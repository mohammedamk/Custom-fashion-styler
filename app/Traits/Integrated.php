<?php


namespace App\Traits;


use App\Models\Partner;
use App\Models\PartnerIntegration;
use phpDocumentor\Reflection\Types\Boolean;

trait Integrated
{

    /**
     * @var Partner|null
     */
    private $_partner = null;

    public function integration()
    {
        return $this->morphOne( PartnerIntegration::class, 'integration' );
    }

    public function getPartnerAttribute()
    {

        if( ! isset( $this->_partner ) )
        {

            if( $integration = $this->integration()->first() )
            {

                /**
                 * @var PartnerIntegration $integration
                 */

                if( $partner = $integration->partner()->first() )
                {

                    /**
                     * @var Partner $partner
                     */

                    $this->_partner = $partner;
                }
            }
        }

        return $this->_partner;
    }

    /**
     * @return bool
     */
    public function getIsActiveAttribute()
    {
        return false;
    }

    public function getButtonCodeAttribute()
    {
        return '<a href="#" class="moda-match-button" data-moda-match="">Virtual Fitting Room</a>';
    }
}
