<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Spatie\MediaLibrary\HasMedia;

class Font extends BaseModel implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    
}
