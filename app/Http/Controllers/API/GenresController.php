<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Genres;

class GenresController extends Controller
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
        $genres = Genres::get();

        return response([
            'message' => 'tampil data berhasil!',
            'data' => $genres,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
        ], [
            'required' => 'name harus diisi!.',
            'min' => 'name minimal 3 karakter.'
        ]);

        $genres = Genres::create([
            'name' => $request->input('name'),
        ]);

        return response([
            'message' => 'Tambah Genre berhasil',
            'data' => $genres
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $genre = Genres::with('list_movies')->find($id);

        if (!$genre) {
            return response([
                'message' => "Genre $id tidak ditemukan!",
            ], 404);
        };

        return response([
            'message' => "Detail Data Genre",
            'data' => $genre,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|min:3',
        ], [
            'required' => 'name harus diisi!.',
            'min' => 'name minimal 3 karakter.'
        ]);

        $genre = Genres::find($id);

        if (!$genre) {
            return response([
                'message' => "Genre $id tidak ditemukan!",
            ], 404);
        };

        $genre->name = $request->input('name');
        $genre->save();

        return response([
            'message' => "Berhasil melakukan update Genre id : $id!",
            'data' => $genre,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $genre = Genres::find($id);

        if (!$genre) {
            return response([
                'message' => "Genre $id tidak ditemukan!",
            ], 404);
        };

        $genre->delete();
        return response([
            'message' => "Berhasil Menghapus genre!",

        ], 201);
    }
}
