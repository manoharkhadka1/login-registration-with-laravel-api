<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function searchUser($keyboard = null) {
        $keyboard = '%'.$keyboard.'%';
        $users = User::where('name', 'like', '%'.$keyboard.'%')
            ->orWhere('email', 'like', '%'.$keyboard.'%')
            ->get();

        return response()->json(['success'=>true, 'data'=>$users]);
    }
}
