<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Topup;
use App\Museum;
use App\Order;

use Hash;

class MobileController extends Controller
{
    //Modul User
    public function doLogin(Request $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');

        $cekUser = User::where('email',  $email)->first();
        if (!empty($cekUser)) {
            if (Hash::check($password, $cekUser->password, [])) {
                $cekUser->makeHidden(['passowrd']);
    
                return response()->json(['statusCode' => 1, 'data' => $cekUser]);
            } else {
                return response()->json(['statusCode' => 0, 'data' => 'Password Salah']);
            }
        } else {
            return response()->json(['statusCode' => 0, 'data' => 'Email Tidak Ditemukan']);
        }
    }

    public function doRegist(Request $request)
    {
        $email = $request->get('email');

        $cekUser = User::where('email', $email)->first();
        
        if (empty($cekUser)) {
            $user = new User;
            $user->full_name = $request->get('full_name');
            $user->email = $request->get('email');
            $user->password = Hash::make($request->get('password'));
            $user->no_hp = $request->get('no_hp');
            $user->alamat = $request->get('alamat');
            $user->date_birth = $request->get('date_birth');
            $user->role_id = $request->get('role');
            $user->save();

            return response()->json(['statusCode' => 1, 'data' => 'OK']);
        } else {
            return response()->json(['statusCode' => 0, 'data' => 'Email Telah Digunakan']);
        }
    }

    public function viewProfile($id)
    {
        $dataUser = User::find($id);

        if (!empty($dataUser)) {
            return response()->json(['statusCode' => 1, 'data' => $dataUser]);
        } else {
            return response()->json(['statusCode' => 0, 'data' => 'Data Tidak Ditemukan']);
        }
    }

    public function editProfile(Request $request, $id)
    {
        $user = User::find($id);

        $email = $request->get('email');
        $email_old = $request->get('email_old');
        $image = $request->get('image');

        if (!empty($user)) {
            $cekEmail = User::where('email', $email)->first();

            if (empty($cekEmail) || $cekEmail->email == $email_old) {
                $user->full_name = $request->get('full_name');
                $user->email = $request->get('email');
                $user->password = Hash::make($request->get('password'));
                $user->no_hp = $request->get('no_hp');
                $user->alamat = $request->get('alamat');
                $user->date_birth = $request->get('date_birth');
                
                if (!empty($image)) {
                    $imageUpload = base64_decode($image);
                    $imageName = \Str::slug($request->get('full_name'),'-').'.'.$avatar->getClientOriginalExtension();

                    file_put_contents('image/users/'.$imageName, $imageUpload);

                    $user->image = $imageName;
                }

                $user->save();

                return response()->json(['statusCode' => 1, 'data' => 'OK']);
            } else {
                return response()->json(['statusCode' => 0, 'data' => 'Email Telah Digunakan']);
            }
        } else {
            return response()->json(['statusCode' => 0, 'data' => 'User Tidak Ditemukan']);
        }
    }

    //Modul Pelanggan
    public function getMuseumData()
    {
        $museum = Museum::orderBy('museum_rating', 'DESC')->get();

        if (!empty($museum)) {
            return response()->json(['statusCode' => 1, 'data' => $museum]);
        } else {
            return response()->json(['statusCode' => 0, 'data' => 'Tidak Ada Museum']);
        }
    }

    public function getDetailMuseum($id)
    {
        $museum = Museum::find($id);

        if (!empty($museum)) {
            return response()->json(['statusCode' => 1, 'data' => $museum]);
        } else {
            return response()->json(['statusCode' => 0, 'data' => 'Museum Tidak Ditemukan']);
        }
    }

    public function orderPemandu(Request $request, $id)
    {
        $pemandu = $this->getPemandu();
        $museum = $request->get('museum_id');

        if (!empty($pemandu)) {
            $order = new Order;
            $order->pelanggan_id = $id;
            $order->museum_id = $museum;
            $order->pemandu_id = $pemandu->id;
            $order->save();

            return response()->json(['statusCode' => 1, 'data' => 'OK']);
        } else {
            return response()->json(['statusCode' => 0, 'data' => 'Tidak Menemukan Pemandu']);
        }
    }

    public function giveMuseumRating(Request $request, $id)
    {
        $order = Order::find($id);

        if (!empty($order)) {
            $order->rating = $request->get('rating');
            $order->status = '1';
            $order->save();

            $this->collaborativeFilter($order->museum_id);

            return response()->json(['statusCode' => 1, 'data' => 'Berhasil Memberikan Ratin']);
        } else {
            return response()->json(['statusCode' => 0, 'data' => 'Data Tidak Ditemukan']);
        }
    }

    public function topupSaldo(Request $request, $id)
    {
        $user = User::find($id);

        $saldo = $request->get('topup');

        if (!empty($user)) {
            $topup = new Topup;
            $topup->user_id = $id;
            $topup->topup = intval($saldo) + (rand(1, 899) + 100);
            $topup->status = '0';
            $topup->save();

            return response()->json(['statusCode' => 1, 'data' => 'OK']);
        } else {
            return response()->json(['statusCode' => 0, 'data' => 'User Tidak Ditemukan']);
        }
    }

    public function historyOrder($id)
    {
        // $history = Order::where('pelanggan_id', $id)->get();
        $history = Order::where('pelanggan_id', $id)->with('pemandu', 'museum')->get();

        if (count($history) > 0) {
            return response()->json(['statusCode' => 1, 'data' => $history]);
        } else {
            return response()->json(['statusCode' => 0, 'data' => 'Data Tidak Ditemukan']);
        }
    }

    protected function collaborativeFilter($museum_id)
    {
        $getOrderData = Order::where('museum_id', $museum_id)->orderBy('rating', 'DESC')->get()->unique('pelanggan_id');

        $dataUser = count($getOrderData);

        foreach ($getOrderData as $dataOrder) {
            $rating[] = ((1 * $dataOrder->rating * 100) / 5 * 1);
        }

        $totRating = array_sum($rating) / $dataUser;

        $museum = Museum::find($museum_id);
        $museum->museum_rating = $totRating;
        $museum->save();
    }

    protected function getPemandu()
    {
        $pemandu = User::where(['role_id' => 2, 'status' => 1])->inRandomOrder()->first();

        return $pemandu;
    }

    //Modul Pemandu
    public function getDataOrder()
    {
        
    }
}
