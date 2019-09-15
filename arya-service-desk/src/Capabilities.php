<?php
/**
 * @package Arya\ServiceDesk
 */

namespace Arya\ServiceDesk;

/**
 * Capabilities class.
 *
 * @since 1.0.0
 */
class Capabilities
{
    /**
     * Singleton instance
     *
     * @since 1.0.0
     * @var Capabilities
     */
    private static $instance;

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    private function __construct()
    {
        add_action( 'wp_roles_init', [ $this, 'roles' ] );
    }

    /**
     * The singleton method.
     *
     * @since 1.0.0
     *
     * @return Capabilities
     */
    public static function newInstance(): Capabilities
    {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new Capabilities;
        }

        return self::$instance;
    }

    /**
     * Adds the Manager, Agent and Customer roles and their capabilities.
     *
     * @since 1.0.0
     */
    public function roles( $wp_roles )
    {
        $roles = [
            'service_desk_manager' => [
                'name' => 'Service Desk Manager',
                'capabilities' => [
                    'publish_tickets'       => true,
                    'edit_tickets'          => true,
                    'edit_others_tickets'   => true,
                    'read_private_tickets'  => true,
                    'read_hidden_tickets'   => true,
                    'delete_tickets'        => true,
                    'delete_others_tickets' => true
                ]
            ],
            'service_desk_agent' => [
                'name' => 'Service Desk Agent',
                'capabilities' => [
                    'publish_tickets'       => true,
                    'edit_tickets'          => true,
                    'edit_others_tickets'   => false,
                    'read_private_tickets'  => true,
                    'read_hidden_tickets'   => true,
                    'delete_tickets'        => false,
                    'delete_others_tickets' => false
                ]
            ],
            'service_desk_customer' => [
                'name' => 'Service Desk Customer',
                'capabilities' => [
                    'publish_tickets'       => true,
                    'edit_tickets'          => true,
                    'edit_others_tickets'   => false,
                    'read_private_tickets'  => false,
                    'read_hidden_tickets'   => false,
                    'delete_tickets'        => false, 
                    'delete_others_tickets' => false
                ]
            ]
        ];

        foreach ( $roles as $role_id => $details ) {
            $wp_roles->roles[$role_id]        = $details;
            $wp_roles->role_objects[$role_id] = new \WP_Role( $role_id, $details['capabilities'] );
            $wp_roles->role_names[$role_id]   = $details['name'];
        }

        return $wp_roles;
    }
}
