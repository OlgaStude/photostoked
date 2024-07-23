<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\approvalRequest;
use App\Http\Requests\materialsRequest;
use App\Http\Resources\materialsResource;
use App\Models\approved_material;
use App\Models\like;
use App\Models\material_for_approval;
use App\Models\Message;
use App\Models\tag;
use App\Models\tag_material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class materialsController extends Controller
{

    // Работа с ещё не выставленными на сайт работами

    // Отправка работы на проверку

    public function send_for_approval(materialsRequest $req){
        
        $req->file('material')->store('public/sent_materials');
        $material_name = $req->file('material')->hashName();
        $tags = str_replace(', ', ',', $req->tags);

        $material = material_for_approval::create([
            'path' => $material_name, 
            'users_id' => Auth::user()->id, 
            'tags' => $tags
        ]);

        if ($material) {
            return response()->json(['status' => 200, 'message' => 'Ваш материал был успешно отправлен на проверку']);
        }
        return response()->json(['status' => 422, 'message' => 'Что-то пошло не так']);

    }


    public function show_in_admin(){

        $materials = material_for_approval::all();

        return materialsResource::collection($materials);

    }

    public function delete(Request $req)
    {

        Storage::delete("public/sent_materials/" . material_for_approval::find($req->id)->path);
        material_for_approval::find($req->id)->delete();

    }


    // Принятие работы

    public function approve(approvalRequest $req)
    {

        $req->file('material')->store('public/approved_materials');
        $material_name = $req->file('material')->hashName();
        $original_name = material_for_approval::find($req->material_id)->path;

        $new_material = approved_material::create(
            array_merge($req->validated(), [
                'path' => $material_name, 
                'original_path' => $original_name, 
                'likes' => 0
            ]));

        $tags = explode(',', $req->tags);

        // Проверка, если указанные метки уже существуют,
        // если нет - создание и привязка их к работе,
        // если да, привязываются существующую

        foreach ($tags as $tag) {
            $exists = tag::where('tag_name', $tag)->exists();
            if (!$exists) {
                tag::create(['tag_name' => $tag]);
            }
            $tag_id = tag::where('tag_name', $tag)->get();
            tag_material::create([
                'approved_materials_id' => $new_material->id, 
                'tags_id' => $tag_id[0]->id
            ]);
        }

        Storage::move("public/sent_materials/" . $original_name, "public/original_imgs/" . $original_name);
        material_for_approval::find($req->material_id)->delete();

        Message::create([
            'approved_materials_id' => $new_material->id, 
            'users_id' => $new_material->users_id, 
            'user_send_id' => 0, 
            'text' => 'Ваша работа была одобрена!'
        ]);


    }


    // Работа с одобренными материалами

    // Вывод принатых работ на главной

    public function show_in_main()
    {

        $materials = approved_material::orderBy('id', 'desc')->paginate(6);

        return materialsResource::collection($materials);

    }


    // Вывод популярных работ на главной

    public function show_popular_in_main()
    {

        $materials = approved_material::orderBy('likes', 'desc')->paginate(5);

        return materialsResource::collection($materials);
    }


    // Вывод работ одного автора на его странице
   
    public function show_users($id)
    {

        $materials = approved_material::where('users_id', '=', $id)->orderBy('id', 'desc')->get();

        return materialsResource::collection($materials);
    }


    // Вывод понравившихся пользователю работ

    public function show_liked($id)
    {

        $materials = Like::join('users', 'users.id', '=', 'likes.users_id')
        ->join('approved_materials', 'approved_materials.id', '=', 'likes.approved_materials_id')
        ->where('users.id', '=', Auth::user()->id)
        ->select('approved_materials.id', 'approved_materials.path', 'approved_materials.type', 'approved_materials.likes', 'approved_materials.users_id')
        ->orderBy('approved_materials.id', 'desc')->get();

        return materialsResource::collection($materials);

    }


    // Добавить/убрать работу из понравившихся пользователю

    public function like(Request $req)
    {
        $exists = like::where([
            ['users_id', '=', Auth::user()->id],
            ['approved_materials_id', '=', $req->id],
        ])->exists();

        if (!$exists) {
            approved_material::where("id", $req->id)->increment("likes");
            like::create([
                'users_id' => Auth::user()->id, 
                'approved_materials_id' => $req->id
            ]);

            if(Auth::user()->id != $req->user_id){
                Message::create([
                    'approved_materials_id' => $req->id, 
                    'users_id' => $req->user_id, 
                    'user_send_id' => Auth::user()->id, 
                    'text' => 'Понравилась ваша работа!'
                ]);
            }
        } else {
            approved_material::where("id", $req->id)->decrement("likes");
            like::where([
                ['users_id', '=', Auth::user()->id],
                ['approved_materials_id', '=', $req->id],
            ])->delete();

            $exists = Message::where([
                ['approved_materials_id', '=', $req->id],
                ['user_send_id', "=", Auth::user()->id]
            ])->exists();
            
            if($exists){
                Message::where([
                    ['approved_materials_id', '=', $req->id],
                    ['user_send_id', "=", Auth::user()->id]
                ])->delete();
            }
        }
    }
}
