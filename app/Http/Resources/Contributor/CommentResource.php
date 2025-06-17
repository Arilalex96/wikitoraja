<?php

namespace App\Http\Resources\Contributor;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'created_at' => Carbon::parse($this->created_at)->format('j F Y'),
            'user_fullname' => $this->user->name,
        ];
    }
}
