<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\collectionRequest;
use App\Models\approved_material;
use App\Models\Collection;
use App\Models\Collection_items;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
{
    
    public function make(collectionRequest $req)
    {
        Collection::create(['users_id' => Auth::user()->id, 'name' => $req->name, 'path' => '']);
    }

    public function delete(Request $req)
    {
        Collection_items::where('collections_id', '=', $req->id)->delete();
        Collection::find($req->id)->delete();
    }

    public function status_change(Request $req)
    {
        $exists = Collection_items::where([
            ['collections_id', '=', $req->collections_id],
            ['approved_materials_id', '=', $req->approved_ms_id]
        ])->exists();
        if (!$exists) {

            Collection_items::create(['collections_id' => $req->collections_id, 'approved_materials_id' => $req->approved_ms_id]);
            $path = approved_material::where('id', '=', $req->approved_ms_id)->get();
            $collection = Collection::where("id", '=', $req->collections_id)->get();
            if ($collection[0]->path == '') {
                    Collection::where("id", $req->collections_id)->update(["path" => $path[0]->path]);
            }
            return 'added';

        } else {

            $path = approved_material::where('id', '=', $req->approved_ms_id)->get();
            Collection_items::where([
                ['collections_id', '=', $req->collections_id],
                ['approved_materials_id', '=', $req->approved_ms_id]
            ])->delete();

            $collection = Collection::where("id", '=', $req->collections_id)->get();
            $counter = Collection_items::where('collections_id', '=', $collection[0]->id)->get();
            if ($collection[0]->path == $path[0]->path && count($counter) >= 1) {
                $new = Collection_items::where('collections_id', '=', $req->collections_id)->get();
                $new_item = approved_material::where('id', '=', $new[0]->approved_materials_id)->get();
                Collection::where("id", $req->collections_id)->update(["path" => $new_item[0]->path]);
            } else{
                Collection::where("id", $req->collections_id)->update(["path" => '']);
            }

            return 'removed';
        }
    
        
        
    }

}
