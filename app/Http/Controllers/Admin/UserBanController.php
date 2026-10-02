<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserBanController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('auth');
//        $this->middleware('role:admin|moderator');
//    }

        public function create(User $user)
        {
            return inertia('Admin/User/Ban', compact('user'));
        }
}
