<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;

class ProfileController extends Controller
{
    public function updateOrCreate(Request $request)
    {
        $request->validate([
            'age' => 'required|integer',
            'biodata' => 'sometimes',
            'address' => 'required',
        ], [
            'required' => 'input :attribute harus diisi!.',
            'integer' => 'input :attribute harus berupa angka',
        ]);

        $user = auth()->user();
        $profile = Profile::updateOrCreate(['user_id' => $user->id], [
            'age' => $request->input('age'),
            'biodata' => $request->input('biodata'),
            'address' => $request->input('address'),
        ]);



        return response()->json([
            'message' => 'Profile berhasil diubah',
            'data' => $profile,
        ], 201);
    }
}
