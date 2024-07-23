<?php

namespace App\Http\Controllers;

use App\Http\Resources\materialsResource;
use App\Models\approved_material;
use App\Models\tag;
use App\Models\tag_material;
use Illuminate\Http\Request;

class filtrationController extends Controller
{

    public function wordSearch(Request $req)
    {

        if (count(tag::where('tag_name', 'LIKE', '%' . $req->search_word . '%')->get()) > 0) {
            $materials = tag_material::join('tags', 'tags.id', '=', 'tag_materials.tags_id')
                ->join('approved_materials', 'approved_materials.id', '=', 'tag_materials.approved_materials_id')
                ->where('tags.tag_name', 'LIKE', '%' . $req->search_word . '%')
                ->select(
                    'approved_materials.id',
                    'approved_materials.path as path',
                    'approved_materials.type',
                    'approved_materials.likes',
                    'approved_materials.format',
                    'approved_materials.users_id as users_id',
                    'tags.tag_name'
                )
                ->orderBy('approved_materials.id', 'desc')->paginate(6);

            $cur_id = 0;
            foreach ($materials as $row => $material) {
                if ($material->id == $cur_id) {
                    unset($materials[$row]);
                }
                $cur_id = $material->id;
            }

            return materialsResource::collection($materials);
        }
    }

    // Фильтр с учётом всех позакателей

    public function filter(Request $req)
    {

        if ($req->type != '' && $req->format != '') {
            if ($req->search_word != '') {
                $materials = tag_material::join('tags', 'tags.id', '=', 'tag_materials.tags_id')
                ->join('approved_materials', 'approved_materials.id', '=', 'tag_materials.approved_materials_id')
                ->select(
                    'approved_materials.id',
                    'approved_materials.path as path',
                    'approved_materials.type',
                    'approved_materials.likes',
                    'approved_materials.format',
                    'approved_materials.users_id as users_id',
                    'tags.tag_name'
                )
                ->where([
                    ['type', '=', $req->type],
                    ['format', '=', $req->format],
                    ['tags.tag_name', 'LIKE', '%' . $req->search_word . '%']
                ])->orderBy('id', 'desc')->paginate(6);
            } elseif ($req->tag != '') {
                $materials = tag_material::join('tags', 'tags.id', '=', 'tag_materials.tags_id')
                ->join('approved_materials', 'approved_materials.id', '=', 'tag_materials.approved_materials_id')
                ->select(
                    'approved_materials.id',
                    'approved_materials.path as path',
                    'approved_materials.type',
                    'approved_materials.likes',
                    'approved_materials.format',
                    'approved_materials.users_id as users_id',
                    'tags.tag_name'
                )
                ->where([
                    ['type', '=', $req->type],
                    ['format', '=', $req->format],
                    ['tags.tag_name', '=', $req->tag]
                ])->orderBy('id', 'desc')->paginate(6);
            } else {
                $materials = approved_material::where([
                    ['type', '=', $req->type],
                    ['format', '=', $req->format],
                ])->orderBy('id', 'desc')->paginate(6);
            }
        } elseif ($req->type != '') {
            if ($req->search_word != '') {
                $materials = tag_material::join('tags', 'tags.id', '=', 'tag_materials.tags_id')
                ->join('approved_materials', 'approved_materials.id', '=', 'tag_materials.approved_materials_id')
                ->select(
                    'approved_materials.id',
                    'approved_materials.path as path',
                    'approved_materials.type',
                    'approved_materials.likes',
                    'approved_materials.format',
                    'approved_materials.users_id as users_id',
                    'tags.tag_name'
                )
                ->where([
                    ['type', '=', $req->type],
                    ['tags.tag_name', 'LIKE', '%' . $req->search_word . '%']
                ])->orderBy('id', 'desc')->paginate(6);
            } elseif ($req->tag != '') {
                $materials = tag_material::join('tags', 'tags.id', '=', 'tag_materials.tags_id')
                ->join('approved_materials', 'approved_materials.id', '=', 'tag_materials.approved_materials_id')
                ->select(
                    'approved_materials.id',
                    'approved_materials.path as path',
                    'approved_materials.type',
                    'approved_materials.likes',
                    'approved_materials.format',
                    'approved_materials.users_id as users_id',
                    'tags.tag_name'
                )
                ->where([
                    ['type', '=', $req->type],
                    ['tags.tag_name', '=', $req->tag]
                ])->orderBy('id', 'desc')->paginate(6);
            } else {
                $materials = approved_material::where('type', '=', $req->type)->orderBy('id', 'desc')->paginate(6);
            }
        } elseif ($req->format != '') {
            if ($req->search_word != '') {
                $materials = tag_material::join('tags', 'tags.id', '=', 'tag_materials.tags_id')
                ->join('approved_materials', 'approved_materials.id', '=', 'tag_materials.approved_materials_id')
                ->select(
                    'approved_materials.id',
                    'approved_materials.path as path',
                    'approved_materials.type',
                    'approved_materials.likes',
                    'approved_materials.format',
                    'approved_materials.users_id as users_id',
                    'tags.tag_name'
                )
                ->where([
                    ['format', '=', $req->format],
                    ['tags.tag_name', 'LIKE', '%' . $req->search_word . '%']
                ])->orderBy('id', 'desc')->paginate(6);
            } elseif ($req->tag != '') {
                $materials = tag_material::join('tags', 'tags.id', '=', 'tag_materials.tags_id')
                ->join('approved_materials', 'approved_materials.id', '=', 'tag_materials.approved_materials_id')
                ->select(
                    'approved_materials.id',
                    'approved_materials.path as path',
                    'approved_materials.type',
                    'approved_materials.likes',
                    'approved_materials.format',
                    'approved_materials.users_id as users_id',
                    'tags.tag_name'
                )
                ->where([
                    ['format', '=', $req->format],
                    ['tags.tag_name', '=', $req->tag]
                ])->orderBy('id', 'desc')->paginate(6);
            } else {
                $materials = approved_material::where('format', '=', $req->format)->orderBy('id', 'desc')->paginate(6);
            }
        }

        return materialsResource::collection($materials);
    }


}
