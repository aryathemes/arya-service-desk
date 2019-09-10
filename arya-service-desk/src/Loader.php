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

        add_action( 'after_setup_theme', [ $this, 'themes' ] );

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
            'name'               => __( 'Articles',                   'arya-service-desk' ),
            'singular_name'      => __( 'Article',                    'arya-service-desk' ),
            'edit_item'          => __( 'Edit Article',               'arya-service-desk' ),
            'new_item'           => __( 'New Article',                'arya-service-desk' ),
            'view_item'          => __( 'View Article',               'arya-service-desk' ),
            'view_items'         => __( 'View Articles',              'arya-service-desk' ),
            'search_items'       => __( 'Search Articles',            'arya-service-desk' ),
            'not_found'          => __( 'No articles found',          'arya-service-desk' ),
            'not_found_in_trash' => __( 'No articles found in Trash', 'arya-service-desk' ),
            'all_items'          => __( 'All Articles',               'arya-service-desk' ),
            'item_published'     => __( 'Article published.',         'arya-service-desk' ),
            'item_updated'       => __( 'Article updated.',           'arya-service-desk' )
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
            'show_in_admin_bar'   => true,
            'show_in_rest'        => true,
            'menu_position'       => 40,
            'menu_icon'           => 'dashicons-media-default',
            'map_meta_cap'        => true,
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
            'name'               => __( 'FAQs',                   'arya-service-desk' ),
            'singular_name'      => __( 'FAQ',                    'arya-service-desk' ),
            'edit_item'          => __( 'Edit FAQ',               'arya-service-desk' ),
            'new_item'           => __( 'New FAQ',                'arya-service-desk' ),
            'view_item'          => __( 'View FAQ',               'arya-service-desk' ),
            'view_items'         => __( 'View FAQs',              'arya-service-desk' ),
            'search_items'       => __( 'Search FAQs',            'arya-service-desk' ),
            'not_found'          => __( 'No faqs found',          'arya-service-desk' ),
            'not_found_in_trash' => __( 'No faqs found in Trash', 'arya-service-desk' ),
            'all_items'          => __( 'All FAQs',               'arya-service-desk' ),
            'item_published'     => __( 'FAQ published.',         'arya-service-desk' ),
            'item_updated'       => __( 'FAQ updated.',           'arya-service-desk' )
        ];

        $faqs_args = [
            'label'               => __( 'FAQs', 'arya-service-desk' ),
            'labels'              => $faqs_labels,
            'public'              => true,
            'hierarchical'        => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => false,
            'show_in_admin_bar'   => true,
            'show_in_rest'        => true,
            'menu_position'       => 40,
            'menu_icon'           => 'dashicons-sos',
            'map_meta_cap'        => true,
            'supports'            => [ 'title', 'editor' ],
            'has_archive'         => true,
            'rewrite'             => [ 'slug' => 'faqs', 'with_front' => false ],
            'can_export'          => true,
            'delete_with_user'    => false
        ];
        register_post_type( 'service-desk-faq', $faqs_args );

        /**
         * Tickets
         */
        $tickets_labels = [
            'name'               => __( 'Tickets',                   'arya-service-desk' ),
            'singular_name'      => __( 'Ticket',                    'arya-service-desk' ),
            'edit_item'          => __( 'Edit Ticket',               'arya-service-desk' ),
            'new_item'           => __( 'New Ticket',                'arya-service-desk' ),
            'view_item'          => __( 'View Ticket',               'arya-service-desk' ),
            'view_items'         => __( 'View Tickets',              'arya-service-desk' ),
            'search_items'       => __( 'Search Tickets',            'arya-service-desk' ),
            'not_found'          => __( 'No tickets found',          'arya-service-desk' ),
            'not_found_in_trash' => __( 'No tickets found in Trash', 'arya-service-desk' ),
            'all_items'          => __( 'All Tickets',               'arya-service-desk' ),
            'item_published'     => __( 'Ticket published.',         'arya-service-desk' ),
            'item_updated'       => __( 'Ticket updated.',           'arya-service-desk' )
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
        /**
         * Articles
         */
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
     * Templating system.
     *
     * @since 1.0.0
     */
    public function themes()
    {
        Theme::newInstance();
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
