<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function storeUpdate(Request $request)
    {
        $request->validate([
            'critic' => 'required',
            'point' => 'required|integer',
            'movie_id' => 'required|exists:movies,id',
        ], [
            'required' => 'input :attribute harus diisi!.',
            'integer' => 'input :attribute harus berupa angka',
            'exists' => 'input :attribute tidak ditemukan di table movies!',
        ]);

        $user = auth()->user();
        $review = Review::updateOrCreate(['user_id' => $user->id], [
            'critic' => $request->input('critic'),
            'point' => $request->input('point'),
            'movie_id' => $request->input('movie_id'),
        ]);

        return response()->json([
            'message' => 'Review berhasil dibuat/diubah',
            'data' => $review,
        ], 201);
    }
}
