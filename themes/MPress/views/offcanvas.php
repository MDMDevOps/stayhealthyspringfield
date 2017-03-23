<aside class="off-canvas-menu" data-toggle="offcanvas" data-group="offcanvas" aria-expanded="false">
    <nav id="off-canvas-navigation" class="navigation-menu dropdown" role="navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">
        <header class="nav-header">
            <button class="menu-toggle" aria-expanded="false" data-toggle="<?php echo get_theme_mod( 'mpress_menu_type' ); ?>"><?php do_action( 'mpress_icon', 'menu' ) ?><span class="screen-reader-text"><?php esc_html_e( 'Menu', 'mpress' ); ?></span></button>
        </header>
        <div class="nav-body">
            <?php
                // Check if off canvas menu location has a menu assigned, else use primary menu
                $location = ( has_nav_menu( 'off-canvas-nav' ) ) ? 'off-canvas-nav' : 'primary-navbar';
                // Call Menu Function
                if( has_nav_menu( $location ) ) {
                    wp_nav_menu( array( 'theme_location' => $location, 'container' => '', 'walker' => new \Mpress\Walker_Nav_Menu() ) );
                }
            ?>
        </div>
    </nav><!-- #site-navigation -->
</aside>