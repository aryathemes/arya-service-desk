<?php
/**
 * @package Arya\ServiceDesk
 */

namespace Arya\ServiceDesk;

/**
 * Shortcodes class.
 *
 * @since 1.0.0
 */
class Shortcodes
{
    /**
     * Singleton instance
     *
     * @since 1.0.0
     * @var Shortcodes
     */
    private static $instance;

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    private function __construct()
    {
        $shortcodes = [
            'ticket'
        ];

        foreach( $shortcodes as $shortcode ) {
            add_shortcode( $shortcode, [ $this, $shortcode ] );
        }
    }

    /**
     * The singleton method.
     *
     * @since 1.0.0
     *
     * @return Shortcodes
     */
    public static function newInstance(): Shortcodes
    {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new Shortcodes();
        }

        return self::$instance;
    }

    /**
     * 
     *
     * @since 1.0.0
     */
    public function ticket()
    {
        if ( ! current_user_can( 'publish_tickets' ) ) {
            return '';
        }

        $templates = [
            'service-desk/form-ticket.php'
        ];

        $template = locate_template( $templates, false );

        if ( empty( $template ) ) {
            $template = plugin_dir_path( ARYA_SERVICE_DESK_FILE ) . 'templates/default/form-ticket.php';
        }

        ob_start();

        load_template( $template, true );

        return ob_get_clean();
    }
}
