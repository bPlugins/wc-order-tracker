<?php
/**
 * Base test case: sets up Brain Monkey and loads the plugin's procedural files
 * once WordPress functions are stubbed.
 *
 * @package wc-order-tracker
 */

namespace CBWCT\Tests;

use Brain\Monkey;
use Brain\Monkey\Functions;
use Yoast\PHPUnitPolyfills\TestCases\TestCase as PolyfillTestCase;

abstract class TestCase extends PolyfillTestCase {

	/**
	 * Whether the plugin files were already required in this process.
	 *
	 * @var bool
	 */
	private static $loaded = false;

	protected function set_up() {
		parent::set_up();
		Monkey\setUp();

		Functions\stubTranslationFunctions();
		Functions\stubEscapeFunctions();
		Functions\stubs(
			array(
				'wp_kses_post' => static function ( $value ) {
					return $value;
				},
			)
		);

		if ( ! self::$loaded ) {
			require_once CBWCT_TESTS_PLUGIN_DIR . 'inc/custom.php';
			require_once CBWCT_TESTS_PLUGIN_DIR . 'inc/hooks.php';
			self::$loaded = true;
		}
	}

	protected function tear_down() {
		Monkey\tearDown();
		parent::tear_down();
	}
}
