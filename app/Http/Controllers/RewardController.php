<?php

namespace App\Http\Controllers;

use App\Repositories\CoinHistoryRepositoryInterface;
use App\Repositories\RewardClaimRepositoryInterface;
use App\Repositories\RewardRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

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
                throw new \Error('Coin tidak cukup');
            }

            $this->userRepository->cutCoin($user, $reward->price);
            $this->coinHistoryRepository->create($user, [
                'coin' => $reward->price,
                'type' => 'cut',
                'description' => 'Penukaran ' . $reward->coin . ' coin dengan produk ' . $reward->title
            ]);
            $this->rewardClaimRepository->create($reward, $user, []);

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Berhasil']);
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return response()->json(['status' => false, 'message' => $e->getMessage()], 400);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $repo = $this->rewardRepository;
        $repo->setWithRelation(['partner']);
        $reward = $repo->getById($id);

        return Inertia::render('Rewards/Detail', [
            'reward' => $reward,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
