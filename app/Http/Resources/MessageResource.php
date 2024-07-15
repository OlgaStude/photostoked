<?php

namespace App\Http\Resources;

use App\Models\approved_material;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $path = 'none';

        if($this->user_send_id != 0){
            $path = User::find($this->user_send_id)->path;
        }

        $m_path = 'none';

        if($this->approved_materials_id != 0){
            $m_path = approved_material::find($this->approved_materials_id)->path;
        }
        
        return [
            'id' => $this->id,
            'text' => $this->text,
            'material_path' => $m_path,
            'material_id' => $this->approved_materials_id,
            'user_path' => $path,
            'user_id' => $this->user_send_id,
        ];
    }
}
