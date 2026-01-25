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

    // Dans NotificationServiceAlert.php
    public function sendPush($token, $title, $body)
    {
        if (empty($token)) return;

        // Création du message avec priorité haute
        $message = CloudMessage::withTarget('token', $token)
            ->withNotification(Notification::create($title, $body))
            ->withAndroidConfig([
                'priority' => 'high',
                'notification' => [
                    'channel_id' => 'high_importance_channel', // DOIT CORRESPONDRE À FLUTTER
                    'icon' => 'stock_ticker_update',
                    'color' => '#f45342'
                ],
            ]);

        try {
            $this->messaging->send($message);
        } catch (\Exception $e) {
            \Log::error("Erreur Push Firebase: " . $e->getMessage());
        }
    }
}