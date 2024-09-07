<?php

namespace App\Http\Controllers;

use App\Enums\NotificationEnum;
use App\Exceptions\InvalidExchangeRewardException;
use App\Models\Reward;
use App\Notifications\ExchangeRewardProcess;
use App\Repositories\CoinHistoryRepositoryInterface;
use App\Repositories\NotificationRepositoryInterface;
use App\Repositories\RewardClaimRepositoryInterface;
use App\Repositories\RewardRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
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
    protected $notificationRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        CoinHistoryRepositoryInterface $coinHistoryRepository,
        RewardRepositoryInterface $rewardRepository,
        RewardClaimRepositoryInterface $rewardClaimRepository,
        NotificationRepositoryInterface $notificationRepository,
    ) {
        $this->userRepository = $userRepository;
        $this->coinHistoryRepository = $coinHistoryRepository;
        $this->rewardRepository = $rewardRepository;
        $this->rewardClaimRepository = $rewardClaimRepository;
        $this->notificationRepository = $notificationRepository;
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
        $transform = $rewards->setCollection($rewards->getCollection()->transform(function ($reward) {
            $reward->thumbnail = asset('storage/' . $reward->thumbnail);
            return $reward;
        }));

        return response()->json($transform);
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
                'description' => 'Penukaran ' . $reward->price . ' coin dengan produk ' . $reward->title
            ]);
            $this->rewardClaimRepository->create($reward, $user, [
                'code' => fake()->regexify('[A-Z]{5}[0-4]{3}')
            ]);
            $this->rewardRepository->decreaseQuantity($reward->id);
            $reward->partner->user->notify(new ExchangeRewardProcess(
                NotificationEnum::getValue('NEW_EXCHANGE'),
                'Penukaran baru pada produk ' . $reward->title . ' senilai ' . $reward->price . ' coin'
            ));

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
        $data = $repo->getById($id);
        $data->thumbnail = asset('storage/' . $data->thumbnail);
        return response()->json(['data' => $data]);
    }

    public function claimsAll($id = null)
    {
        return Inertia::render('Rewards/History', [
            'rewards' => $this->getDataClaims(),
            'detail' => $id == null ? '' : $this->getDataClaimById($id)
        ]);
    }

    public function getDataClaims()
    {
        $repo = $this->rewardClaimRepository;
        $repo->setWhereArg([
            ['user_id', '=', Auth::id()]
        ]);
        $repo->setWithRelation(['reward.partner', 'user']);
        $repo->setPerPage(5);
        $rewardClaims = $repo->getAll();
        $transform = $rewardClaims->through(function ($data) {
            $reward = $this->rewardRepository->getById($data->reward->id);
            $thumbnail = $reward ? $reward->thumbnail : null;

            $thumbnailUrl = $thumbnail ? asset('storage/' . $thumbnail) : null;
            $data->reward->thumbnail = $thumbnailUrl;
            return $data;
        });

        return response()->json($transform);
    }

    public function getDataClaimById($id)
    {
        $repo = $this->rewardClaimRepository;
        $repo->setWithRelation(['reward.partner', 'user']);
        $rewardClaims = $repo->getById($id);
        $rewardClaims->reward->thumbnail = asset('storage/' . $rewardClaims->reward->thumbnail);

        return $rewardClaims;
    }
}
