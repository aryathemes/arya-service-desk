<?php
/**
 * @package Arya\ServiceDesk\Admin
 */

namespace Arya\ServiceDesk\Admin;

/**
 * Ticket class.
 *
 * @since 1.0.0
 */
class Ticket
{
    /**
     * Singleton instance
     *
     * @since 1.0.0
     * @var Ticket
     */
    private static $instance;

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    private function __construct()
    {
        /* Placeholder */
        add_filter( 'enter_title_here', [ $this, 'placeholder' ], 10, 2 );
    }

    /**
     * The singleton method.
     *
     * @since 1.0.0
     *
     * @return Ticket
     */
    public static function newInstance(): Ticket
    {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new Ticket;
        }

        return self::$instance;
    }

    /**
     * Filters the title field placeholder text.
     *
     * @since 1.0.0
     */
    public function placeholder( $text, $post )
    {
        if ( 'service-desk-ticket' === $post->post_type ) {
            $text = __( 'Add Ticket', 'arya-service-desk' );
        }

        return $text;
    }
}
