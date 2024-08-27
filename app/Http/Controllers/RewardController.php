<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidExchangeRewardException;
use App\Repositories\CoinHistoryRepositoryInterface;
use App\Repositories\RewardClaimRepositoryInterface;
use App\Repositories\RewardRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Throwable;

class RewardController extends Controller
{
    protected $userRepository;
    protected $coinHistoryRepository;
    protected $rewardRepository;
    protected $rewardClaimRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        CoinHistoryRepositoryInterface $coinHistoryRepository,
        RewardRepositoryInterface $rewardRepository,
        RewardClaimRepositoryInterface $rewardClaimRepository
    ) {
        $this->userRepository = $userRepository;
        $this->coinHistoryRepository = $coinHistoryRepository;
        $this->rewardRepository = $rewardRepository;
        $this->rewardClaimRepository = $rewardClaimRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Rewards/List', [
            'rewards' => $this->getData()
        ]);
    }

    public function getData()
    {
        $repo = $this->rewardRepository;
        $repo->setWhereArg([
            ['status', '=', 'publish']
        ]);
        $repo->setWithRelation(['partner']);
        $repo->setPerPage(5);
        $rewards = $repo->getAll();

        return response()->json($rewards);
    }

    public function exchange(string $id)
    {
        DB::beginTransaction();
        try {
            $reward = $this->rewardRepository->getById($id);
            $user = $this->userRepository->getById(Auth::id());

            if ($user->coin < $reward->price) {
                throw new InvalidExchangeRewardException('Koin Belum Mencukupi', 'Belum cukup nih, kumpulkan lebih banyak lagi yaa.');
            }

            if ($reward->quantity == 0) {
                throw new InvalidExchangeRewardException('Stok Sudah Habis', 'Yaaa stok udah habis ni, coba lagi besok yaa.');
            }

            $this->userRepository->cutCoin($user, $reward->price);
            $this->coinHistoryRepository->create($user, [
                'coin' => $reward->price,
                'type' => 'cut',
                'description' => 'Penukaran ' . $reward->coin . ' coin dengan produk ' . $reward->title
            ]);
            $this->rewardClaimRepository->create($reward, $user, []);
            $this->rewardRepository->decreaseQuantity($reward->id);

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Berhasil']);
        } catch (\Throwable $e) {
            Log::error($e);
            DB::rollBack();
            if ($e instanceof InvalidExchangeRewardException) {
                return response()->json(['message' => $e->getMessage(), 'reason' => $e->getReason()], 400);
            }
            return response()->json(['message' => 'Terjadi Kesalahan','reason' => $e->getMessage()], 400);
        }
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {

        return Inertia::render('Rewards/Detail', [
            'id' => $id,
        ]);
    }

    public function showGetData(string $id)
    {
        $repo = $this->rewardRepository;
        $repo->setWithRelation(['partner']);
        return response()->json(['data' => $repo->getById($id)]);
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }

    public function claimsAll()
    {
        return Inertia::render('Rewards/History', [
            'rewards' => $this->getDataClaims()
        ]);
    }

    public function getDataClaims()
    {
        $repo = $this->rewardClaimRepository;
        $repo->setWhereArg([
            ['user_id', '=', Auth::id()]
        ]);
        $repo->setWithRelation(['reward', 'user']);
        $repo->setPerPage(5);
        $rewards = $repo->getAll();

        return response()->json($rewards);
    }
}
