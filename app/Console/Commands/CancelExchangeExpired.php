<?php

namespace App\Console\Commands;

use App\Enums\NotificationEnum;
use App\Enums\RewardClaimEnum;
use App\Notifications\UserNotification;
use App\Repositories\CoinHistoryRepositoryInterface;
use App\Repositories\RewardClaimRepositoryInterface;
use App\Repositories\RewardRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CancelExchangeExpired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cancel-exchange-expired';
    protected $rewardRepository;
    protected $rewardClaimRepository;
    protected $coinHistoryRepository;
    protected $userRepository;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct(
        RewardRepositoryInterface $rewardRepository,
        RewardClaimRepositoryInterface $rewardClaimRepository,
        CoinHistoryRepositoryInterface $coinHistoryRepository,
        UserRepositoryInterface $userRepository
    ) {
        parent::__construct();
        $this->rewardRepository = $rewardRepository;
        $this->rewardClaimRepository = $rewardClaimRepository;
        $this->coinHistoryRepository = $coinHistoryRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Cancel Reward Claim Expired...');

        DB::beginTransaction();
        try {
            $rewardClaim = $this->rewardClaimRepository;
            $rewardClaim->setWithRelation(['reward']);
            $rewardClaim->setWhereArg([
                ['created_at', '<=', now()->subDay()],
                ['status', '=', RewardClaimEnum::WAITING_CONFIRMATION]
            ]);
            foreach ($rewardClaim->getAll() as $claim) {
                $this->rewardClaimRepository->update([
                    'status' => RewardClaimEnum::REJECTED,
                    'reason_rejection' => 'kadaluarsa'
                ], $claim['id']);
                $user = $this->userRepository->getById($claim['user_id']);
                $this->userRepository->addCoin($user, $claim['price']);
                $user->notify(new UserNotification(
                    NotificationEnum::EXCHANGE_CANCELED,
                    "Penukaran produk " . $claim['reward']['title'] . " telah kadaluarsa, " . $claim['price'] . " coin telah dikembalikan."
                ));
                $this->info("Reward Claim with ID " . $claim['id']. " has canceled");
            }

            $this->info('Successfully Cancel Reward Claim Expired.');
            DB::commit();
        } catch (\Exception $e) {
            $this->info("ERROR Cancel Reward Claim Expired");
            Log::error($e->getMessage());
            DB::rollback();
        }
    }
}
