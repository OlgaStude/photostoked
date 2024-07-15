<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportedResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'report_id' => $this->id,
            'path' => User::find($this->users_id)->path,
            'reported_nik' => User::find($this->users_id)->nikname,
            'reported_id' => User::find($this->users_id)->id,
            'reporter_nik' => User::find($this->user_send_id)->nikname,
            'reporter_id' => User::find($this->user_send_id)->id,
        ];
    }
}
