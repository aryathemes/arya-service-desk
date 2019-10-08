<?php
/**
 * @package Arya\ServiceDesk
 */

namespace Arya\ServiceDesk;

/**
 * Theme class.
 *
 * @since 1.0.0
 */
class Theme
{
    /**
     * Singleton instance
     *
     * @since 1.0.0
     * @var Theme
     */
    private static $instance;

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    private function __construct()
    {
        add_filter( 'template_include', [ $this, 'templates' ], 10, 1 );

        add_filter( 'post_type_link', [ $this, 'postTypeLink' ], 10, 2 );

        add_filter( 'language_attributes', [ $this, 'itemscope' ], 10, 2 );

        $themes = [
            'arya',
            'twentynineteen',
            'twentyseventeen'
        ];

        if ( in_array( $theme = wp_get_theme()->get( 'TextDomain' ), $themes ) ) {
            add_action( 'init', [ $this, $theme ] );
        } else {
            add_action( 'init', [ $this, 'theme' ] );
        }

        add_action( 'service_desk_before_main_content', [ $this, 'search' ], 15 );

        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );
    }

    /**
     * The singleton method.
     *
     * @since 1.0.0
     *
     * @return Theme
     */
    public static function newInstance(): Theme
    {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new Theme;
        }

        return self::$instance;
    }

    /**
     * Adds new templates.
     *
     * @since 1.0.0
     */
    public function templates( $template )
    {
        if ( is_embed() ) {
            return $template;
        }

        $templates = $this->getDefaultTemplates();

        if ( ! empty( $templates ) ) {

            $template = locate_template( $templates );

            if ( empty( $template ) ) {
                $template = plugin_dir_path( ARYA_SERVICE_DESK_FILE ) . "templates/default/{$templates[0]}";
            }
        }

        return $template;
    }

    /**
     * Retrieves default templates.
     *
     * @since 1.0.0
     */
    private function getDefaultTemplates()
    {
        $templates = [];

        if ( is_post_type_archive( 'service-desk-article' ) ) {
            $templates[] = 'archive-article.php';
            $templates[] = 'service-desk/archive-article.php';
        }

        if ( is_tax( 'service-desk-article-cat' ) ) {
            $templates[] = 'taxonomy-article-cat.php';
            $templates[] = 'service-desk/taxonomy-article-cat.php';
        }

        if ( is_post_type_archive( 'service-desk-faq' ) ) {
            $templates[] = 'archive-faq.php';
            $templates[] = 'service-desk/archive-faq.php';
        }

        return $templates;
    }

    /**
     * Filters the permalink of an article.
     *
     * @since 1.0.0
     */
    public function postTypeLink( $post_link, $post )
    {
        if ( false !== strpos( $post_link, '%service-desk-article-cat%' ) ) {

            $taxonomy_terms = get_the_terms( $post->ID, 'service-desk-article-cat' );

            foreach ( $taxonomy_terms as $term ) {
                if ( ! $term->parent ) {
                    $post_link = str_replace( '%service-desk-article-cat%', $term->slug, $post_link );
                }
            }
        }

        return $post_link;
    }

    /**
     * Defines Frequently Asked Questions (FAQ) document.
     *
     * @link https://schema.org/FAQPage
     *
     * @since 1.0.0
     */
    public function itemscope( $output, $doctype )
    {
        if ( is_post_type_archive( 'service-desk-faq' ) ) {
            return "{$output} itemscope itemtype=\"https://schema.org/FAQPage\"";
        }

        return $output;
    }

    /**
     * Arya theme
     *
     * @since 1.0.0
     */
    public function arya()
    {
        add_filter( 'is_active_sidebar', function( $is_active_sidebar, $index )
        {
            if ( is_post_type_archive( 'service-desk-faq' ) ) {
                return ! in_array( $index, [ 'primary', 'secondary' ] );
            }

            return $is_active_sidebar;
        }, 10, 2 );

        add_action( 'service_desk_before_main_content', function()
        {
            echo '<main id="main" class="main">';
        });

        add_action( 'service_desk_after_main_content', function()
        {
            echo '</main>';
        });

        add_action( 'wp_enqueue_scripts', function()
        {
            wp_enqueue_style( 'service-desk-arya',
                plugins_url( "static/css/arya.css", ARYA_SERVICE_DESK_FILE ),
                [],
                null
            );
        });
    }

    /**
     * Twenty Nineteen theme
     *
     * @since 1.0.0
     */
    public function twentynineteen()
    {
        add_action( 'service_desk_before_main_content', function()
        {
            echo '<section id="primary" class="content-area"><main id="main" class="site-main">';
        });

        add_action( 'service_desk_after_main_content', function()
        {
            echo '</main></section>';
        });

        add_action( 'wp_enqueue_scripts', function()
        {
            wp_enqueue_style( 'service-desk-twentynineteen',
                plugins_url( "static/css/twentynineteen.css", ARYA_SERVICE_DESK_FILE ),
                [],
                null
            );
        });
    }

    /**
     * Twenty Seventeen theme
     *
     * @since 1.0.0
     */
    public function twentyseventeen()
    {
        add_action( 'service_desk_before_main_content', function()
        {
            echo '<div class="wrap">';
        });

        add_action( 'service_desk_after_main_content', function()
        {
            echo '</div>';
        });

        add_action( 'wp_enqueue_scripts', function()
        {
            wp_enqueue_style( 'service-desk-twentyseventeen',
                plugins_url( "static/css/twentyseventeen.css", ARYA_SERVICE_DESK_FILE ),
                [],
                null
            );
        });
    }

    /**
     * General theme.
     *
     * @since 1.0.0
     */
    public function theme()
    {
        add_action( 'service_desk_before_main_content', function()
        {
            echo '<div id="arya-service-desk">';
        });

        add_action( 'service_desk_after_main_content', function()
        {
            echo '</div>';
        });

        add_action( 'wp_enqueue_scripts', function()
        {
            wp_enqueue_style( 'service-desk-theme',
                plugins_url( "static/css/style.css", ARYA_SERVICE_DESK_FILE ),
                [],
                null
            );
        });
    }

    /**
     * Displays the search form.
     *
     * @since 1.0.0
     */
    public function search()
    {
        $templates = [
            'service-desk/form-search.php'
        ];

        $template = locate_template( $templates, false );

        if ( empty( $template ) ) {
            $template = plugin_dir_path( ARYA_SERVICE_DESK_FILE ) . 'templates/default/form-search.php';
        }

        load_template( $template, true );
    }

    /**
     * Enqueuing scripts.
     *
     * @since 1.0.0
     */
    public function enqueue()
    {
        wp_enqueue_script( 'service-desk-faqs',
            plugins_url( "static/js/service-desk-faqs.js", ARYA_SERVICE_DESK_FILE ),
            [ 'jquery', 'jquery-ui-accordion' ],
            null,
            true
        );
    }
}
