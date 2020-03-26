<?php
/**
 * Plugin Name: Mail Helpers
 * Description: Overrides mail From headers, adds check of mail functionality.
 * Version: 0.1.0
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
