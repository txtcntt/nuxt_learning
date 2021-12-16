<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstProduct extends Model
{
    use HasFactory;
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'product_name',
        'product_price',
        'product_image',
        'description',
        'is_sales',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public $timestamps = false;
    public $incrementing = false;

    protected $primaryKey = 'product_id';

    public function getImageUrlAttribute($value)
    {
        return config('filesystems.disk.public.url') . '/' . config('constants.folder_path.product') . '/'. $value;
    }

}
