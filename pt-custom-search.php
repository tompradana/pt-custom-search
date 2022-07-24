<?php
/**
 * Plugin Name: Post Type Custom Search
 * Author: Tommy Pradana
 * Author URI: https://www.sribulancer.com/id/users/tompradana
 * Version: 1.0.0
 * Text Domain: textdomain
 * Description: PT Custom Search plugin
 * 
 * You should have received a copy of the GNU General Public License. If not, see <http://www.gnu.org/licenses/>.
 */

if ( ! function_exists( 'add_action' ) ) exit;

/**
 * Undocumented function
 *
 * @return void
 */
function pt_custom_search_shortcode( $atts ) {

    static $instance = 0;
    $instance++;

    $atts = shortcode_atts( [
        'ajax'  => "no",
        'style' => "yes"
    ], $atts, 'pt-custom-search' );

    // load style
    if ( $atts['style'] === "yes" ) {
        wp_enqueue_style( 'pt-cusotm-search-css', plugins_url( '/assets/css/style.css', __FILE__ ) );
    }

    // buffer
    ob_start(); 

    // check if using ajax
    if ( $atts['ajax'] === "yes" ) {
        wp_enqueue_script( 'pt-custom-search-js', plugins_url( '/assets/js/custom.js', __FILE__ ), ['jquery'], time(), true );
        wp_localize_script( 'pt-custom-search-js', 'pt_custom_search_var', array( 
            'ajax_url' => plugin_dir_url( __FILE__ ) . 'includes/ajax-handler.php', // @THIS WILL HOLD YOUR AJAX URL :) To retrieve this inside your script.js simply call: pluginslug_scriptname_i18n.ajax_url
            // 'wp_root' => ABSPATH // @THIS WILL HOLD THE ROOT PATH :) To retrieve this inside your script.js simply call: pluginslug_scriptname_i18n.wp_root
        ) );
    
        // include html
        include plugin_dir_path( __FILE__ ) . '/views/pt-custom-search-ajax.html.php';
    
    } else {
        $username   = false !== is_user_logged_in() ? wp_get_current_user()->user_login : 'wed62a3272ff4147';
        $results    = [];
        $args       = [];
        $guest_name = pt_custom_search_get_guest_name();

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
        }

        // include html
        include plugin_dir_path( __FILE__ ) . '/views/pt-custom-search.html.php';
    }

    return ob_get_clean();
}
add_shortcode( 'pt-custom-search', 'pt_custom_search_shortcode' );

/**
 * Undocumented function
 *
 * @return void
 */
function pt_custom_search_query( $args = [], $guest_name = "" ) {
    // default arguments
    $defaults = array(
        'post_type'         => 'tamu',
        'post_status'       => 'any',   // all post status,
        'nopaging'          => true,    // no pagination
        'posts_per_page'    => -1,      // show all posts
        'fields'            => ['ids']
    );
    
    // custom arguments
    $args = wp_parse_args( $args, $defaults );

    // get the data
    $rows = get_posts( $args ); 

    // check get fields
    $is_get_fields_exist = function_exists('get_fields') ? true : false;

    $results = [];
    
    if ( count( $rows ) ) {
        foreach( $rows as $row ) {
            $meta = $is_get_fields_exist ? get_fields( $row->ID ) : pt_custom_search_format_meta( $row->ID, get_post_meta( $row->ID ) );
            $meta['marked_name'] = preg_replace('#'. preg_quote($guest_name) .'#i', '<mark>\\0</mark>', $meta['your_name']);
            $results[] = [
                'id'                => $row->ID,
                'meta'              => $meta
            ];
        }
    }

    return $results;
}

/**
 * Undocumented function
 *
 * @return void
 */
function pt_custom_search_get_guest_name() {
    $guest_name = isset( $_GET['guest_name'] ) ? esc_attr( $_GET['guest_name'] ) : "";
    return esc_attr( $guest_name );
}

/**
 * Undocumented function
 *
 * @param array $meta
 * @return void
 */
function pt_custom_search_format_meta( $post_id = 0, $meta = [] ) {
    $new_arr = [];
    if ( !empty( $meta ) ) {
        foreach( $meta as $key => $val_arr ) {
            $new_arr[$key] = get_post_meta( $post_id, $key, true );
        }
    }

    return $new_arr;
}