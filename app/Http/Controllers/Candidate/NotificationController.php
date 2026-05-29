<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Services\CandidateNotificationFeed;

class NotificationController extends Controller
{
    public function __construct(private CandidateNotificationFeed $notificationFeed)
    {
    }

    


    public function index()
    {
        $notifications = $this->notificationFeed->forUser(auth()->user());

        $unreadCount = collect($notifications)->where('read', false)->count();
        $readCount = collect($notifications)->where('read', true)->count();

        return view('candidate.notifications', compact('notifications', 'unreadCount', 'readCount'));
    }
}
