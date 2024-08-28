<?php

namespace App\Http\Controllers;

use App\Repositories\NotificationRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NotificationController extends Controller
{
    protected $notificationRepository;

    public function __construct(NotificationRepositoryInterface $notificationRepository)
    {
        $this->notificationRepository  = $notificationRepository;
    }
    /**
     * Display a listing of the resource.
     */
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
        $notiofications = $repo->getAll();

        return response()->json($notiofications);
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
        //
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
