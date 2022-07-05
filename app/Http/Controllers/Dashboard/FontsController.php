<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Partners\PartnerIntegrationResource;
use App\Models\Font;
use App\Models\Partner;
use Illuminate\Http\Request;

class FontsController extends DashboardController
{

    public function list_all()
    {

        $this->setTitle( 'Fonts' );

        return view( 'dashboard.fonts.list_all' );
    }

    public function create_form()
    {

        $this->setTitle( [ 'Add new', 'Fonts' ] );

        return view( 'dashboard.fonts.create_form' );
    }

    public function edit_form( Font $font )
    {

        $this->setTitle( [ $font->name, 'Fonts' ] );

        return view( 'dashboard.fonts.edit_form', [

            'font'           => $font,

        ] );
    }

}
