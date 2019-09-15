<?php
/**
 * @link https://developer.wordpress.org/plugins/the-basics/uninstall-methods/
 *
 * @package Arya\ServiceDesk
 */

/* if uninstall.php is not called by WordPress, exit */
defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

/* Remove roles */
remove_role( 'service_desk_customer' );
remove_role( 'service_desk_agent'    );
remove_role( 'service_desk_manager'  );

/* Removes all cache items. */
wp_cache_flush();
