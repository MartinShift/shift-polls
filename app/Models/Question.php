<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property Carbon|null $start_at
 * @property Carbon|null $end_at
 * @property int $user_id
 * @property int|null $image_id
 * @property bool $active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Question extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'start_at',
        'end_at',
        'user_id',
        'image_id',
        'active'
    ];

    public function getDates()
    {
        return [
            'start_at',
            'end_at',
            'updated_at',
            'created_at'
        ] ;
    }
    public function options()
    {
        return $this->hasMany(Option::class);
    }
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
    public function image()
    {
        return $this->belongsTo(Image::class);
    }
    protected $casts = [
        'active' => 'boolean',
//        'start_at' => 'date',
//        'end_at' => 'date',
    ];
}
