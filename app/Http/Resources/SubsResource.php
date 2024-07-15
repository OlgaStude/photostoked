<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class SubsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = User::where('id', '=', $this->followed_id)->get();


        return [
            'id' => $this->id,
            'nikname' => $user[0]->nikname,
            'path' => $user[0]->path,
            'user_id' => $user[0]->id
        ];
    }
}
