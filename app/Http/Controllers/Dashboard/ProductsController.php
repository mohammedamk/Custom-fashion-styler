<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;

class ProductsController extends DashboardController
{

    public function list_products( Partner $partner )
    {

        $this->setTitle( [ 'Products', $partner->name ] );

        return view( 'dashboard.products.list', [

            'partner'           => $partner

        ] );
    }

}
