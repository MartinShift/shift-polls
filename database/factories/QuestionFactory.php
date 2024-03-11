<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       return [
            'title' => 'Test Title',
            'description' => 'Test Description',
            'start_at' => now(),
            'end_at' => now()->modify("+" . 5 . " days"),
            'user_id'=>23
        ];
    }
}
