<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Http\Requests\priceChangeRequest;
use App\Models\Bought;
use App\Models\Message;
use App\Models\Pockets;
use App\Models\Price;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function buypackege(PaymentRequest $req)
    {

        if($req->ammount == 'take'){
            User::where("id", '=', Auth::user()->id)->update(['money' => 0]);
            return;
        }

        $exists = Pockets::where('users_id', '=', Auth::user()->id)->exists();

        if(!$exists){
            Pockets::create([
                'users_id' => Auth::user()->id, 
                'purchases_left' => $req->ammount
            ]);
        } else {
            $packege = Pockets::where('users_id', '=', Auth::user()->id)->get();
            $ammount = $packege[0]->purchases_left + $req->ammount;

            Pockets::where('users_id', '=', Auth::user()->id)->update(['purchases_left' => $ammount]);
        }
    }


    // Покупка работы

    public function buy(Request $req)
    {
        if($req->material_id != 0 && $req->user_id != 0){

            Pockets::where('users_id', '=', Auth::user()->id)->decrement("purchases_left");
            $pakege = Pockets::where('users_id', '=', Auth::user()->id)->get();

            if ($pakege[0]->purchases_left == 0) {
                Pockets::where('users_id', '=', Auth::user()->id)->delete();
            }

            Bought::create([
                'users_id' => Auth::user()->id, 
                'approved_materials_id' => $req->material_id
            ]);

            $user = User::where("id", '=', $req->user_id)->get();
            $money = $user[0]->money + 100;

            User::where("id", $req->user_id)->update(["money" => $money]);
            Message::create([
                'users_id' => $req->user_id, 
                'user_send_id' => Auth::user()->id, 
                'approved_materials_id' => $req->material_id, 
                'text' => 'скачал вашу работу!'
            ]);
        }

    }


    public function price_change(priceChangeRequest $req){

        Price::where('id', $req->id)->update(['price' => $req->new_val]);

    }

}
