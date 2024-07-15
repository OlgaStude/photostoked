<?php

namespace App\Http\Resources;

use App\Models\Collection_items;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class collectionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $is_in = Collection_items::where([
            ['collections_id', '=', $this->id],
            ['approved_materials_id', '=', $this->collection_material_id]
        ])->exists();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_in' => $is_in
        ];
    }
}
