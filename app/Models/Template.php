<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Template extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'website_id', 'name', 'type', 'content', 'params'];

    protected $casts = [
        'content' => 'array',
        'params' => 'array',
    ];

    const TYPE_LAYOUT = 'layout';
    const TYPE_BLOCK = 'block';
    const TYPE_POST = 'post';
    const TYPE_CATEGORY = 'category';

    public static function types(): array
    {
        return [self::TYPE_LAYOUT, self::TYPE_BLOCK, self::TYPE_POST, self::TYPE_CATEGORY];
    }

    public static function defaultType(): string
    {
        return self::TYPE_BLOCK;
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }
}
