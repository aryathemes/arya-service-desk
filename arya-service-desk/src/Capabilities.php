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
        add_filter( 'map_meta_cap', [ $this, 'mapping' ], 10, 4);

        add_action( 'wp_roles_init', [ $this, 'roles' ] );

        add_action( 'set_current_user', [ $this, 'capabilities' ] );
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
     * Maps meta capabilities to primitive capabilities.
     *
     * @since 1.0.0
     */
    public function mapping( $caps, $cap, $user_id, $args )
    {
        switch ( $cap ) {
            case 'edit_ticket':
                $_post = get_post( $args[0] );

                if ( ! empty( $_post ) ) {
                    $post_type = get_post_type_object( $_post->post_type );

                    $caps = [];

                    if ( $user_id == $_post->post_author ) {
                        $caps[] = $post_type->cap->edit_posts;
                    } else {
                        $caps[] = $post_type->cap->edit_others_posts;
                    }
                }
                break;
            case 'delete_ticket':
                $_post = get_post( $args[0] );

                if ( ! empty( $_post ) ) {
                    $post_type = get_post_type_object( $_post->post_type );

                    $caps = [];

                    if ( $user_id == $_post->post_author ) {
                        $caps[] = $post_type->cap->delete_posts;
                    } else {
                        $caps[] = $post_type->cap->delete_others_posts;
                    }
                }
                break;
        }

        return $caps;
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
                    /* Tickets */
                    'publish_tickets'       => true,
                    'edit_tickets'          => true,
                    'edit_others_tickets'   => true,
                    'read_private_tickets'  => true,
                    'read_hidden_tickets'   => true,
                    'delete_tickets'        => true,
                    'delete_others_tickets' => true,

                    /* Replies */
                    'publish_replies'       => true,
                    'edit_replies'          => true,
                    'edit_others_replies'   => true,
                    'read_private_replies'  => true,
                    'read_hidden_replies'   => true,
                    'delete_replies'        => true,
                    'delete_others_replies' => true
                ]
            ],
            'service_desk_agent' => [
                'name' => 'Service Desk Agent',
                'capabilities' => [
                    /* Tickets */
                    'publish_tickets'       => true,
                    'edit_tickets'          => true,
                    'edit_others_tickets'   => false,
                    'read_private_tickets'  => true,
                    'read_hidden_tickets'   => true,
                    'delete_tickets'        => false,
                    'delete_others_tickets' => false,

                    /* Replies */
                    'publish_replies'       => true,
                    'edit_replies'          => true,
                    'edit_others_replies'   => false,
                    'read_private_replies'  => true,
                    'read_hidden_replies'   => true,
                    'delete_replies'        => true,
                    'delete_others_replies' => false
                ]
            ],
            'service_desk_customer' => [
                'name' => 'Service Desk Customer',
                'capabilities' => [
                    /* Tickets */
                    'publish_tickets'       => true,
                    'edit_tickets'          => true,
                    'edit_others_tickets'   => false,
                    'read_private_tickets'  => false,
                    'read_hidden_tickets'   => false,
                    'delete_tickets'        => false,
                    'delete_others_tickets' => false,

                    /* Replies */
                    'publish_replies'       => true,
                    'edit_replies'          => true,
                    'edit_others_replies'   => false,
                    'read_private_replies'  => false,
                    'read_hidden_replies'   => false,
                    'delete_replies'        => true,
                    'delete_others_replies' => false
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

    /**
     * Sets a new role to the current user.
     *
     * @since 1.0.0
     */
    public function capabilities()
    {
        $user_id = get_current_user_id();

        if ( $this->getServiceDeskRole( $user_id ) ) {
            return;
        }

        $blog_role = $this->getWordPressRole( $user_id );

        /* Role mapping */
        $new_role = 'administrator' == $blog_role ?
            'service_desk_manager' :
            'service_desk_customer';

        error_log( $new_role );

        $user = wp_get_current_user();

        $user->add_role( $new_role );
    }

    /**
     * @since 1.0.0
     */
    private function getServiceDeskRole( $user_id )
    {
        $role = '';

        $user = get_userdata( $user_id );

        $roles = array_intersect( $user->roles, [
            'service_desk_manager',
            'service_desk_agent',
            'service_desk_customer'
        ]);

        $roles = array_values( array_filter( $roles ) );

        if ( ! empty( $roles ) ) {
            $role = $roles[0];
        }

        return $role;
    }

    /**
     * @since 1.0.0
     */
    private function getWordPressRole( $user_id )
    {
        $role = '';

        $user = get_userdata( $user_id );

        $roles = array_intersect( $user->roles, [
            'administrator',
            'editor',
            'author',
            'contributor',
            'subscriber'
        ]);

        $roles = array_values( array_filter( $roles ) );

        if ( ! empty( $roles ) ) {
            $role = $roles[0];
        }

        return $role;
    }
}
