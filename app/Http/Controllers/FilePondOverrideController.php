<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Illuminate\Support\Facades\Storage;

class FilePondOverrideController extends Controller
{

    public function load( $id, Request $request )
    {

        $media = Media::query()->where( 'id', '=', $id )->first();

        if( ! $media ) {
            throw new NotFoundHttpException( 'Not found' );
        }

        /**
         * @var Media $media
         */

        $path = null;

        if( $media->hasGeneratedConversion( 'thumb' ) ) {
          // if ( $media->disk === "s3" ) {
          //   $path = $media->getUrl( 'thumb' );
          // } else {
            $path = $media->getPath( 'thumb' );
          // }
        } else if( $media->hasGeneratedConversion( 'small' ) ) {
          // if ( $media->disk === "s3" ) {
          //   $path = $media->getUrl( 'small' );
          // } else {
            $path = $media->getPath( 'small' );
          // }
        } else {
          // if ( $media->disk === "s3" ) {
          //   $path = $media->getUrl();
          // } else {
            $path = $media->getPath();
          // }
        }

        if ( $media->disk === "s3" ) {

          // $file = Storage::disk( 's3' )->getDriver()->readStream( '/' . $media->id . '/' . $media->file_name );
          $file = Storage::disk( 's3' )->getDriver()->readStream( $path );

          return \Response::stream(function() use($file) {
            fpassthru($file);
          }, 200, [
            'Accept-Ranges'       =>  'bytes',
            'Cache-Control'       =>  'public',
            'Content-Type'        =>  $media->mime_type,
            'Content-Disposition' =>  'attachment; filename="' . $media->file_name . '"',
            'Content-Length'      =>  Storage::size( $path )
          ]);
        } else {
          return response()->download( $path );
        }


    }

}
