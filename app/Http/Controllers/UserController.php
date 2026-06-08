<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return Inertia::render('Users/Index', [
            'users' => User::with('roles')->paginate(),
            'roles' => Role::all()
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
            'rol' => 'required', // Validamos que se envíe el nombre o ID del rol
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'password' => Hash::make($request->password),
            'estado' => $request->estado ?? 'activo',
        ]);

        $user->assignRole($request->rol);

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
            'rol' => 'required',
            'password' => 'nullable|string|min:8',
        ]);

        $user->update([
            'username' => $request->username,
            'email' => $request->email,
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'password' => Hash::make($request->password),
            'estado' => $request->estado ?? 'activo',
            
        ]);

        $user->syncRoles($request->rol);

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
            'estado' => 'required|in:activo,inactivo',
        ]);

        $user->update(['estado' => $request->estado]);

        return response()->json(['success' => true]);
    }
}
