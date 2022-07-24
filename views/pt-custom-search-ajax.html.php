<div id="pt-custom-search-<?php echo $instance; ?>" class="pt-custom-search use-ajax">
    <form name="custom-search-posttype" method="get" action="<?php echo get_permalink(); ?>">
        <input type="text" name="guest_name" placeholder="<?php esc_attr_e( 'Type guest name here...' ); ?>" value="<?php echo pt_custom_search_get_guest_name(); ?>" />
        <?php // wp_nonce_field( 'pt_search', 'pt_search_nonce', false ); ?>
        <button type="submit"><?php _e( 'Search', 'textdomain' ); ?></button>
    </form>

    <div class="search-results"></div>
</div>