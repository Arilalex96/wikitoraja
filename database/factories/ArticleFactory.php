<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\ArticleStatus;
use App\Models\Reference;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => ucwords(fake()->words(5, true)),
            'category_id' => mt_rand(1, 10),
            'content' => fake()->paragraphs(10, true),
            'image' => '6c757dh98h9jb.jpg',
            'user_id' => fake()->passthrough(mt_rand(3, 5)),
            'rating_temp' => mt_rand(1, 5),
            'created_at' => now(),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Article $article) {
            for($i = 1; $i <= 3; $i++){
                Reference::create([
                    'article_id' => $article->id,
                    'link' => fake()->url(),
                    'created_at' => now(),
                ]);
            }

            ArticleStatus::create([
                'article_id' => $article->id,
                'value' => true,
                'user_id' => 2,
                'created_at' => now(),
            ]);

            $article->attachTags(fake()->words(5));
        });
    }
}
