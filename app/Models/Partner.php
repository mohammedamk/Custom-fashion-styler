<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class Partner extends Model
{

    use HasFactory;
    use Notifiable;
    use HasApiTokens;

    protected $fillable = [

        'name',
        'email',
        'primary_color',
        'secondary_color',
        'is_rotate_enabled',
        'is_tuck_in_enabled',
        'is_pending',
        'is_font_enabled'
    ];

    protected $with = [ 'models' ];

    protected $appends = [ 'embed_code' ];

    protected $hidden = [

        'api_token'
    ];

    public static function boot()
    {
        parent::boot();

        self::created( function ( Partner $partner ) {

            $partner->generateApiToken();

        } );
    }

    public function user()
    {
        return $this->belongsTo( User::class );
    }

    public function integration()
    {
        return $this->hasOne( PartnerIntegration::class );
    }

    public function invite()
    {
        return $this->hasOne( PartnerInvite::class );
    }

    public function products()
    {
        return $this->hasMany( Product::class );
    }

    public function product_categories()
    {
        return $this->hasMany( ProductCategory::class );
    }

    public function models()
    {
        return $this->belongsToMany( \App\Models\Model::class );
    }

    public function setPrimaryColorAttribute( $value )
    {
        $this->attributes[ 'primary_color' ] = str_replace( '#', '', $value );
    }

    public function setSecondaryColorAttribute( $value )
    {
        $this->attributes[ 'secondary_color' ] = str_replace( '#', '', $value );
    }

    public function getPrimaryColorAttribute()
    {
        return '#' . $this->attributes[ 'primary_color' ];
    }

    public function getSecondaryColorAttribute()
    {
        return '#' . $this->attributes[ 'secondary_color' ];
    }

    public function routeNotificationForMail()
    {
        return $this->email;
    }

    public function getEmbedCodeAttribute()
    {

        return sprintf(
            '<div id="moda-match-root" data-partner="%s"></div>'
            . "\n" . '<script src="%s" type="text/javascript"></script>',
            $this->api_token,
            route( 'embed.js' )
        );
    }

    public function generateApiToken()
    {

        $token = $this->createToken( 'app_token', [

            'app:products.retrieve',
            'app:categories.retrieve',
            'app:models.retrieve',
            'app:cart.manage',
            'app:embed'

        ] );

        $this->setAttribute( 'api_token', $token->plainTextToken );

        return $this->save();
    }

    public function sendInvite()
    {

        $invite = new PartnerInvite( [

            'partner_id'            => $this->id

        ] );

        if( ! $invite->save() )
        {
            throw new UnprocessableEntityHttpException( "An error occurred while creating the invitation link for the partner." );
        }
    }
}
