<template>
<nav class="moda-match-footer">

  <div class="moda-match-footer-wrapper">

    <router-link to="/" exact class="moda-match-footer-link" active-class="moda-match-footer-link--active">
      <one-icon></one-icon><span class="moda-match-footer-link-dot">•</span>
      <catalog-icon></catalog-icon>
      <span>Catalog</span>
    </router-link>
    <!-- /.moda-match-footer-link -->

    <router-link to="/models" class="moda-match-footer-link" active-class="moda-match-footer-link--active">
      <two-icon></two-icon><span class="moda-match-footer-link-dot">•</span>
      <models-icon></models-icon>
      <span>Models</span>
    </router-link>
    <!-- /.moda-match-footer-link -->

    <router-link
      to="/fitting-room"
      class="moda-match-footer-link"
      active-class="moda-match-footer-link--active"
    >
      <three-icon></three-icon><span class="moda-match-footer-link-dot">•</span>
      <fitting-room-icon></fitting-room-icon>
      <span>Fitting Room</span>
      <span class="moda-match-footer-link-badge" v-if="this.$parent.fittingRoomItems.length > 0">
        {{this.$parent.fittingRoomItems.length}}
      </span>
      <!-- /.moda-match-footer-link-badge -->
    </router-link>
    <!-- /.moda-match-footer-link -->

    <router-link to="/cart" class="moda-match-footer-link" active-class="moda-match-footer-link--active">
      <four-icon></four-icon><span class="moda-match-footer-link-dot">•</span>
      <cart-icon></cart-icon>
      <span>Cart</span>
      <span class="moda-match-footer-link-badge" v-if="this.$parent.cartItems.length > 0">
        {{this.$parent.cartItems.length}}
      </span>
      <!-- /.moda-match-footer-link-badge -->
    </router-link>
    <!-- /.moda-match-footer-link -->

  </div>
  <!-- /.moda-match-footer-wrapper -->

</nav>
<!-- /.moda-match-footer -->
</template>

<script>
import CatalogIcon from '../../../images/svg/store_black_24dp.svg?inline';
import ModelsIcon from '../../../images/svg/accessibility_black_24dp.svg?inline';
import FittingRoomIcon from '../../../images/svg/checkroom_black_24dp.svg?inline';
import CartIcon from '../../../images/svg/shopping_bag_black_24dp.svg?inline';

import OneIcon from '../../../images/svg/1.svg?inline';
import TwoIcon from '../../../images/svg/2.svg?inline';
import ThreeIcon from '../../../images/svg/3.svg?inline';
import FourIcon from '../../../images/svg/4.svg?inline';

export default {

  name: 'app-footer',

  components: {
    OneIcon,
    TwoIcon,
    ThreeIcon,
    FourIcon,
    CatalogIcon,
    ModelsIcon,
    FittingRoomIcon,
    CartIcon
  },

  computed: {

    currentModel() {

      return this.getCurrentModel();
    }
  },

  data() {

    return {};
  },

  inject: [

    'getUrl',
    'getCurrentModel',
    'getFittingRoomItems'
  ],

  methods: {


  },

};
</script>
