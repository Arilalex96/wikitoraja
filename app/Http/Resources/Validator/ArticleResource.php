<?php

namespace App\Http\Resources\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
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
            'author' => User::find($this->id)->name,
            'title' => $this->title,
            'content' => $this->content,
            'category' => $this->category->name,
            'tags' => $this->tags->select(['id', 'name']),
            'status' => $this->status->value ?? null,
            'rating_temp' => $this->rating_temp,
            'references' => $this->references->select('link'),
        ];
    }
}
