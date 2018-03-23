<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Notifications;

use GuzzleHttp\Exception\GuzzleException;

use GuzzleHttp\Client;

class NotificationCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs the scheduled notifications';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $notifications = Notifications::with([ 'user', 'app' ])
            ->where([ 'status' => '0' ])
            ->limit(50)
            ->get();

        if(!$notifications->isEmpty())
        {
            foreach($notifications as $notification)
            {
                $client = new Client();
                $request = $client->post('https://fcm.googleapis.com/fcm/send', [
                    'headers' => [
                        'Authorization' => sprintf('key=%s', 'AAAALmmzgAs:APA91bEj14TltFCV_frxYORytO4DApgDIwPlP24LmVPBQBHCQLjcWCHqsW3Ujx9rbspCJLCejaELVysmBUDSAISo6ioIwSnilSn5gZYQ9dERE_I-Z0yoBptuudk51q0oPya9Hsh7f-RM'),
                        'Content-Type' => 'application/json'
                    ],
                    'body' => json_encode([
                        "to" => $notification->user->token,
                        "notification" => [
                            "body" => $notification->body,
                            "title" => $notification->title,
                            "click_action" => $notification->action,
                            "sound" => $notification->sound
                        ],
                        "data" => [
                            "body" => $notification->body,
                            "title" => $notification->title,
                            "click_action" => $notification->action,
                            "sound" => $notification->sound
                        ]
                    ])
                ]);
    
                $response = json_decode($request->getBody()->getContents());
    
                $notification->status = ($response->success == '1') ? '1': '2';
                $notification->save();
            }
        }
    }
}
