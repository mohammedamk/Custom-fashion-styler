<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [

        'name',
        'partner_id'
    ];

    public function partner()
    {
        return $this->belongsTo( Partner::class );
    }

    public function products()
    {
        return $this->hasMany( Product::class );
    }
}
