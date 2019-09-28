<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Role;

class UserRoleController extends Controller
{
    public function index()
    {
        $role = Role::all();

        return view('roles.index', ['roles' => $role]);
    }

    public function add(Request $request)
    {
        $role = new Role;
        $role->role_desc = $request->get('role_desc');
        $role->save();

        return redirect()->route('roles')->with('status', 'Berhasil menambahkan role');
    }

    public function getData($id)
    {
        $role = Role::find($id);

        return $role;
    }

    public function edit(Request $request)
    {
        $role = Role::find($request->id);
        $role->role_desc = $request->get('role_desc');
        $role->save();

        return redirect()->route('roles')->with('status', 'Berhasil mengubah role');
    }

    public function delete($id)
    {
        $role = Role::find($id);
        $role->delete();

        return redirect()->route('roles')->with('status', 'Berhasil menghapus role');
    }
}
