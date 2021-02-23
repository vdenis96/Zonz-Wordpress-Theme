<?php

/**
 * Require authentication
 *
 * @return void
 */
function wcct_require_auth() {
    $auth      = wcct_get_auth();
    $auth_user = ( isset( $auth['username'] ) ) ? $auth['username'] : '';
    $auth_pass = ( isset( $auth['password'] ) ) ? $auth['password'] : '';

    header('Cache-Control: no-cache, must-revalidate, max-age=0');
    $has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
    $is_not_authenticated = (
        !$has_supplied_credentials ||
        $_SERVER['PHP_AUTH_USER'] != $auth_user ||
        $_SERVER['PHP_AUTH_PW']   != $auth_pass
    );
    if ($is_not_authenticated) {
        header('HTTP/1.1 401 Authorization Required');
        header('WWW-Authenticate: Basic realm="Access denied"');
        exit;
    }
}

/**
 * Get authentication data
 *
 * @return array
 */
function wcct_get_auth() {
    $auth       = get_option( 'wcct_data_feed' );
    $auth_data  = array();

    if ( isset( $auth['authenticate'] ) && ! empty( $auth['authenticate'] ) ) {
        $auth_data = $auth['authenticate'];
    }

    return $auth_data;
}

/**
 * Auth enable
 *
 * @return boolean
 */
function wcct_http_auth_enable() {
    $auth   = wcct_get_auth();

    if ( isset( $auth['wcct-authenticate-enable'] ) &&  $auth['wcct-authenticate-enable'] == 'yes' ) {
        return true;
    }

    return false;
}


/**
 * Category checklist walker
 *
 * @since 0.8
 */
class WCCT_Walker_Category_Checklist extends Walker {
    var $tree_type = 'product_cat';
    var $db_fields = array('parent' => 'parent', 'id' => 'term_id'); //TODO: decouple this
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat( "\t", $depth );
        $output .= "$indent<ul class='children'>\n";
    }
    function end_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat( "\t", $depth );
        $output .= "$indent</ul>\n";
    }
    function start_el( &$output, $category, $depth = 0, $args = array(), $current_object_id = 0 ) {
        extract( $args );
        if ( empty( $taxonomy ) )
            $taxonomy = 'product_cat';
        if ( $taxonomy == 'product_cat' )
            $name = 'product_cat';
        else
            $name = $taxonomy;
        if ( 'yes' === $show_inline ) {
            $inline_class = 'wcct-checkbox-inline';
        } else {
            $inline_class = '';
        }
        $class = isset( $args['class'] ) ? $args['class'] : '';
        $output .= "\n<li class='" . $inline_class . "' id='{$taxonomy}-{$category->term_id}'>" . '<label class="selectit"><input class="'. $class . '" value="' . $category->term_id . '" type="checkbox" name="' . $name . '[]" id="in-' . $taxonomy . '-' . $category->term_id . '"' . checked( in_array( $category->term_id, $selected_cats ), true, false ) . disabled( empty( $args['disabled'] ), false, false ) . ' /> ' . esc_html( apply_filters( 'the_category', $category->name ) ) . '</label>';
    }
    function end_el( &$output, $category, $depth = 0, $args = array() ) {
        $output .= "</li>\n";
    }
}

/**
 * Displays checklist of a taxonomy
 *
 * @since 0.8
 * @param int $post_id
 * @param array $selected_cats
 */
function wcct_category_checklist( $post_id = 0, $selected_cats = false, $attr = array(), $class = null ) {
    require_once ABSPATH . '/wp-admin/includes/template.php';
    $walker       = new WCCT_Walker_Category_Checklist();
    $exclude_type = isset( $attr['exclude_type'] ) ? $attr['exclude_type'] : 'exclude';
    $exclude      = explode( ',', isset( $attr['exclude'] ) );
    $tax          = 'product_cat';
    $current_user = get_current_user_id();
    $args = array(
        'taxonomy' => $tax,
    );
    if ( $post_id ) {
        $args['selected_cats'] = wp_get_object_terms( $post_id, $tax, array('fields' => 'ids') );
    } elseif ( $selected_cats ) {
        $args['selected_cats'] = $selected_cats;
    } else {
        $args['selected_cats'] = array();
    }
    $args['show_inline'] = 'show_inline';
    $args['class'] = $class;
    $tax_args = array(
        'hide_empty'  => false,
        $exclude_type => (array) $exclude,
        'orderby'     => isset( $attr['orderby'] ) ? $attr['orderby'] : 'name',
        'order'       => isset( $attr['order'] ) ? $attr['order'] : 'ASC',
    );
    $tax_args = apply_filters( 'wcct_taxonomy_checklist_args', $tax_args );
    $categories = (array) get_terms( $tax, $tax_args );
    echo '<ul class="cat-checklist product_cat-checklist wcct-category-checklist">';
    printf( '<input type="hidden" name="%s" value="0" />', $tax );
    echo call_user_func_array( array(&$walker, 'walk'), array($categories, 0, $args) );
    echo '</ul>';
}