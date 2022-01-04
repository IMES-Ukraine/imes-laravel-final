<?php

namespace App\Services;

use App\Models\Notifications;

class NotificationService
{

    public function sendToUser($user, $message, )
    { $this->sendNotificationToUser($user, Notifications::TYPE_MESSAGE, $body, $extraFields, $title);

    }
}
