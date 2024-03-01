<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class UserManageController extends Controller
{

    // after login
    public function AllUser(Request $request)
    {
        $email = $request->header('email');
        $user = User::where('email', '=', $email)->first();
        return view('pages.dashboard.user-page', compact('user'));
    }
    public function UserList()
    {
        $data =  User::all();
        return response($data);
    }
    function UserCreate(Request $request)
    {
        try {
            User::create([
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'password' => $request->input('password'),
                'user_type' => $request->input('user_type'),
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'User Create Successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'User Create Failed'
            ], 200);
        }
    }

    function UserByID(Request $request)
    {
        $user_id = $request->input('id');
        return User::where('id', $user_id)->first();
    }

    function EditUserData(Request $request)
    {
        $user_id = $request->input('id');
        return User::where('id', '=', $user_id)->update([
            'firstName' => $request->input('firstName'),
            'lastName' => $request->input('lastName'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
            'password' => $request->input('password'),
            'user_type' => $request->input('user_type')
        ]);
    }


    function UserDelete(Request $request)
    {
        $user_id = $request->input('id');
        return User::where('id', $user_id)->delete();
    }

}
