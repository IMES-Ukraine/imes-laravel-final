<?php
namespace App\Traits;

use App\Models\User;
use App\Models\Notifications;
use App\Models\NotificationsStatus;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Factory;
use Kreait\Firebase\JWT\CustomTokenGenerator;
use Kreait\Firebase\Messaging\CloudMessage;


trait NotificationsHelper
{
    use RetrieveFirebaseToken;

    private function defaultPayload($text, $title){
        return [
            'body' => $text,
            'title' => $title,
            'sound' => 'default',
            'badge' => 1
        ];
    }

    protected function sendNotificationToAll($type, $text, $extraFields = [], $title = '') {

        $title = $title??$text;

        $users = User::where('is_verified', 1)->where('is_activated', 1)->select( 'id', 'firebase_token')->get();


        $notification = new Notifications();
        $notification->user_id = 0;
        $notification->type = $type;
        $notification->text = [
            'title' => $title,
            'content' => $text,
        ];
        if(isset( $extraFields['action'])) {
            $notification->action = $extraFields['action'];
        }
        if(isset( $extraFields['image'])) {
            $notification->image = $extraFields['image'];
        }

        $notification->save();

        foreach ( $users as $user){
            $token = $user->firebase_token;
            if (! $token || strlen($token) > 163){
                $user->firebase_token = null;
                $user->save();
                continue;
                }

                $res = $this->sendNotification(
                    $token,
                    $this->defaultPayload($text, $title),
                    array_merge([
                        'user_id' => $notification->user_id,
                        'type' => $notification->type,
                        'action' => $notification->action,
                    ], $extraFields),
                    $type
                );
                if (isset($res)){
                    $status = new NotificationsStatus();
                    $status->user_id = $user->id;
                    $status->notification_id = $notification->id;
                    $status->save();
                }
            }
        return true;

    }

//    protected function sendNotificationToIds( int $id){
//        $clientEmail = "firebase-adminsdk-9r636@imes-v1.iam.gserviceaccount.com";
//        $privateKey = "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCtMO0mT4tjcsqG\nrpcstguUepDpEKII3m5jeNPqdjslTfLsVYa1pEUKYy0jiG+ra5CDEnoXQwnBeSb3\nEOW4Q1WlW+YIoJvoNg4d47LGCyrrNVhAdZPwjFMOtXQNzxP5xgzBlTuqqbzduqAE\niADuv96m881JK+FiGmexmNXoqQvpOhqmXRm2Y7gD5M/cL5dvX+SjIKU0vAkKxgrh\n5WneyWVnblsPU/eH4Y5dOz5rujK9aMRN/3bUaMdvF3vs6O7DH3FdzCSi0niQfkWG\nm9O1T8L3b5soqsFaJhkmwn3wBkmc2ACVjk95pd4y3zjYOPy5iWUkFXPYfNedXgcR\nrklyc/QjAgMBAAECggEAN7hfBA1hfhxndk9jidoD/cA1MRbN2uadQ1mTbIKfYtAi\nUNDvZy8zmzTvR9hUfSU9OD5Qk31Y/SsaUzDXh6H6Qxo+9xWdM2d4wTsJwFfFdikS\nfKKXdzPeYQQWE39FIP4MCpgVu0Gi5v9tDignjKikXLhhmqIIgESYifXvFEyW7XDh\naJVeLFekOz9hI092DTjz3ZWBEfTN6pqJADw4y2AavlCTvSRE6HjXaAww3DsVG5EO\nJisvOLmr1eI/zkjtHfcKW8yTgs+CLfAr6zoim6lPTD5rmDoFXY8BGad0aPL5OOHn\npvoRZmUkoBzRsd7m+dHFDxIO3e59FAwzvEKGnBCeQQKBgQDhLawYRA+MjM5my5Kq\n+ut+qKJDAqY58AS2GMrvx8IICW07BA91eNtQzd66bKKGImpJf7bf+9WNJkfUOfcQ\nhuRUBhonnZOQWYxVk/cOCUkUwqzL4eKSPGlgbeiqkWrGMHo17rk1kRtuuqKEu1bW\nn/eINgqdFthDQOhhs6nveRuLQwKBgQDE5ZoV9PKPA2jeohhliDiBDBHhdJvseC1h\nuAeuiIPZLe7W9OQa8gvKYGQ23p13tb1L3Vvp232GOMJgUEme62d2hcjYwwO33Md+\nMNrsrCLo2qnfkw75FIeSKlRFLXFLvrH5HjWbq1eDsawgfXj7gu9zKywq6HqLzqIc\nM3VdVHm1oQKBgQDdrH2fwdjgz3kdv0ia47vTi6V5fHEx1jR9I3kchjVgfg83Ku8h\nASI+sPyT4AA7NkQWKRFZ/OwlMUtDBPFRJso+YWd8Gmc7krxRPwHWopN8SY2chLns\npmZClNCYoeFQTEbzMxjHQBuWbUsyic6aKQ6g3DqknOm3g32BOUKQylOwSwKBgDkB\n/VYEIMKViySCrCuhgDKk6vxrBcY2mXUkklzPO9WCvhdQukau9Aj5VaqpU9BYN2Gi\nyrzwBuz4vBakyFwR7lfbrmZMOGRgsQXxYUGLKWRgAzUvX/NwJTyFsvaAjMAQi3kE\nbSMQPftsUtjpW0D/DRM8WcJmoyydfERMBdwq3D5hAoGBALwQRruisdc9UHuXp2Ze\nVqqlZENKvJQlV/MYAOaqezJ9PyTQTsc3HTksw9FdM0It9daxvgd/gykOFZBrhbxk\ntbkf6oqfJVUlZ6uifs9PUZenQMd1VXJxMvTReNaCO293mriYE3npKmoXcJE+as4+\nZlBt6tOAltKpzelS+yhw2OtL\n-----END PRIVATE KEY-----\n";
//
//        $generator = CustomTokenGenerator::withClientEmailAndPrivateKey($clientEmail, $privateKey);
//        $token = $generator->createCustomToken('uid', ['first_claim' => 'first_value']);
//
//        return $token->toString();
//    }

    protected function sendNotificationToUser( $user, $type, $text, $extraFields = [], $title = '') {


        $title = $title??$text;

        $notification = new Notifications();
        $notification->user_id = $user->id;
        $notification->type = $type;
        $notification->text = [
            'title' => $title,
            'content' => $text,
        ];
        if(isset( $extraFields['action'])) {
            $notification->action = $extraFields['action'];
        }
        if( $notification->save()) {
            $extraFields['id'] = $notification->id;
        }
        if (! $user->firebase_token){
            return false;
        }
        $this->sendNotification(
            $user->firebase_token,
            $this->defaultPayload($text, $title),
            array_merge([
                'user_id' => $notification->user_id,
                'type' => $notification->type,
                'action' => $notification->action,
            ], $extraFields),
            $type
        );
    }

    private function createMessaging() {

        $factory = (new Factory)
            ->withServiceAccount(base_path('resources/notifications/imes-v1-firebase-adminsdk-9r636-7af97231d5.json'));

        return $factory->createMessaging();
    }


    /**
     * @param $userToken
     * @param $msg
     * @param array $data
     * @param $type
     * @return array
     * @throws FirebaseException
     * @throws MessagingException
     */
    protected function sendNotification($userToken, $msg, $data = [], $type ): array
    {


        $data['click_action'] = 'FLUTTER_NOTIFICATION_CLICK';

        $messaging = $this->createMessaging();

        $message = CloudMessage::fromArray([
            'token' => $userToken,
            'notification' => $msg, // optional
            'data'  => $data,
        ]);

        return $messaging->send($message);
    }

    /**
     * Sends notification to contact
     * @param $userToken
     * @param $msg
     * @param null $data
     * @return mixed
     */
    protected function sendNotificationToTopic( $userToken, $msg, $data = [], $type ) {


        $data['click_action'] = 'FLUTTER_NOTIFICATION_CLICK';

        $messaging = $this->createMessaging();


        $subscriptionResult = $messaging->subscribeToTopic(Notifications::TOPIC_MAPPING[$type], $userToken);
        foreach ( $subscriptionResult[Notifications::TOPIC_MAPPING[$type]] as $key => $value) {
            $topicKey = $key;
            break;
        }

        $message = CloudMessage::fromArray([
            //'condition' => $condition,
            'topic' => Notifications::TOPIC_MAPPING[$type],//array_key_first( $result['balance']),
            'to' => $topicKey,
            'notification' => $msg, // optional
            'data'  => $data,
        ]);

        $var = $messaging->send($message);
    }

}
