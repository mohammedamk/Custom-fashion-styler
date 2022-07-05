<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerIntegration extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'integration_id',
        'integration_type'
    ];

    public function partner()
    {
        return $this->belongsTo( Partner::class );
    }

    public function integration()
    {
        return $this->morphTo( 'integration' );
    }

}
