<?php

namespace App\Http\Controllers;

use App\Http\Resources\materialsResource;
use App\Models\approved_material;
use App\Models\like;
use App\Models\tag_material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class mainPageController extends Controller
{

    // Загрузка большего колличества изображений
    // для главной и для каталога
    
    public function more_data(Request $req){
        if ($req->search_word != '') {
            if ($req->type != '' && $req->format != '') {
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
                    ['tags.tag_name', 'LIKE', '%' . $req->search_word . '%'],
                    ['approved_materials.id', '<', $req->id]
                ])->orderBy('approved_materials.id', 'desc')->paginate(6);
            } elseif ($req->type != '') {
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
                    ['tags.tag_name', 'LIKE', '%' . $req->search_word . '%'],
                    ['approved_materials.id', '<', $req->id]
                ])->orderBy('approved_materials.id', 'desc')->paginate(6);
            } elseif ($req->format != '') {
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
                    ['tags.tag_name', 'LIKE', '%' . $req->search_word . '%'],
                    ['approved_materials.id', '<', $req->id]
                ])->orderBy('approved_materials.id', 'desc')->paginate(6);
            } else {
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
                    ['tags.tag_name', 'LIKE', '%' . $req->search_word . '%'],
                    ['approved_materials.id', '<', $req->id]
                ])->orderBy('approved_materials.id', 'desc')->paginate(6);
            }

            $cur_id = 0;
            foreach ($materials as $row => $material) {
                if ($material->id == $cur_id) {
                    unset($materials[$row]);
                }
                $cur_id = $material->id;
            }
        } elseif ($req->tag != '') {
            if ($req->type != '' && $req->format != '') {
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
                    ['tags.tag_name', '=', $req->tag],
                    ['approved_materials.id', '<', $req->id]
                ])->orderBy('approved_materials.id', 'desc')->paginate(6);
            } elseif ($req->type != '') {
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
                    ['tags.tag_name', '=', $req->tag],
                    ['approved_materials.id', '<', $req->id]
                ])->orderBy('approved_materials.id', 'desc')->paginate(6);
            } elseif ($req->format != '') {
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
                    ['tags.tag_name', '=', $req->tag],
                    ['approved_materials.id', '<', $req->id]
                ])->orderBy('approved_materials.id', 'desc')->paginate(6);
            } else {
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
                    ['tags.tag_name', '=', $req->tag],
                    ['approved_materials.id', '<', $req->id]
                ])->orderBy('approved_materials.id', 'desc')->paginate(6);
            }

            $cur_id = 0;
            foreach ($materials as $row => $material) {
                if ($material->id == $cur_id) {
                    unset($materials[$row]);
                }
                $cur_id = $material->id;
            }
        } elseif ($req->type != '' && $req->format != '') {
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
                ['approved_materials.id', '<', $req->id]
            ])->orderBy('approved_materials.id', 'desc')->paginate(6);
        } elseif ($req->type != '') {
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
                ['approved_materials.id', '<', $req->id]
            ])->orderBy('approved_materials.id', 'desc')->paginate(6);
        } elseif ($req->format != '') {
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
                ['approved_materials.id', '<', $req->id]
            ])->orderBy('approved_materials.id', 'desc')->paginate(6);
        } elseif ($req->user_id != '') {
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
                ['users_id', '=', $req->user_id],
                ['approved_materials.id', '<', $req->id]
            ])->orderBy('approved_materials.id', 'desc')->paginate(6);
        } elseif ($req->user_likes_id != '') {
            $materials = like::join('users', 'users.id', '=', 'likes.users_id')
            ->join('approved_materials', 'approved_materials.id', '=', 'likes.approved_materials_id')
            ->where([
                ['users.id', '=', Auth::user()->id],
                ['approved_materials.id', '<', $req->id]
            ])
                ->select('approved_materials.id', 'approved_materials.path', 'approved_materials.type', 'approved_materials.likes', 'approved_materials.users_id')
                ->orderBy('approved_materials.id', 'desc')->paginate(6);
        } else {
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
            ->where('approved_materials.id', '<', $req->id)->orderBy('approved_materials.id', 'desc')->paginate(3);
        }

        $cur_id = 0;
        foreach ($materials as $row => $material) {
            if ($material->id == $cur_id) {
                unset($materials[$row]);
            }
            $cur_id = $material->id;
        }

        return materialsResource::collection($materials);
    }

    // Загрузка большего колличества изображений
    // на странице пользователя

    public function more_user(Request $req){
        $materials = approved_material::where([
            ['id', '<', $req->id],
            ['users_id', '=', $req->id_user]
            ])->latest()->paginate(6);
        return materialsResource::collection($materials);
        
    }

    // Загрузка большего колличества изображений
    // на странице с понравившимися работами

    public function more_liked(Request $req){
        $materials = Like::join('users', 'users.id', '=', 'likes.users_id')
        ->join('approved_materials', 'approved_materials.id', '=', 'likes.approved_materials_id')
        ->where([
            ['users.id', '=', Auth::user()->id],
            ['approved_materials.id', '<', $req->id]
        ])
        ->select('approved_materials.id', 'approved_materials.path', 'approved_materials.type', 'approved_materials.likes', 'approved_materials.users_id')
        ->orderBy('approved_materials.id', 'desc')->paginate(4);
        return materialsResource::collection($materials);
        
    }


}
