<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Roles;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterMail;
use App\Mail\GenerateOtp;
use App\Models\Otpcode;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,id',
            'password' => 'required|min:8|confirmed',
        ], [
            'required' => 'input :attribute harus diisi!.',
            'min' => 'input  :atribute minimal :min karakter.',
            'email' => 'inputan email harus berformat email.',
            'unique' => 'inputan email sudah terdaftar.',
            'confirmed' => 'inputan password tidak sama.',
        ]);


        $user = new User;

        $roleUser = Roles::where('name', 'user')->first();

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->role_id = $roleUser->id;

        $user->save();

        $user->makeHidden(['otp_code']);

        Mail::to($user->email)->send(new RegisterMail($user));
        return response([
            'message' => 'user berhasil Register, silahkan cek email Anda!',
            'data' => $user
        ], 200);
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ], [
            'required' => 'input :attribute harus diisi!.',
        ]);

        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = User::with('role')->where('email', $request->input('email'))->first();

        return response([
            'message' => 'user berhasil login',
            'data' => $user,
            'access_token' => $token
        ], 201);
    }

    public function getUserLogged()
    {
        $user = auth()->user();

        return response()->json([
            'message' => 'Profile berhasil ditampilkan',
            'data' => $user
        ]);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Logout Berhasil']);
    }

    public function generate_otp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'required' => 'input :attribute harus diisi!.',
            'email' => 'input harus berformat email'
        ]);

        $user = User::where('email', $request->input(key: 'email'))->first();

        $user->generateOtp();

        Mail::to($user->email)->send(new GenerateOtp($user));

        return response()->json([
            'success' => 'true',
            'message' => 'OTP Code Berhasil di generate',


        ]);
    }

    public function verification(Request $request)
    {
        $request->validate([
            'otp' => 'required|min:6',
        ], [
            'required' => 'input :attribute harus diisi!.',
            'min' => 'input minimal :min karakter'
        ]);

        $user = auth()->user();
        $otp_code = Otpcode::where('otp', $request->input('otp'))->where('user_id', $user->id)->first();

        if (!$otp_code) {
            return response()->json([
                'response_message' => 'OTP Code tidak ditemukan',
                'response_code' => '01'
            ], 400);
        }

        $now = Carbon::now();
        if ($now > $otp_code->expired_at) {
            return response()->json([
                'response_message' => 'otp code sudah tidak berlaku, silahkan generate ulang',
                'response_code' => '01'
            ], 400);
        }

        $user = User::find($otp_code->user_id);

        $user->email_verified_at = $now;

        $user->save();

        $otp_code->delete();

        return response()->json([
            'response_message' => 'email sudah terverifikasi',
            'response_code' => '00'
        ], 200);
    }
}
