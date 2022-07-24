<div id="pt-custom-search-<?php echo $instance; ?>" class="pt-custom-search">

    <form name="custom-search-posttype" method="get" action="<?php echo get_permalink(); ?>">
        <input type="text" name="guest_name" placeholder="<?php esc_attr_e( 'Type guest name here...' ); ?>" value="<?php echo pt_custom_search_get_guest_name(); ?>" />
        <?php // wp_nonce_field( 'pt_search', 'pt_search_nonce', false ); ?>
        <button type="submit"><?php _e( 'Search', 'textdomain' ); ?></button>
    </form>

    <?php if ( $guest_name ) : ?>

        <div class="search-results">

            <?php if ( $total = count( $results ) ) : ?>

                <h4 class="search-result-title"><?php printf( __( '%s results found', 'textomain' ), $total ); ?></h4>

                <?php foreach( $results as $guest ) : ?>
                    <div class="guest">
                        <div class="guest-info">
                            <div class="guest-name"><span><?php echo $guest['meta']['marked_name']; ?></span></div>
                            <div class="guest-phone-number"><span><?php echo $guest['meta']['phone']; ?></span></div>
                        </div>
                        <a class="view-link" href="<?php echo get_permalink( $guest['id'] ); ?>"><?php _e( 'View', 'textdomain' ); ?></a>
                    </div>
                <?php endforeach; ?>

            <?php else: ?>

                <h4 class="search-result-title no-results"><?php _e( 'No results found', 'textomain' ); ?></h4>

            <?php endif; ?>
        </div>

    <?php endif; ?>
</div>