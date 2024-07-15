<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\editRequest;
use App\Http\Requests\loginRequest;
use App\Http\Requests\registrationRequest;
use App\Models\approved_material;
use App\Models\Bought;
use App\Models\Collection;
use App\Models\Collection_items;
use App\Models\like;
use App\Models\material_for_approval;
use App\Models\Message;
use App\Models\Pockets;
use App\Models\Report;
use App\Models\Subscription;
use App\Models\tag;
use App\Models\tag_material;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class userController extends Controller
{
    public function register(registrationRequest $req)
    {


        $req->file('pfp')->store('public/profile_pics');
        $pfp_name = $req->file('pfp')->hashName();
        $user = User::create(array_merge($req->validated(), ['password' => Hash::make($req->password), 'path' => $pfp_name, 'followers' => 0, 'money' => 0]));

        if ($user) {
            Auth::login($user);
            Cookie::make('user', json_encode(auth()->user()), 60);
            return response()->json(['status' => 200, 'message' => 'user is registreted!', 'user_id' => auth()->user()->id, 'is_admin' => auth()->user()->is_admin]);
        }

        return response()->json(['status' => 422, 'message' => 'user is failed to be registreted!']);
    }

    public function login(loginRequest $req)
    {
        $formFields = $req->only(['email', 'password']);


        if (Auth::attempt($formFields)) {
            Cookie::make('user', json_encode(auth()->user()), 60);
            // $user = json_decode(Cookie::get('user'));
            return response()->json(['status' => 200, 'message' => 'user is logged in!', 'user_id' => auth()->user()->id, 'is_admin' => auth()->user()->is_admin]);
        }
        return response()->json(['status' => 422, 'message' => 'Неверная почта или пароль']);
    }


    public function logout()
    {

        try {
            Auth::logout();
            Cookie::forget('user');
            return response()->json(['status' => 200, 'message' => 'user is logged out!']);
        } catch (QueryException $e) {
            return response()->json(['status' => 422, 'message' => $e]);
        }
    }


    public function get_user($id)
    {
        $exists = User::where('id', '=', $id)->exists();

        if($exists){
            return User::find($id);
        }
        return 'no_user_found';

    }


    public function updateData(editRequest $req){
        
        if($req->nikname){
            User::where("id", Auth::user()->id)->update(["nikname" => $req->nikname]);
        }
        if($req->email){
            User::where("id", Auth::user()->id)->update(["email" => $req->email]);
        }
        if($req->hasFile('pfp')){
            $req->file('pfp')->store('public/profile_pics');
            $material_name = $req->file('pfp')->hashName();

            Storage::delete("public/profile_pics/".User::find(Auth::user()->id)->path);


            User::where("id", Auth::user()->id)->update(["path" => $material_name]);

        }
        if($req->birthdate){
            User::where("id", Auth::user()->id)->update(["birthdate" => $req->birthdate]);
        }
        if($req->add_info){
            User::where("id", Auth::user()->id)->update(["add_info" => $req->add_info]);
        }else{
            User::where("id", Auth::user()->id)->update(["add_info" => NUll]);
        }
    }


    public function follow(Request $req){
        $exists = Subscription::where([
            ['users_id', '=', Auth::user()->id],
            ['followed_id', '=', $req->followed_id],
        ])->exists();
        if(!$exists){
            Subscription::create(['users_id' => Auth::user()->id, 'followed_id' => $req->followed_id]);
            User::where("id", $req->followed_id)->increment("followers");
            Message::create(['users_id' => $req->followed_id, 'user_send_id' => Auth::user()->id, 'approved_materials_id' => 0, 'text' => 'добавил вас в избранное']);
        } else{
            User::where("id", $req->followed_id)->decrement("followers");
            Subscription::where([
                ['users_id', '=', Auth::user()->id],
                ['followed_id', '=', $req->followed_id],
            ])->delete();
            Message::where([['users_id', '=', $req->followed_id], ['user_send_id', '=', Auth::user()->id], ['approved_materials_id', '=', '0']])->delete();

        }
    }

    public function delete(Request $req){
        Bought::where('users_id', '=', $req->id)->delete();
        $likes = like::where('users_id', '=', $req->id)->get();

        foreach($likes as $like){
            approved_material::where('id', '=', $like->approved_materials_id)->decrement("likes");
        }
        like::where('users_id', '=', $req->id)->delete();


        material_for_approval::where('users_id', '=', $req->id)->delete();
        Message::where('users_id', '=', $req->id)->delete();
        Message::where('user_send_id', '=', $req->id)->delete();
        Pockets::where('users_id', '=', $req->id)->delete();
        Subscription::where('users_id', '=', $req->id)->delete();
        Subscription::where('followed_id', '=', $req->id)->delete();

        $collections = Collection::where('users_id', '=', $req->id)->get();

        foreach($collections as $collection){
            Collection_items::where('collections_id', '=', $collection->id)->delete();
        }
        Collection::where('users_id', '=', $req->id)->delete();

        $materials = approved_material::where('users_id', '=', $req->id)->get();
        foreach($materials as $material){
            Bought::where('approved_materials_id', '=', $material->id)->delete();
            like::where('approved_materials_id', '=', $material->id)->delete();

            $tag_check = tag_material::where('approved_materials_id', '=', $material->id)->get();
            tag_material::where('approved_materials_id', '=', $material->id)->delete();

            foreach($tag_check as $tag){
                $exists = tag_material::where('tags_id', '=', $tag->tags_id)->exists();
                if(!$exists){
                    tag::where('id', '=', $tag->tags_id)->delete();
                }
            }
            
        }
        approved_material::where('users_id', '=', $req->id)->delete();

        User::where('id', '=', $req->id)->delete();
    }


    public function find_user(Request $req){


        if($req->search_word == ''){
            $authors = User::get();

            return $authors;
        }

        $authors = User::where('nikname', 'LIKE', '%' . $req->search_word . '%')->get();

        return $authors;

    }

    public function report_user(Request $req){

        $exists = Report::where([['users_id', '=', $req->id], ['user_send_id', '=', Auth::user()->id]])->exists();

        if(!$exists){
            Report::create(['users_id' => $req->id, 'user_send_id' => Auth::user()->id]);
        }


    }

    public function delete_report(Request $req){

        Report::where('id', '=', $req->id)->delete();

    }


}
