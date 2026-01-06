<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Validator;
use Auth;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error Occurred',
                'data' => $validator->errors()
            ]);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = Admin::create($input);

        $success['token'] = $user->createToken('auth_token')->plainTextToken;
        $success['name'] = $user->name;

        return response()->json([
            'success' => true,
            'message' => 'Register Successfully',
            'data' => $success
        ]);
    }

    public function login(Request $request){
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $auth = Auth::guard('admin')->user();
            $success['token'] = $auth->createToken('auth_token')->plainTextToken;
            $success['name'] = $auth->name;
            $success['email'] = $auth->email;
            $success['license'] = $auth->license;
            $success['phoneno'] = $auth->phoneno;
            $success['address'] = $auth->address;

            return response()->json([
                'success' => true,
                'message' => 'Login Successfully',
                'data' => $success
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Check Email and Password again',
                'data' => null
            ]);
        }
    }

    public function deleteAdmin(Request $request)
    {
        $user = $request->user(); // Get the authenticated user via token

        if ($user) {
            $user->tokens()->delete(); // Revoke all tokens
            $user->delete(); // Delete the user from the database

            return response()->json([
                'success' => true,
                'message' => 'Admin deleted successfully',
                'data' => null
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Admin not found or unauthorized',
            'data' => null
        ], 404);
    }

    public function logout(Request $request)
    {
        // Revoke the user's current token
        $user = $request->user();  // Get the authenticated user
        $user->tokens()->delete(); // Delete all tokens for the user

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ], 200);
    }
}
