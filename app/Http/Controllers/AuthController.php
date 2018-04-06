<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterFormRequest;
use JWTAuth;
use Auth;
use App\User;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function register(RegisterFormRequest $request) {
      $date = explode('(', $request->birthday);
      $birthday = new Carbon($date[0]);
      $data = $birthday->toDateTimeString();
      $user = new User;
      $user->email = $request->email;
      $user->name = $request->name;
      $user->birthday = $data;
      $user->gender = $request->gender;
      $user->password = bcrypt($request->password);
      $user->save();

      return response ([
        'status' => 'success',
        'data' => $user
      ], 200);
    }

    public function login(Request $request) {
      $credentials = $request->only('email', 'password');
      if( !$token = JWTAuth::attempt($credentials)) {
        return response ([
          'status' => 'error',
          'error' => 'invalid.credentials',
          'msg' => 'E-mail ou senha inválidos.'
        ], 400);
      }

      return response([
        'status' => 'success'
      ])
      ->header('Authorization', $token);
    }

    public function user(Request $request) {
      $user = User::where('id', Auth::user()->id)->with('team')->first();

      return response([
        'status' => 'success',
        'data' => $user
      ]);
    }

    public function refresh() {
      return response ([
        'status' => 'success'
      ]);
    }

    public function logout()
    {
      JWTAuth::invalidate();
      return response([
        'status' => 'success',
        'msg' => 'Logged out Successfully.'
      ], 200);
    }
}
