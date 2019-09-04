<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class UsersController1 extends Controller
{
    
    public function index()
    {
        
        $users = User::orderBy('created_at', 'desc')->paginate(2);
        return $users;
        //return view('users.index', ['users' => $users]);
    }

    public function destroy($id)
    {
        return ['success' => true] ;
    }

    public function store(Request $request)
    {
        $user = $request->all();

        $user_created = User::create([
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => bcrypt('password')
        ]);

        return $user_created;
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());

        return $user;
    }

}
