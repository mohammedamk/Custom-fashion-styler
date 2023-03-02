<?php

namespace App\Models;

use App\Notifications\Partners\InviteNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class PartnerInvite extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id'
    ];

    public static function boot()
    {
        parent::boot();

        self::creating( function( PartnerInvite $invite ) {

            $invite->populateUUID();
            $invite->generateSecret();

            $invite->setAttribute( 'expire_at', Carbon::now()->addDays( 7 ) );

        } );

        self::created( function( PartnerInvite $invite ) {

            $invite->notifyPartner();

        } );
    }

    public function partner()
    {
        return $this->belongsTo( Partner::class );
    }

    public function populateUUID()
    {

        if( $this->id )
        {
            return true;
        }

        $uuid = null;

        do {

            $uuid = Str::uuid()->toString();

        }
        while( PartnerInvite::query()->where( 'id', '=', $uuid )->exists() );

        $this->id = $uuid;

        return true;
    }

    public function generateSecret()
    {

        if( $this->secret_key )
        {
            return true;
        }

        $secret = null;

        do {

            $secret = Str::random( 64 );

        }
        while( PartnerInvite::query()->where( 'secret_key', '=', $secret )->exists() );

        $this->secret_key = $secret;

        return true;
    }

    public function notifyPartner()
    {

        if( ! $partner = $this->partner()->first() )
        {
            return false;
        }

        /**
         * @var Partner $partner
         */

        $partner->notify( new InviteNotification( $this ) );

    }

    public function getKeyType()
    {
        return 'string';
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getUrlAttribute()
    {

        $url = route( 'public.partners.invite', [

            'invite'        => $this

        ] );

        $url = $url . '?token=' . $this->secret_key;

        return $url;
    }
}
