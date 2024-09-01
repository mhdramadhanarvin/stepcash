<?php

namespace App\Http\Controllers;

use App\Repositories\NotificationRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NotificationController extends Controller
{
    protected $notificationRepository;

    public function __construct(NotificationRepositoryInterface $notificationRepository)
    {
        $this->notificationRepository  = $notificationRepository;
    }

    public function index()
    {
        return Inertia::render('Notifications/List');
    }

    public function getData()
    {
        $repo = $this->notificationRepository;
        $repo->setWhereArg([
            ['user_id', '=', Auth::id()]
        ]);
        $repo->setPerPage(5);
        $notifications = $repo->getAll();

        return response()->json($notifications);
    }

    public function getDataUnread()
    {
        $repo = $this->notificationRepository;
        $repo->setWhereArg([
            ['user_id', '=', Auth::id()],
            ['is_read', '=', false]
        ]);
        $notifications = $repo->getAll();

        return response()->json(['data' => $notifications]);
    }

    public function setRead(int $id)
    {
        return $this->notificationRepository->update(['is_read' => true], $id);
    }

    public function setReadAll()
    {
        $notifications = $this->notificationRepository;
        $notifications->setWhereArg([
            ['user_id', '=', Auth::id()]
        ]);
        foreach ($notifications->getAll() as $notification) {
            $this->notificationRepository->update(['is_read' => true], $notification->id);
        }
    }
}
