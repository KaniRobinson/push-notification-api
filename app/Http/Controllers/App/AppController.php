<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use App\App;

class AppController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $details = $request->only('url');

        $validation = Validator::make($details, [
            'url' => 'required|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/|string|unique:apps'
        ]);

        if ($validation->fails()) {
            $response['error'] = [$validation->errors()];
        } else {
            $response = App::create([
                'url' => $request->input('url')
            ]);
        }

        return response()->json($response, 200);
    }
}
