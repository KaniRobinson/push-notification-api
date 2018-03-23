<?php

namespace App\Http\Controllers\Notification;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use App\User;

use App\App;

use App\Notifications;


class NotificationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $details = $request->only( 'title', 'body', 'action', 'key', 'user_id' );

        $validation = Validator::make($details, [
            'user_id' => 'required|string|exists:users,user_id',
            'key' => 'required|string|exists:apps,key',
            'title' => 'required|string',
            'body' => 'required|string',
            'action' => 'required|string'
        ]);

        if ($validation->fails()) {
            $response['error'] = [$validation->errors()];
        } else {
            $users = User::where([
                'user_id' => $request->input('user_id')
            ])->get();

            $app = App::where([
                'key' => $request->input('key')
            ])->first();

            if(!$app) {
                $response['error'] = ['verification' => ['Authentication Failed']];
            } else {
                $response = [];
                foreach($users as $user)
                {
                    array_push($response, Notifications::create([
                        'app_id' => $app->id,
                        'user_id' => $user->id,
                        'title' => $request->input('title'),
                        'body' => $request->input('body'),
                        'action' => $request->input('action')
                    ]));
                }
            }
        }

        return response()->json($response, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $status)
    {
        $details = [
            'id' => $id,
            'status' => $status
        ];

        $validation = Validator::make($details, [
            'id' => 'required|string|exists:notifications,id',
            'status' => 'required|string|in:0,1,2'
        ]);

        if ($validation->fails()) {
            $response['error'] = [$validation->errors()];
        } else {
            $notifications = Notifications::find($id);
            $notifications->status = (string)$status;
            $notifications->save();

            $response = $notifications;
        }

        return response()->json($response, 200);
    }
}
