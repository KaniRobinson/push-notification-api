<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use App\User;

use App\App;

class UserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $details = $request->only( 'user_id', 'app_id', 'token', 'key' );

        $validation = Validator::make($details, [
            'user_id' => 'required|string',
            'app_id' => 'required|string|exists:apps,app_id',
            'token' => 'required|string|unique:users',
            'key' => 'required|string|exists:apps,key'
        ]);

        if ($validation->fails()) {
            $response['error'] = [$validation->errors()];
        } else {
            $app = App::where([
                'app_id' => $request->input('app_id'),
                'key' => $request->input('key')
            ])->first();

            $response = User::create([
                'user_id' => $request->input('user_id'),
                'app_id' => $app->id,
                'token' => $request->input('token')
            ]);
        }

        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->active = false;
        $user->save();

        return $user;
    }
}
