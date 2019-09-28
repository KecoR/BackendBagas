<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Role;

class UserController extends Controller
{
    public function index()
    {
        $user = User::with('role')->get();
        $role = Role::all();

        return view('users.index', ['users' => $user, 'roles' => $role]);
    }

    public function add(Request $request)
    {
        $email = $request->get('email');
        
        $cekUser = User::where('email', $email)->first();

        if (!empty($cekUser)) {
            return redirect()->route('users')->with('failed', 'Email telah digunakan');
        } else {
            $user = new User;
            $user->full_name = $request->get('full_name');
            $user->email = $email;
            $user->password = \Hash::make($request->get('password'));
            $user->no_hp = $request->get('no_hp');
            $user->alamat = $request->get('alamat');
            $user->date_birth = $request->get('date_birth');
            $user->role_id = $request->get('role_id');

            $avatar = $request->file('image');
            $allowed_extension = ['png','jpg','jpeg','bmp'];
            if(!empty($avatar) && in_array(strtolower($avatar->getClientOriginalExtension()),$allowed_extension)){
                $file_name = \Str::slug($request->get('full_name'),'-').'.'.$avatar->getClientOriginalExtension();
                $user->image = $file_name;
                $avatar->move(public_path().'/image/users/',$file_name); 
            }

            $user->save();

            return redirect()->route('users')->with('status', 'Berhasil menambahkan user');
        }

    }

    public function getData($id)
    {
        $user = User::find($id);

        return $user;
    }

    public function edit(Request $request)
    {
        $id = $request->get('id_old');
        $password = $request->get('password_old');

        $cekEmail = User::where('email', $request->email)->first();

        if (empty($cekEmail) || $cekEmail->email == $request->email_old) {
            $user = User::find($id);
            $user->full_name = $request->get('full_name');
            $user->email = $request->get('email');
            $user->no_hp = $request->get('no_hp');
            $user->alamat = $request->get('alamat');
            $user->date_birth = $request->get('date_birth');
            $user->role_id = $request->get('role_id');

            if ($request->has('new_password')) {
                if ($request->get('new_password')) {
                    $user->password = \Hash::make($request->new_password);
                }
            } else {
                $user->password = $request->get('password_old');
            }

            if ($request->file('image')) {
                if ($user->image && file_exists('image/users/'.$user->image)) {
                    unlink('image/users/'.$user->image);

                    $avatar = $request->file('image');
                    $allowed_extension = ['png','jpg','jpeg','bmp'];
                    if(!empty($avatar) && in_array(strtolower($avatar->getClientOriginalExtension()),$allowed_extension)){
                        $file_name = \Str::slug($request->get('full_name'),'-').'.'.$avatar->getClientOriginalExtension();
                        $user->image = $file_name;
                        $avatar->move(public_path().'/image/users/',$file_name); 
                    }
                } else {
                    $avatar = $request->file('image');
                    $allowed_extension = ['png','jpg','jpeg','bmp'];
                    if(!empty($avatar) && in_array(strtolower($avatar->getClientOriginalExtension()),$allowed_extension)){
                        $file_name = \Str::slug($request->get('full_name'),'-').'.'.$avatar->getClientOriginalExtension();
                        $user->image = $file_name;
                        $avatar->move(public_path().'/image/users/',$file_name); 
                    }
                }
            }

            $user->save();

            return redirect()->route('users')->with('status', 'Berhasil mengedit user');

        } else {
            return redirect()->route('users')->with('failed', 'Email telah digunakan');
        }
    }

    public function delete($id)
    {
        $user = User::find($id);

        if($user->image && file_exists('image/users/'.$user->image)){
            unlink('image/users/'.$user->image);
        }

        $user->delete();

        return redirect()->route('users')->with('status', 'User berhasil dihapus.');
    }
}
