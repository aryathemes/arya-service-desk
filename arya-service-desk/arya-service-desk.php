<?php
/**
 * Plugin Name: Arya Service Desk
 * Plugin URI: https://example.com/arya-service-desk
 * Description: I hope this boilerplate helps you to write the best plugin possible.
 * Author: Your Name
 * Author URI: https://example.com
 * Version: 1.0.0
 * License: GNU General Public License v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: arya-service-desk
 * Domain Path: /languages
 *
 * @package   Arya\ServiceDesk
 * @author    Your Name
 * @copyright 2019 Your Name or Company Name
 * @license   GPL-2.0-or-later
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'ARYA_SERVICE_DESK_FILE' ) ) {
    define( 'ARYA_SERVICE_DESK_FILE', __FILE__ );
}

/* PHP namespace autoloader */
require_once( dirname( ARYA_SERVICE_DESK_FILE ) . '/vendor/autoload.php' );

\Arya\ServiceDesk\Loader::newInstance( plugin_basename( ARYA_SERVICE_DESK_FILE ) );
