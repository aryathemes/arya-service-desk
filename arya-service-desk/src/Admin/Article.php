<?php
/**
 * @package Arya\ServiceDesk\Admin
 */

namespace Arya\ServiceDesk\Admin;

/**
 * Article class.
 *
 * @since 1.0.0
 */
class Article
{
    /**
     * Singleton instance
     *
     * @since 1.0.0
     * @var Article
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
     * @return Article
     */
    public static function newInstance(): Article
    {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new Article;
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
        if ( 'service-desk-article' === $post->post_type ) {
            $text = __( 'Add Article', 'arya-service-desk' );
        }

        return $text;
    }
}
