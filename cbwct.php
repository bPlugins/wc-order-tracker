<?php

/*
	Plugin Name: Order Tracker for WooCommerce – Track Customer Order
	Plugin URI: https://wordpress.org/plugins/wc-order-tracker/
	Description: Allow customers to easily track their orders on your WooCommerce store with this order tracking system, powered by AJAX.
	Author: bPlugins
	Version: 1.2.9
	Author URI: https://bPlugins.com
	Text Domain: wc-order-tracker
	Requires Plugins: woocommerce
	License: GPLv2 or later
	License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/
// Don't call the file directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
if ( !function_exists( 'wot_fs' ) ) {
    // Create a helper function for easy SDK access.
    function wot_fs() {
        global $wot_fs;
        if ( !isset( $wot_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/vendor/freemius/start.php';
            $wot_fs = fs_dynamic_init( array(
                'id'               => '30067',
                'slug'             => 'wc-order-tracker',
                'type'             => 'plugin',
                'public_key'       => 'pk_4a52b93219ae8de8b416e101bfcfd',
                'is_premium'       => false,
                'premium_suffix'   => 'Pro',
                'has_addons'       => false,
                'has_paid_plans'   => true,
                'is_org_compliant' => true,
                'menu'             => array(
                    'support' => false,
                ),
                'is_live'          => true,
            ) );
        }
        return $wot_fs;
    }

    // Init Freemius.
    wot_fs();
    // Signal that SDK was initiated.
    do_action( 'wot_fs_loaded' );
}
//define URL
define( 'CBWCT_TRACKER_URL', plugin_dir_url( __FILE__ ) );
define( 'CBWCT_TRACKER_PATH', plugin_dir_path( __FILE__ ) );
define( 'CBWCT_TRACKER_VERSION', '1.2.9' );
//Enqueue Style for Plugin
function cbwct_basic_scripts() {
    wp_enqueue_style(
        'cbwct-animate',
        CBWCT_TRACKER_URL . 'css/ali-animate.css',
        array(),
        CBWCT_TRACKER_VERSION
    );
    wp_enqueue_style(
        'cbwct-tracker-style',
        CBWCT_TRACKER_URL . 'css/style.css',
        array(),
        CBWCT_TRACKER_VERSION
    );
    wp_enqueue_style(
        'cbwct-tracker-responsive',
        CBWCT_TRACKER_URL . 'css/responsive.css',
        array(),
        CBWCT_TRACKER_VERSION
    );
    wp_enqueue_script(
        'cbwct-tracker-ajax',
        CBWCT_TRACKER_URL . 'js/ajax-active.js',
        array('jquery'),
        CBWCT_TRACKER_VERSION,
        true
    );
    wp_localize_script( 'cbwct-tracker-ajax', 'cbwct_tracker', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
    ) );
}

add_action( 'wp_enqueue_scripts', 'cbwct_basic_scripts' );
//Include additional file
require_once CBWCT_TRACKER_PATH . '/inc/custom.php';
require_once CBWCT_TRACKER_PATH . '/inc/shortcode.php';
require_once CBWCT_TRACKER_PATH . '/inc/hooks.php';