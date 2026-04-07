<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Roles;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {

        return Inertia::render('Users/Index', [
            'users' => User::with('rol')->paginate(),
            'roles' => Roles::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'telefono' => 'required|Integer',
            'direccion' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'id_rol' => 'required|exists:roles,id',
        ]);

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'password' => Hash::make($request->password),
            'id_rol' => $request->id_rol,
            'activo' => $request->activo ?? true,
        ]);

        return redirect()->route('users.index');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'telefono' => 'nullable|Integer',
            'direccion' => 'nullable|string|max:255',
            'id_rol' => 'required|exists:roles,id',
        ]);

        $user->update([
            'username' => $request->username,
            'email' => $request->email,
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'id_rol' => $request->id_rol,
            'activo' => $request->activo ?? true,
        ]);

        return redirect()->route('users.index');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index');
    }

    public function toggle(Request $request, User $user)
    {
        $request->validate([
            'activo' => 'required|boolean',
        ]);

        $user->update(['activo' => $request->activo]);

        return response()->json(['success' => true]);
    }
}
