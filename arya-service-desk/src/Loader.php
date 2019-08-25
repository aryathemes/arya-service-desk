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

        add_action( 'init', [ $this, 'registerTaxonomy' ] );

        add_action( 'init', [ $this, 'admin' ] );

        /* WordPress 5.1 */
        add_action( 'plugin_loaded', [ $this, 'plugin' ] );

        add_action( 'plugins_loaded', [ $this, 'plugins' ] );
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
            //'capabilities'        => $articles_capabilities,
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
        ];

        $faqs_args = [
            'label'               => __( 'FAQs', 'knowledge-base' ),
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
            //'capabilities'        => $faqs_capabilities,
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

        $ticket_capabilities = [
        ];

        $ticket_args = [
            'label'               => __( 'Tickets', 'knowledge-base' ),
            'labels'              => $tickets_labels,
            'public'              => true,
            'hierarchical'        => false,
            'exclude_from_search' => false,
            'publicly_queryable'  => false,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => false,
            'show_in_admin_bar'   => true,
            'show_in_rest'        => false,
            'menu_position'       => 40,
            'menu_icon'           => 'dashicons-email',
            //'capabilities'        => $tickets_capabilities,
            'supports'            => [ 'title', 'editor' ],
            'has_archive'         => false,
            'can_export'          => true,
            'delete_with_user'    => false
        ];
        register_post_type( 'service-desk-ticket', $ticket_args );
    }

    public function registerTaxonomy()
    {
        $cat_labels = [
            'name'          => __( 'Categories', 'arya-service-desk' ),
            'singular_name' => __( 'Category', 'arya-service-desk' )
        ];

        $cat_args = [
            'labels'             => $cat_labels,
            'public'             => true,
            'publicly_queryable' => true,
            'hierarchical'       => false,
            'show_in_rest'       => true,
            'show_tagcloud'      => false
        ];
        register_taxonomy( 'service-desk-article-category', [ 'knowledge-base' ], $cat_args );

        $args = [
            'public'             => true,
            'publicly_queryable' => true,
            'hierarchical'       => false,
            'show_in_rest'       => true,
            'show_tagcloud'      => true
        ];
        register_taxonomy( 'service-desk-article-tag', [ 'knowledge-base' ], $args );
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
     * Fires once activated plugin have loaded.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function plugin()
    {
    }

    /**
     * Fires once activated plugins have loaded.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function plugins()
    {
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
