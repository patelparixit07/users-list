<?php
/**
 * PHPUnit bootstrap file.
 *
 * @package Users_List
 */

if ( PHP_MAJOR_VERSION >= 8 ) {
	echo "The scaffolded tests cannot currently be run on PHP 8.0+. See https://github.com/wp-cli/scaffold-command/issues/285" . PHP_EOL; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	exit( 1 );
}

$_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $_tests_dir ) {
	$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

if ( ! file_exists( "{$_tests_dir}/includes/functions.php" ) ) {
	echo "Could not find {$_tests_dir}/includes/functions.php, have you run bin/install-wp-tests.sh ?" . PHP_EOL; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	exit( 1 );
}

// Give access to tests_add_filter() function.
require_once "{$_tests_dir}/includes/functions.php";

/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin() {
	require dirname( dirname( __FILE__ ) ) . '/users-list.php';
}

tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

// Start up the WP testing environment.
require "{$_tests_dir}/includes/bootstrap.php";

function pre_http_request_halt_request( $preempt, $args, $url ) {
	throw new \Exception(
		json_encode(
			[
				'url'     => $url,
				'method'  => $args['method'],
				'headers' => $args['headers'],
				'body'    => json_decode( $args['body'], true ),
				'preempt' => $preempt,
			]
		)
	);
}

function wp_ajax_halt_handler_filter() {
	return 'wp_ajax_halt_handler';
}

function wp_ajax_halt_handler( $message, $title, $args ) {
	$is_bad_nonce = -1 === $message && ! empty( $args['response'] ) && 403 === $args['response'];
	throw new Exception( $is_bad_nonce ? 'bad_nonce' : 'die_ajax' );
}

function wp_ajax_print_handler_filter() {
	return 'wp_ajax_print_handler';
}

function wp_ajax_print_handler( $message ) {
	echo $message;
}
