<?php
/**
 * @package Arya\ServiceDesk
 */

namespace Arya\ServiceDesk;

/**
 * Request class.
 *
 * @since 1.0.0
 */
class Request
{
    /**
     * Singleton instance
     *
     * @since 1.0.0
     * @var Request
     */
    private static $instance;

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    private function __construct()
    {
        add_action( 'template_redirect', [ $this, 'request' ] );

        add_action( 'service_desk_ticket_request_new-ticket', [ $this, 'edit' ] );
    }

    /**
     * The singleton method.
     *
     * @since 1.0.0
     *
     * @return Request
     */
    public static function newInstance(): Request
    {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new Request();
        }

        return self::$instance;
    }

    /**
     * Action used for handling theme-side POST requests.
     *
     * @since 1.0.0
     */
    public function request()
    {
        if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) ) {
            return;
        }

        $action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );

        if ( ! in_array( $action, [ 'new-ticket' ] ) ) {
            return;
        }

        do_action( "service_desk_ticket_request_{$action}" );
    }

    /**
     * Action used for handling theme-side POST requests.
     *
     * @since 1.0.0
     */
    public function edit()
    {
        $args = [
            'subject' => FILTER_SANITIZE_STRING,
            'message' => FILTER_SANITIZE_STRING
        ];

        $input = filter_input_array( INPUT_POST, $args );

        $ticket_data = [
            'post_parent'    => 0,
            'post_status'    => 'publish',
            'post_type'      => 'service-desk-ticket',
            'post_author'    => get_current_user_id(),
            'post_password'  => '',
            'post_content'   => $input['message'],
            'post_title'     => $input['subject'],
            'menu_order'     => 0,
            'comment_status' => 'closed'
        ];

        wp_insert_post( $ticket_data );
    }
}
