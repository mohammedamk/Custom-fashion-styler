<?php

namespace App\Jobs\Partners;

use App\Interfaces\Integration;
use App\Models\Partner;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Traits\Integrated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Imtigger\LaravelJobStatus\Trackable;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

use Illuminate\Support\Facades\Log;

class ImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Trackable;

    public $integration;

    public function __construct( Integration $integration )
    {

        $this->integration = $integration;

        $this->prepareStatus();

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $this->setOutput( [

            'message'       => 'Retrieving products from integration'

        ] );

        $products = $this->integration->retrieveProducts();

        $total = count( $products );

        $this->setOutput( [

            'message'       => 'Found ' . $total . ' products to import'

        ] );

        $this->setProgressMax( $total );

        $partner = $this->integration->getPartnerAttribute();

        if( ! $partner )
        {
            throw new \Exception( "No partner found." );
        }

        /**
         * @var Partner $partner
         */

        $current_progress = 0;

        foreach ( $products as $product_data )
        {

          // Log::info( '$product_data: ' . json_encode( $product_data ) );

            $this->setProgressNow( ++$current_progress );

            $this->setOutput( [

                'message'       => 'Importing product ' . $current_progress . ' of ' . $total

            ] );

            Product::fromObject( $partner, $product_data );
        }

        $this->setOutput( [

            'message'           => 'Imported.'

        ] );
    }
}
