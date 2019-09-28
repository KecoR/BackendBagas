<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Museum;

class MuseumController extends Controller
{
    public function index()
    {
        $museum = Museum::all();

        return view('museums.index', ['museums' => $museum]);
    }

    public function add(Request $request)
    {
        $museum = new Museum;
        $museum->museum_name = $request->get('museum_name');
        $museum->museum_desc = $request->get('museum_desc');

        $image = $request->file('museum_image');
        $allowed_extension = ['png','jpg','jpeg','bmp'];
        if(!empty($image) && in_array(strtolower($image->getClientOriginalExtension()),$allowed_extension)){
            $file_name = \Str::slug($request->get('museum_name'),'-').'.'.$image->getClientOriginalExtension();
            $museum->museum_image = $file_name;
            $image->move(public_path().'/image/museums/',$file_name); 
        }

        // dd($museum);

        $museum->save();

        return redirect()->route('museums')->with('status', 'Berhasil menambahkan museum');
    }

    public function getData($id)
    {
        $museum = Museum::find($id);

        return $museum;
    }

    public function edit(Request $request)
    {
        $museum = Museum::find($request->id);

        $museum->museum_name = $request->get('museum_name');
        $museum->museum_desc = $request->get('museum_desc');

        if ($request->file('image')) {
            if ($museum->museum_image && file_exists('image/museums/'.$museum->museum_image)) {
                unlink('image/museums/'.$museum->museum_image);

                $image = $request->file('image');
                $allowed_extension = ['png','jpg','jpeg','bmp'];
                if(!empty($image) && in_array(strtolower($image->getClientOriginalExtension()),$allowed_extension)){
                    $file_name = \Str::slug($request->get('museum_name'),'-').'.'.$image->getClientOriginalExtension();
                    $museum->museum_image = $file_name;
                    $image->move(public_path().'/image/museums/',$file_name); 
                }
            } else {
                $image = $request->file('image');
                $allowed_extension = ['png','jpg','jpeg','bmp'];
                if(!empty($image) && in_array(strtolower($image->getClientOriginalExtension()),$allowed_extension)){
                    $file_name = \Str::slug($request->get('full_name'),'-').'.'.$image->getClientOriginalExtension();
                    $museum->museum_image = $file_name;
                    $image->move(public_path().'/image/museums/',$file_name); 
                }
            }
        }

        $museum->save();

        return redirect()->route('museums')->with('status', 'Berhasil mengubah museum');
    }

    public function delete($id)
    {
        $museum = Museum::find($id);

        if($museum->museum_image && file_exists('image/museums/'.$museum->museum_image)){
            unlink('image/museums/'.$museum->museum_image);
        }

        $museum->delete();

        return redirect()->route('museums')->with('status', 'Museum berhasil dihapus.');
    }
}
