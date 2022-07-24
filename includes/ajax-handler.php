<?php
header('Content-Type: text/html');

// Disable caching
header('Cache-Control: no-cache');
header('Pragma: no-cache');

if ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') :

    // Set this to true to just load the basics!
    // Only set this to true if you know what you are doing
    // Lookup SHORTINIT inside wp-settings.php for more details
    define( 'SHORTINIT', false ); 

    // Include wp-load.php
    if( !defined( 'ABSPATH' ) ){
        $pagePath = explode('/wp-content/', dirname(__FILE__));
        include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
    }

    $username   = false !== is_user_logged_in() ? wp_get_current_user()->user_login : 'wed62a3272ff4147';
    $results    = [];
    $args       = [];
    $guest_name = esc_attr( $_REQUEST['guest_name'] );

    if ( $guest_name ) {
        $args['meta_query'] = [
            'relation'      => 'AND',
            [
                'key'       => 'your_name',
                'value'     => $guest_name,
                'compare'   => 'LIKE'
            ],
            [
                'key'       => 'clientid',
                'value'     => $username
            ]
        ];

        $args['orderby']    = [
            'meta_value'    => 'ASC',
            'your_name'     => 'ASC'
        ];

        $results = pt_custom_search_query( $args, $guest_name ); 
    } ?>

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

    <?php die(); ?>

<?php else : ?>

    <?php die( 'Who are you?' ); ?>

<?php endif; ?>