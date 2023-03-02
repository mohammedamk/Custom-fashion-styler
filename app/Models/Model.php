<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Sopamo\LaravelFilepond\Filepond;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileUnacceptableForCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class Model extends BaseModel implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public $appends = [

        'front_image',
        'back_image',
        'profile_image'
    ];

    protected $fillable = [

        'name',
        'gender',
        'dimensions',
        'size'
    ];

    public $casts = [

        'dimensions'            => 'array',
        'size'                  => 'array'

    ];

    public function partners()
    {
        return $this->belongsToMany( Partner::class );
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection( 'front' )
            ->singleFile();

        $this->addMediaCollection( 'back' )
            ->singleFile();

        $this->addMediaCollection( 'profile' )
            ->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(386)
            ->height(386)
            ->keepOriginalImageFormat()
            ->performOnCollections( 'profile' );

        $this->addMediaConversion( 'large' )
            ->height( 1080 )
            ->keepOriginalImageFormat()
            ->performOnCollections( 'back', 'front', 'profile' );

        $this->addMediaConversion( 'medium' )
            ->height( 720 )
            ->keepOriginalImageFormat()
            ->performOnCollections( 'back', 'front', 'profile' );

        $this->addMediaConversion( 'small' )
            ->height( 480 )
            ->keepOriginalImageFormat()
            ->performOnCollections( 'back', 'front', 'profile' );
    }

    public function processImages( Request $request )
    {

        $filepond = app( Filepond::class );

        $images = [ 'front', 'back', 'profile' ];

        foreach ( $images as $image_name )
        {

            if(( $image = $request->get( $image_name . '_image' ) ) && is_array( $image ) && ! empty( $image[ 'id' ] ) && ! empty( $image[ 'name' ] ) )
            {

                if( empty( $image[ 'uploaded' ] ) )
                {

                    if( $image_path = $filepond->getPathFromServerId( $image[ 'id' ] ) )
                    {

                        try {

                            $this->getMedia( $image_name )->each( function( $i ) {

                                $i->delete();

                            } );

                            $temp_disk = config( 'filepond.temporary_files_disk', 'local' );

                            $temp_path = \Storage::disk( $temp_disk )->path( '' );

                            $file_path = str_replace( $temp_path, '', $image_path );

                            $this->addMediaFromDisk( $file_path, $temp_disk )
                                ->setFileName( $image[ 'name' ] )
                                ->toMediaCollection( $image_name );
                                // ->toMediaCollection( $image_name, 's3' );

                        }
                        catch( FileUnacceptableForCollection $wrongMime )
                        {
                            throw ValidationException::withMessages( [

                                $image_name            => 'Only image files are accepted.'

                            ] );
                        }
                        catch( \Exception $e )
                        {

                            Log::error( $e->getMessage(), $e->getTrace() );

                            throw new UnprocessableEntityHttpException( "An error occurred while uploading image for model." );
                        }
                    }
                }
            }
            else
            {

                $this->getMedia( $image_name )->each( function( $i ) {

                    $i->delete();

                } );
            }
        }
    }

    public function getFrontImageAttribute()
    {

        $image = $this->getMedia( 'front' )->first();

        if( $image )
        {
            return [

                'id'        => $image->id,
                'full'      => (string)( ( $image )() ),
                'large'     => (string)( ( $image )( 'large' ) ),
                'medium'    => (string)( ( $image )( 'medium' ) ),
                'small'     => (string)( ( $image )( 'small' ) ),
            ];
        }

        return null;
    }

    public function getBackImageAttribute()
    {
        $image = $this->getMedia( 'back' )->first();

        if( $image )
        {
            return [

                'id'        => $image->id,
                'full'      => (string)( ( $image )() ),
                'large'     => (string)( ( $image )( 'large' ) ),
                'medium'    => (string)( ( $image )( 'medium' ) ),
                'small'     => (string)( ( $image )( 'small' ) ),
            ];
        }

        return null;
    }

    public function getProfileImageAttribute()
    {

        $image = $this->getMedia( 'profile' )->first();

        if( $image )
        {
            return [

                'id'        => $image->id,
                'full'      => (string)( ( $image )() ),
                'large'     => (string)( ( $image )( 'large' ) ),
                'medium'    => (string)( ( $image )( 'medium' ) ),
                'small'     => (string)( ( $image )( 'small' ) ),
                'thumb'     => (string)( ( $image )( 'thumb' ) ),
            ];
        }

        return null;
    }

    public function getDimensionsAttribute( $value )
    {

        try
        {
            $value = json_decode( $value, true );
        }
        catch( \Exception $e ) {

            $value = [];
        }

        $formatted = [];

        foreach( $value as $key => $_value )
        {

            switch( $key )
            {
                case 'neck':
                case 'chest':
                case 'waist':
                case 'thighs':
                case 'hips':

                    $_value = isset( $_value ) ? floatval( $_value ) : null;

                    break;
                default:

                    $_value = trim( $_value );

                    break;
            }

            $formatted[ $key ] = $_value;
        }

        return $formatted;
    }

    public function getSizeAttribute( $value )
    {

        try
        {
            $value = json_decode( $value, true );
        }
        catch( \Exception $e ) {

            $value = [];
        }

        if( ! is_array( $value ) )
        {
            $value = [];
        }

        rsort( $value );

        return $value;
    }

}
