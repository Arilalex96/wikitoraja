<?php

namespace App\Http\Resources\Contributor;

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
            'title' => $this->title,
            'content' => $this->content,
            'category' => $this->category->name,
            'category_id' => $this->category->id,
            'tags' => $this->tags->select('name'),
            'status' => $this->status->value ?? null,
            'rating_temp' => $this->rating_temp,
            'references' => $this->references->select('link'),
        ];
    }
}
