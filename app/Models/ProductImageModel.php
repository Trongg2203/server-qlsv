<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImageModel extends Model
{
    protected $table = 'product_images';
    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'food_id',
        'directory',
        'file_name',
        'file_ext',
        'is_primary',
        'sort_order',
        'created_at',
    ];

    protected $casts = [
        'is_primary'  => 'integer',
        'sort_order'  => 'integer',
    ];

    public function food(): BelongsTo
    {
        return $this->belongsTo(FoodModel::class, 'food_id', 'id');
    }
}
