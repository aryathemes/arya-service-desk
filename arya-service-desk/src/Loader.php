<?php
/**
 * @package Arya\ServiceDesk
 */

namespace Arya\ServiceDesk;

/**
 * Hook the WordPress plugin into the appropriate WordPress actions and filters.
 *
 * @since 1.0.0
 */
class Loader
{
    /**
     * Plugin version
     *
     * @since 1.0.0
     * @var string
     */
    const VERSION = '1.0.0';

    /**
     * The path to a plugin main file
     *
     * @since 1.0.0
     * @var string
     */
    private $plugin_file = '';

    /**
     * Singleton instance
     *
     * @since 1.0.0
     * @var Loader
     */
    private static $instance;

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    private function __construct( $plugin_file )
    {
        $this->plugin_file = $plugin_file;

        add_action( 'init', [ $this, 'loadTextdomain' ] );

        add_action( 'init', [ $this, 'registerPostType' ] );

        add_action( 'init', [ $this, 'registerPostStatuses' ] );

        add_action( 'init', [ $this, 'registerTaxonomy' ] );

        add_action( 'init', [ $this, 'admin' ] );
    }

    /**
     * The singleton method.
     *
     * @since 1.0.0
     *
     * @return Loader
     */
    public static function newInstance( $plugin_file ): Loader
    {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new Loader( $plugin_file );
        }

        return self::$instance;
    }

    /**
     * Load translated strings for the current locale.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function loadTextdomain()
    {
        load_plugin_textdomain( 'arya-service-desk' );
    }

    /**
     * Register post types (Articles, FAQs and Tickets)
     *
     * @since 1.0.0
     */
    public function registerPostType()
    {
        /**
         * Articles
         */
        $articles_labels = [
            'name'          => __( 'Articles', 'arya-service-desk' ),
            'singular_name' => __( 'Article',  'arya-service-desk' )
        ];

        $articles_capabilities = [
            'edit_posts'          => 'edit_articles',
            'edit_others_posts'   => 'edit_others_articles',
            'publish_posts'       => 'publish_articles',
            'read_private_posts'  => 'read_private_articles',
            'read_hidden_posts'   => 'read_hidden_articles',
            'delete_posts'        => 'delete_articles',
            'delete_others_posts' => 'delete_others_articles'
        ];

        $articles_args = [
            'label'               => __( 'Articles', 'arya-service-desk' ),
            'labels'              => $articles_labels,
            'public'              => true,
            'hierarchical'        => false,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => false,
            'show_in_admin_bar'   => false,
            'show_in_rest'        => true,
            'menu_position'       => 40,
            'menu_icon'           => 'dashicons-media-default',
            'capabilities'        => $articles_capabilities,
            'capability_type'     => [ 'article', 'articles' ],
            'supports'            => [ 'author', 'title', 'editor', 'excerpt', 'thumbnail' ],
            'has_archive'         => true,
            'rewrite'             => [ 'slug' => 'documentation', 'with_front' => false ],
            'can_export'          => true,
            'delete_with_user'    => false
        ];
        register_post_type( 'service-desk-article', $articles_args );

        /**
         * Frequently Asked Questions
         */
        $faqs_labels = [
            'name'          => __( 'FAQs', 'arya-service-desk' ),
            'singular_name' => __( 'FAQ',  'arya-service-desk' )
        ];

        $faqs_capabilities = [
            'edit_posts'          => 'edit_faqs',
            'edit_others_posts'   => 'edit_others_faqs',
            'publish_posts'       => 'publish_faqs',
            'read_private_posts'  => 'read_private_faqs',
            'read_hidden_posts'   => 'read_hidden_faqs',
            'delete_posts'        => 'delete_faqs',
            'delete_others_posts' => 'delete_others_faqs'
        ];

        $faqs_args = [
            'label'               => __( 'FAQs', 'arya-service-desk' ),
            'labels'              => $faqs_labels,
            'public'              => true,
            'hierarchical'        => false,
            'exclude_from_search' => false,
            'publicly_queryable'  => false,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => false,
            'show_in_admin_bar'   => true,
            'show_in_rest'        => false,
            'menu_position'       => 40,
            'menu_icon'           => 'dashicons-sos',
            'capabilities'        => $faqs_capabilities,
            'capability_type'     => [ 'faq', 'faqs' ],
            'supports'            => [ 'title', 'editor' ],
            'has_archive'         => false,
            'can_export'          => true,
            'delete_with_user'    => false
        ];
        register_post_type( 'service-desk-faq', $faqs_args );

        /**
         * Tickets
         */
        $tickets_labels = [
            'name'          => __( 'Tickets', 'arya-service-desk' ),
            'singular_name' => __( 'Ticket',  'arya-service-desk' )
        ];

        $tickets_capabilities = [
            'edit_posts'          => 'edit_tickets',
            'edit_others_posts'   => 'edit_others_tickets',
            'publish_posts'       => 'publish_ticket',
            'read_private_posts'  => 'read_private_tickets',
            'read_hidden_posts'   => 'read_hidden_tickets',
            'delete_posts'        => 'delete_tickets',
            'delete_others_posts' => 'delete_others_tickets'
        ];

        $ticket_args = [
            'label'               => __( 'Tickets', 'arya-service-desk' ),
            'labels'              => $tickets_labels,
            'public'              => true,
            'hierarchical'        => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => false,
            'show_in_admin_bar'   => true,
            'show_in_rest'        => false,
            'menu_position'       => 40,
            'menu_icon'           => 'dashicons-email',
            'capabilities'        => $tickets_capabilities,
            'capability_type'     => [ 'ticket', 'tickets' ],
            'supports'            => [ 'title', 'editor' ],
            'has_archive'         => false,
            'can_export'          => true,
            'delete_with_user'    => false
        ];
        register_post_type( 'service-desk-ticket', $ticket_args );
    }

    /**
     * Register post statuses (Open, Pending, Resolved or Closed for Tickets).
     *
     * @since 1.0.0
     */
    public function registerPostStatuses()
    {
        register_post_status( 'service-desk-ticket-open', [
            'label'                     => _x( 'Open', 'post', 'arya-service-desk' ),
            'label_count'               => _n_noop( 'Open <span class="count">(%s)</span>', 'Open <span class="count">(%s)</span>', 'arya-service-desk' ),
            'protected'                 => true,
            'exclude_from_search'       => true,
            'show_in_admin_status_list' => true,
            'show_in_admin_all_list'    => false
        ] );

        register_post_status( 'service-desk-ticket-pending', [
            'label'                     => _x( 'Pending', 'post', 'arya-service-desk' ),
            'label_count'               => _n_noop( 'Pending <span class="count">(%s)</span>', 'Pending <span class="count">(%s)</span>', 'arya-service-desk' ),
            'protected'                 => true,
            'exclude_from_search'       => true,
            'show_in_admin_status_list' => true,
            'show_in_admin_all_list'    => false
        ] );

        register_post_status( 'service-desk-ticket-resolved', [
            'label'                     => _x( 'Resolved', 'post', 'arya-service-desk' ),
            'label_count'               => _n_noop( 'Resolved <span class="count">(%s)</span>', 'Resolved <span class="count">(%s)</span>', 'arya-service-desk' ),
            'protected'                 => true,
            'exclude_from_search'       => true,
            'show_in_admin_status_list' => true,
            'show_in_admin_all_list'    => false
        ] );

        register_post_status( 'service-desk-ticket-closed', [
            'label'                     => _x( 'Closed', 'post', 'arya-service-desk' ),
            'label_count'               => _n_noop( 'Closed <span class="count">(%s)</span>', 'Closed <span class="count">(%s)</span>', 'arya-service-desk' ),
            'protected'                 => true,
            'exclude_from_search'       => true,
            'show_in_admin_status_list' => true,
            'show_in_admin_all_list'    => false
        ] );
    }

    /**
     * Register taxonomies.
     *
     * @since 1.0.0
     */
    public function registerTaxonomy()
    {
        $cat_labels = [
            'name'          => __( 'Categories', 'arya-service-desk' ),
            'singular_name' => __( 'Category',   'arya-service-desk' )
        ];

        $cat_args = [
            'labels'             => $cat_labels,
            'public'             => true,
            'publicly_queryable' => true,
            'hierarchical'       => false,
            'show_in_rest'       => true,
            'show_tagcloud'      => false
        ];
        register_taxonomy( 'service-desk-article-cat', [ 'service-desk-article' ], $cat_args );

        $args = [
            'public'             => true,
            'publicly_queryable' => true,
            'hierarchical'       => false,
            'show_in_rest'       => true,
            'show_tagcloud'      => true
        ];
        register_taxonomy( 'service-desk-article-tag', [ 'service-desk-article' ], $args );
    }

    /**
     * Hook into actions and filters for administrative interface page.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function admin()
    {
        /**
         * Do not show the admin toolbar if the current user is not allowed to
         * access WordPress administration
         */
        if ( ! current_user_can( 'edit_posts' ) ) {
            add_filter( 'show_admin_bar', '__return_false' );
        }

        /* Administration functionalities */
        if ( is_admin() ) {
            \Arya\ServiceDesk\Admin\Admin::newInstance( $this );
        }
    }

    /**
     * Retrieve the basename of the plugin.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function getFile(): string
    {
        return $this->plugin_file;
    }

    /**
     * Retrieve the slug of the plugin.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function getSlug(): string
    {
        return basename( $this->plugin_file, '.php' );
    }
}
