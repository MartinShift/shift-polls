<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;
    protected $fillable = [
        'question_id', // Foreign key to associate the option with a question
        'value', // Field to store the value of the option
        // Add other fields here as needed
    ];
    public function votes()
{
    return $this->hasMany(Vote::class);
}
}
