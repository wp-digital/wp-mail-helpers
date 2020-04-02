<?php
/**
 * Plugin Name: Mail Helpers
 * Description: Overrides mail From headers, adds check of mail functionality.
 * Version: 1.1.1
 * Author: Innocode
 * Author URI: https://innocode.com
 * Tested up to: 5.3.2
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

use Innocode\Mail\Helpers;

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    require_once __DIR__ . '/vendor/autoload.php';
}

$innocode_mail_helpers = new Helpers\Plugin( __DIR__ );
$innocode_mail_helpers->run();

$GLOBALS['innocode_mail_helpers'] = $innocode_mail_helpers;

if ( ! function_exists( 'innocode_mail_helpers' ) ) {
    function innocode_mail_helpers() {
        /**
         * @var Helpers\Plugin $innocode_mail_helpers
         */
        global $innocode_mail_helpers;

        return $innocode_mail_helpers;
    }
}
