<?php


namespace App\Interfaces;

use App\Models\Product;
use Imtigger\LaravelJobStatus\Trackable;

interface Integration
{

    /**
     * @return int
     */
    public function import();

    public function retrieveProducts();

    /**
     * @param Product[] $products
     * @return mixed
     */
    public function checkout( $products );

    public function getPartnerAttribute();

    public function createWebhooks();

}
