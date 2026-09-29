<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('profil.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email,' . $user->id,
            'foto_profil' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto_profil')) {
            $path = $request->file('foto_profil')->store('profil', 'public');

            $data['foto_profil'] = $path;
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function gantiAkun(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }
}