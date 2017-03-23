<form role="search" method="get" class="search" action="<?php echo get_site_url(); ?>/">
    <div class="input-group">
        <span class="form-input">
            <label class="screen-reader-text"><?php esc_html_e( 'Search', 'mpress' ); ?></label>
            <input type="search" class="search_input" placeholder="<?php esc_html_e( 'Search', 'mpress' ); ?> &hellip;" value="" name="s" title="Search">
        </span>
        <span class="input-group-button">
            <button type="submit" class="button"><span class="icon fa fa-search"></span>&nbsp;<span class="screen-reader-text"><?php esc_html_e( 'Search', 'mpress' ); ?></span></button>
        </span>
    </div>
</form>