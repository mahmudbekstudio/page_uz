<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureType extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public const POST_TYPE_ID = 1;
    public const POST_TYPE = 'post';

    public const POST_LIST_TYPE_ID = 2;
    public const POST_LIST_TYPE = 'post-list';

    public static function getList(): array
    {
        return [
            ['id' => self::POST_TYPE_ID, 'name' => self::POST_TYPE],
            ['id' => self::POST_LIST_TYPE_ID, 'name' => self::POST_LIST_TYPE],
        ];
    }
}
