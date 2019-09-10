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

        add_filter( 'language_attributes', [ $this, 'itemscope' ], 10, 2 );
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
                $template = plugin_dir_path( ARYA_SERVICE_DESK_FILE ) . "templates/{$templates[0]}";
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

        if ( is_post_type_archive( 'service-desk-faq' ) ) {
            $templates[] = 'archive-faq.php';
        }

        return $templates;
    }

    /**
     * Retrieves the template path.
     *
     * @since 1.0.0
     */
    private function getTemplatePath()
    {
        return 'service-desk';
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
}
