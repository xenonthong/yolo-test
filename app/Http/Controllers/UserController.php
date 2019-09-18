<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @param int                      $id
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function show(int $id, Request $request)
    {
        return User::findOrFail($id);
    }
}
