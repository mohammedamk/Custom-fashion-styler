<nav class="dashboard__menu">

    <?php foreach ( $menu_items as $menu_item ): ?>

        <a href="{{$menu_item[ 'url' ]}}" <?php if( ! empty( $menu_item[ 'is_active' ] ) ): ?> class="dashboard__menu--is-active" <?php endif; ?>>

            <?php if( ! empty( $menu_item[ 'icon' ] ) && file_exists( $menu_item[ 'icon' ] ) ): ?>

            <?php echo file_get_contents( $menu_item[ 'icon' ] ); ?>

            <?php endif; ?>

            <?php echo $menu_item[ 'label' ]; ?>

        </a>

    <?php endforeach; ?>

</nav>
<!-- /.dashboard__menu -->
