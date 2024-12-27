<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cast_Movie;
use Illuminate\Http\Request;

class CastMovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {

        $this->middleware('isAdmin')->except(['index', 'show']);
    }

    public function index()
    {
        $cast_movie = Cast_Movie::all();

        return response([
            "message" => "tampil data berhasil",
            "data" => $cast_movie

        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'cast_id' => 'required|exists:casts,id',
            'movie_id' => 'required|exists:movies,id',
        ], [
            'required' => 'input :attribute harus diisi!.',
            'exists' => 'input :attribute tidak ditemukan di table movies!',
        ]);

        $casts_movie = new Cast_Movie;
        $casts_movie->name = $request->input('name');
        $casts_movie->cast_id = $request->input('cast_id');
        $casts_movie->movie_id = $request->input('movie_id');

        $casts_movie->save();

        return response([
            'message' => 'Tambah Movie berhasil',
            'data' => $casts_movie,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cast_movie = Cast_Movie::with(['movie', 'cast'])->find($id);

        if (!$cast_movie) {
            return response()->json([
                'message' => "Data cast movie tidak ditemukan!",

            ], 404);
        }

        return response()->json([
            'message' => "Berhasil Tampil cast Movie",
            'data' => $cast_movie
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $request->validate([
            'name' => 'required',
            'cast_id' => 'required|exists:casts,id',
            'movie_id' => 'required|exists:movies,id',
        ], [
            'required' => 'input :attribute harus diisi!.',
            'exists' => 'input :attribute tidak ditemukan di table movies!',
        ]);

        $cast_movie = Cast_Movie::find($id);

        if (!$cast_movie) {
            return response()->json([
                'message' => "Data cast movie tidak ditemukan!",

            ], 404);
        }

        $cast_movie->name = $request->input('name');
        $cast_movie->cast_id = $request->input('cast_id');
        $cast_movie->movie_id = $request->input('movie_id');

        $cast_movie->save();

        return response([
            'message' => 'Berhasil Update cast Movie',
            'data' => $cast_movie,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cast_movie = Cast_Movie::find($id);

        if (!$cast_movie) {
            return response()->json([
                'message' => "Data cast movie tidak ditemukan!",

            ], 404);
        }

        $cast_movie->delete();
        return response([
            'message' => 'berhasil Menghapus Cast Movie!',
        ], 201);
    }
}
