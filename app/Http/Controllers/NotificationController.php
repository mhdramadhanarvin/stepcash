<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NotificationController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository->getById(Auth::id());
    }

    public function index()
    {
        return Inertia::render('Notifications/List');
    }

    public function getData()
    {
        $user = User::find(Auth::id());
        $notifications = $user->notifications()->latest()->paginate(5);

        return response()->json($notifications);
    }

    public function getDataUnread()
    {
        $repo = $this->userRepository->unreadNotifications;

        return response()->json(['data' => $repo]);
    }

    public function setRead(string $id)
    {

        return $this->userRepository->notifications->find($id)->markAsRead();
    }

    public function setReadAll()
    {
        return $this->userRepository->notifications->markAsRead();
    }
}
