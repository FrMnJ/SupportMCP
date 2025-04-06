<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserControllerAPI extends Controller
{
    public function index(){
        $users = User::all();
        $users = $users->map(function ($user) {
            return $user->toDto();
        });
        return response()->json($users, 200);
    }

    public function store(Request $request)
    {
        // Check if the user already exists
        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser) {
            return response()->json(['user' => $existingUser->toDto()], 200);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'number' => 'nullable|string|max:15',
            'extension' => 'nullable|string|max:5',
            'department' => 'nullable|string|max:255',
        ]);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'number' => $request->number,
            'extension' => $request->extension,
            'department' => $request->department,
        ]);

        $user->assignRole('client');
        return response()->json(['user' => $user->toDto()], 200);
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['These credentials do not match our records.']
            ], 404);
        }

        $token = $user->createToken('my-app-token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }
}
