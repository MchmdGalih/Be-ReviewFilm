<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Casts;

class CastController extends Controller
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
        $casts = Casts::get();

        return response([
            'message' => 'tampil data berhasil!',
            'data' => $casts,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'age' => 'required',
            'bio' => 'required',
        ], [
            'required' => 'input harus diisi!.',
            'min' => 'input minimal 3 karakter.'
        ]);

        $cast = Casts::create([
            'name' => $request->input('name'),
            'bio' => $request->input('bio'),
            'age' => $request->input('age'),
        ]);

        return response([
            'message' => 'Tambah cast berhasil!',
            'data' => $cast
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cast = Casts::with('list_movie')->find($id);

        if (!$cast) {
            return response([
                'message' => "cast dengan $id tidak ditemukan",
            ], 404);
        };

        return response([
            'message' => "Detail Data Cast",
            'data' => $cast,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|min:3',
            'age' => 'required',
            'bio' => 'required',
        ], [
            'required' => 'input harus diisi!.',
            'min' => 'input minimal 3 karakter.'
        ]);

        $cast = Casts::find($id);

        if (!$cast) {
            return response([
                'message' => "cast $id tidak ditemukan!",
            ], 404);
        };

        $cast->name = $request->input('name');
        $cast->age = $request->input('age');
        $cast->bio = $request->input('bio');

        $cast->save();
        return response([
            'message' => "Update Cast berhasil!",
            'data' => $cast,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cast = Casts::find($id);

        if (!$cast) {
            return response([
                'message' => "cast dengan $id tidak ditemukan!",
            ], 404);
        }
        $cast->delete();
        return response([
            'message' => "Berhasil Menghapus Cast!",

        ], 201);
    }
}
