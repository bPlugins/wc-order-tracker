<?php
/**
 * PHPUnit bootstrap: unit tests run without WordPress, using Brain Monkey
 * to stub WordPress functions and hooks.
 *
 * @package wc-order-tracker
 */

require_once dirname( __DIR__, 2 ) . '/vendor/autoload.php';
require_once dirname( __DIR__, 2 ) . '/vendor/yoast/phpunit-polyfills/phpunitpolyfills-autoload.php';

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', sys_get_temp_dir() . '/wordpress/' );
}

define( 'CBWCT_TESTS_PLUGIN_DIR', dirname( __DIR__, 2 ) . '/' );

if ( ! defined( 'CBWCT_TRACKER_PATH' ) ) {
	define( 'CBWCT_TRACKER_PATH', CBWCT_TESTS_PLUGIN_DIR );
}
if ( ! defined( 'CBWCT_TRACKER_URL' ) ) {
	define( 'CBWCT_TRACKER_URL', 'https://example.test/wp-content/plugins/wc-order-tracker/' );
}

require_once __DIR__ . '/TestCase.php';
