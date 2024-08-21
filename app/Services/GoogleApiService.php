<?php

namespace App\Services;

use Carbon\Carbon;
use Google\Client;
use Google\Service\Fitness;
use Google\Service\Fitness\BucketByTime;
use Google\Service\Fitness\AggregateRequest;
use Google\Service\Fitness\DataSource;
use App\Repositories\TokenRepositoryInterface;

class GoogleApiService
{
    public $config;
    public $accessToken;
    public $refreshToken;
    public $expirationToken;
    private $startTime;
    private $endTime;
    private $duration;
    protected $tokenRepository;

    public function __construct(
        TokenRepositoryInterface $tokenRepository
    ) {
        $config = new Client();
        $config->setAuthConfig(config('app.google_credentials_path'));

        $this->tokenRepository = $tokenRepository;

        return $this->config = $config;
    }

    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        $this->config->setAccessToken($this->accessToken);
    }

    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;
    }

    public function setExpirationToken($expirationToken)
    {
        $this->expirationToken = $expirationToken;
    }

    public function isInvalidOrExpireToken()
    {
        return Carbon::now()->greaterThan($this->expirationToken);
    }

    public function refreshToken($refresh_token)
    {
        if ($this->isInvalidOrExpireToken()) {
            return $this->config->fetchAccessTokenWithRefreshToken($refresh_token);
        }

        return null;
    }

    public function autoRefreshToken($user)
    {
        $new_token = $this->refreshToken($this->refreshToken);

        if (!is_null($new_token)) {
            return $this->tokenRepository->update([
                'token' => $new_token['access_token'],
                'expired_at' => now()->addSeconds(60 * 60)
            ], $user->id);
        }

        return null;
    }

    public function getFitnessData($dataType)
    {
        switch ($dataType) {
            case "steps":
                $dataTypeName = 'com.google.step_count.delta';
                break;
            case "calories":
                $dataTypeName = 'com.google.calories.expended';
                break;
            case "time":
                $dataTypeName = 'com.google.active_minutes';
                break;
            case "distance":
                $dataTypeName = 'com.google.distance.delta';
                break;
            default:
                // Handle invalid data type or unknown cases
                return null;
        }

        $fitness = new Fitness($this->config);
        // prepare request
        $aggregateRequest = new AggregateRequest();

        $bucketByTime = new BucketByTime();
        $bucketByTime->setDurationMillis($this->duration);

        $aggregateRequest->setAggregateBy(['dataTypeName' => $dataTypeName]);
        $aggregateRequest->setBucketByTime($bucketByTime);
        $aggregateRequest->setStartTimeMillis($this->startTime);
        $aggregateRequest->setEndTimeMillis($this->endTime);

        return $fitness->users_dataset->aggregate('me', $aggregateRequest);
    }

    public function getDataSource()
    {
        $fitness = new Fitness($this->config);
        return $fitness->users_dataSources->listUsersDataSources('me')['dataSource'];
    }

    public function syncData($user)
    {
        $this->setAccessToken($user->token->token);
        $this->setRefreshToken($user->token->refresh_token);
        $this->setExpirationToken($user->token->expired_at);

        $this->autoRefreshToken($user);

        $this->setDuration(86400000);
        $this->setStartTime(Carbon::now()->startOfDay()->getTimestampMs());
        $this->setEndTime(Carbon::now()->getTimestampMs());

        return [
            'steps'         => isset($this->getFitnessData('steps')->bucket[0]->dataset[0]->point) &&
                count($this->getFitnessData('steps')->bucket[0]->dataset[0]->point) > 0 &&
                isset($this->getFitnessData('steps')->bucket[0]->dataset[0]->point[0]->value) &&
                count($this->getFitnessData('steps')->bucket[0]->dataset[0]->point[0]->value) > 0 ?
                $this->getFitnessData('steps')->bucket[0]->dataset[0]->point[0]->value[0]->intVal : 0,
            'calories'      => isset($this->getFitnessData('calories')->bucket[0]->dataset[0]->point) &&
                count($this->getFitnessData('calories')->bucket[0]->dataset[0]->point) > 0 &&
                isset($this->getFitnessData('calories')->bucket[0]->dataset[0]->point[0]->value) &&
                count($this->getFitnessData('calories')->bucket[0]->dataset[0]->point[0]->value) > 0 ?
                $this->getFitnessData('calories')->bucket[0]->dataset[0]->point[0]->value[0]->fpVal : 0,
            'distances'     => isset($this->getFitnessData('distance')->bucket[0]->dataset[0]->point) &&
                count($this->getFitnessData('distance')->bucket[0]->dataset[0]->point) > 0 &&
                isset($this->getFitnessData('distance')->bucket[0]->dataset[0]->point[0]->value) &&
                count($this->getFitnessData('distance')->bucket[0]->dataset[0]->point[0]->value) > 0
                ? $this->getFitnessData('distance')->bucket[0]->dataset[0]->point[0]->value[0]->fpVal / 1000 : 0,
            'time_spent'    => isset($this->getFitnessData('time')->bucket[0]->dataset[0]->point) &&
                count($this->getFitnessData('time')->bucket[0]->dataset[0]->point) > 0 &&
                isset($this->getFitnessData('time')->bucket[0]->dataset[0]->point[0]->value) &&
                count($this->getFitnessData('time')->bucket[0]->dataset[0]->point[0]->value) > 0 ?
                $this->getFitnessData('time')->bucket[0]->dataset[0]->point[0]->value[0]->intVal : 0
        ];
    }

    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    }

    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;
    }

    public function setDuration($duration)
    {
        $this->duration = $duration;
    }
}
