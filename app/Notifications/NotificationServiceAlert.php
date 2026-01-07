<?php

namespace App\Notifications;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class NotificationServiceAlert
{
    protected $messaging;

    public function __construct()
    {
        $path = base_path(env('FIREBASE_CREDENTIALS'));
        $factory = (new Factory)->withServiceAccount($path);
        $this->messaging = $factory->createMessaging();
    }

    public function sendPush($token, $title, $body)
    {
        if (empty($token)) return;

        $notification = Notification::create($title, $body);
        $message = CloudMessage::withTarget('token', $token)
            ->withNotification($notification);

        try {
            $this->messaging->send($message);
        } catch (\Exception $e) {
            \Log::error("Erreur Push Firebase: " . $e->getMessage());
        }
    }
}