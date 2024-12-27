<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\Movie;

class MoviesController extends Controller
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
        $movies = Movie::all();

        return response([
            "message" => "tampil data berhasil",
            "data" => $movies

        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'poster' => 'required|mimes:jpeg,png,jpg|max:2048',
            'title' => 'required|min:3',
            'summary' => 'required',
            'genre_id' => 'required|exists:genres,id',
            'year' => 'required'
        ], [
            'required' => 'input :attribute harus diisi!.',
            'max' => 'input :atribut minimal :max bite',
            'mimes' => 'inputan :atribut harus berformat jpeg,png,jpg',
            'poster' => 'inputan :atribut harus gambar',
            'exists' => 'input :attribute tidak ditemukan di table genres!',
        ]);
        $uploadedFileUrl = cloudinary()->upload($request->file('poster')->getRealPath(), [
            'folder' => 'images',
        ])->getSecurePath();


        $movies = new Movie;

        $movies->title = $request->input('title');
        $movies->summary = $request->input('summary');
        $movies->poster =  $uploadedFileUrl;
        $movies->genre_id = $request->input('genre_id');
        $movies->year = $request->input('year');

        $movies->save();

        return response([
            'message' => 'Tambah Movie berhasil',
            'data' => $movies
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $movie = Movie::with(['genre', 'list_review', 'list_cast'])->find($id);

        if (!$movie) {
            return response([
                'message' => 'Tidak ada Movies!'
            ], 404);
        }

        return response([
            'message' => 'Detail Data Movie!',
            'data' => $movie
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'poster' => 'mimes:jpeg,png,jpg|max:2048',
            'title' => 'required|min:3',
            'summary' => 'required',
            'genre_id' => 'required|exists:genres,id',
            'year' => 'required'
        ], [
            'required' => 'input :attribute harus diisi!.',
            'max' => 'input :atribut minimal :max bite',
            'mimes' => 'inputan :atribut harus berformat jpeg,png,jpg',
            'poster' => 'inputan :atribut harus gambar',
            'exists' => 'input :attribute tidak ditemukan di table genres!',
        ]);

        $movie = Movie::find($id);

        if ($request->hasFile('poster')) {
            $uploadedFileUrl = cloudinary()->upload($request->file('poster')->getRealPath(), [
                'folder' => 'images',
            ])->getSecurePath();
            $movie->poster =  $uploadedFileUrl;
        }

        if (!$movie) {
            return response([
                'message' => 'movie tidak ditemukan'
            ], 404);
        }


        $movie->title = $request->input('title');
        $movie->summary = $request->input('summary');
        $movie->genre_id = $request->input('genre_id');
        $movie->year = $request->input('year');

        $movie->save();

        return response([
            'message' => 'Update Movie berhasil',
            'data' => $movie
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $movie = Movie::find($id);

        if (!$movie) {
            return response([
                'message' => 'Data Movies tidak ditemukan!',
            ], 404);
        }
        $movie->delete();
        return response([
            'message' => 'berhasil Menghapus movie!',
        ], 201);
    }
}
