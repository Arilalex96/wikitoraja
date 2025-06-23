<?php

namespace App\Http\Resources\Contributor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ArticleSimplifiedResource extends JsonResource
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
            'slug' => $this->slug,
            'title' => $this->title,
            'content' =>  Str::limit($this->content, 50, '...'),
            'category' => $this->category->name,
            'status' => $this->status->value ?? null,
            'author' => $this->user->email,
        ];
    }
}
