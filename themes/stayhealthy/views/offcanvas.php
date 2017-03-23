<aside class="off-canvas-menu" data-toggle="offcanvas" data-group="offcanvas" aria-expanded="false">
    <nav id="off-canvas-navigation" class="navigation-menu dropdown" role="navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">
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