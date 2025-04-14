<?php

namespace App\Models;

use App\Traits\ModelAttributesTrait;
use Illuminate\{Database\Eloquent\Factories\HasFactory,
    Database\Eloquent\Model,
    Database\Eloquent\SoftDeletes};

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tasks';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'completed',
        'priority',
        'file_path',
    ];

    protected $casts = [
        'title' => 'string',
        'description' => 'string',
        'completed' => 'bool',
        'priority' => 'string',
        'file_path' => 'string'
    ];

    /**
     * @return array
     */
    public static function getAll(): array
    {
        $arr = [];
        $data = self::get();
        foreach ($data as $item) {
            $arr[$item->id] = ucfirst($item->title);
        }
        return $arr;
    }
}
