<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function delete(Request $req){

        Message::where('id', '=', $req->id)->delete();
        
    }

    public function warning(Request $req){

        Message::create(['approved_materials_id' => 0, 'users_id' => $req->id, 'user_send_id' => 0, 'text' => 'Вы нарушили правила сайта! Ещё одно нарушение, и ваша учётная запись будет удалена!']);
        
    }
}
