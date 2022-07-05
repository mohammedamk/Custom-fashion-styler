if( window.Vue === undefined ) {

    window.Vue = require('vue');

}

import App from './components/App.vue';

import Notifications from 'vue-notification';

Vue.use( Notifications );

import PartnersList from './components/Partners/List.vue';
import PartnersRecentActivity from './components/Partners/RecentActivity.vue';
import PartnerForm from './components/Partners/Form.vue';
import PartnerIntegrationForm from './components/Partners/IntegrationForm.vue';
import PartnerImportHub from './components/Partners/ImportHub.vue';

import IntegrationConnectShopify from './components/Integrations/Connect/Shopify.vue';
import IntegrationIframeShopify from './components/Integrations/Connect/ShopifyIframe.vue';
import IntegrationManageShopify from './components/Integrations/Manage/ShopifyIframe.vue';

import ModelsList from './components/Models/List.vue';
import ModelForm from './components/Models/Form.vue';

import FontsList from './components/Fonts/List.vue';
import FontForm from './components/Fonts/Form.vue';

import ProductsList from './components/Products/List.vue';

import PartnerModelsForm from './components/Partners/AssignModels.vue';
import CategoryMapper from './components/Products/CategoryMapper.vue';

import LoginForm from './components/Forms/Login.vue';

import VTooltip from 'v-tooltip';

Vue.use(VTooltip)

new Vue( {
    el: '#vue',
    components: {
        App,
        PartnersList,
        PartnersRecentActivity,
        PartnerForm,
        PartnerImportHub,
        PartnerIntegrationForm,
        IntegrationConnectShopify,
        IntegrationIframeShopify,
        IntegrationManageShopify,
        ModelsList,
        ModelForm,
        FontsList,
        FontForm,
        ProductsList,
        PartnerModelsForm,
        CategoryMapper,
        LoginForm
    },
    mounted() {

        window.Bus = this;

    }
} );
