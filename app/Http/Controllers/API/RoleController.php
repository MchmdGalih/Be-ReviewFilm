<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Roles;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Roles::all();

        return response()->json([
            'message' => 'tampil data berhasil',
            'data' => $roles
        ], 201);
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

        $role = new Roles;
        $role->name = $request->input('name');
        $role->save();


        return response()->json([
            'message' => 'Tambah Role berhasil',
            'data' => $role,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Roles::find($id);

        if (!$role) {
            return response()->json([
                'message' => 'Role tidak ditemukan!',
            ], 404);
        }

        return response()->json([
            'message' => 'Detail Data Role',
            'data' => $role,
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

        $role = Roles::find($id);

        if (!$role) {
            return response()->json([
                'message' => 'Role tidak ditemukan!',
            ], 404);
        }

        $role->name = $request->input('name');
        $role->save();

        return response()->json([
            'message' => 'Update Role berhasil',
            'data' => $role,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Roles::find($id);

        if (!$role) {
            return response()->json([
                'message' => 'Role tidak ditemukan!',
            ], 404);
        }
        $role->delete();
        return response()->json([
            'message' => 'berhasil Menghapus Role',
        ], 200);
    }
}