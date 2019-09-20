<?php
/**
 * @package Arya\ServiceDesk\Admin
 */

namespace Arya\ServiceDesk\Admin;

/**
 * FAQ class.
 *
 * @since 1.0.0
 */
class FAQ
{
    /**
     * Singleton instance
     *
     * @since 1.0.0
     * @var FAQ
     */
    private static $instance;

    /**
     * Constructor.
     *
     * @link https://developer.wordpress.org/reference/hooks/manage_post_type_posts_columns/
     * @link https://developer.wordpress.org/reference/hooks/manage_post-post_type_posts_custom_column/
     *
     * @since 1.0.0
     */
    private function __construct()
    {
        /* Placeholders */
        add_filter( 'enter_title_here', [ $this, 'title' ], 10, 2 );
        add_filter( 'write_your_story', [ $this, 'body' ],  10, 2 );

        /* List table */
        add_filter( 'manage_service-desk-faq_posts_columns',       [ $this, 'columns' ], 15, 1 );
        add_action( 'manage_service-desk-faq_posts_custom_column', [ $this, 'answer' ],  10, 2 );
    }

    /**
     * The singleton method.
     *
     * @since 1.0.0
     *
     * @return FAQ
     */
    public static function newInstance(): FAQ
    {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new FAQ;
        }

        return self::$instance;
    }

    /**
     * Filters the title field placeholder text.
     *
     * @since 1.0.0
     */
    public function title( $text, $post )
    {
        if ( 'service-desk-faq' === $post->post_type ) {
            $text = __( 'Add Question', 'arya-service-desk' );
        }

        return $text;
    }

    /**
     * Filters the body placeholder text.
     *
     * @since 1.0.0
     */
    public function body( $text, $post )
    {
        if ( 'service-desk-faq' === $post->post_type ) {
            $text = __( 'Start writing an answer or type / to choose a block', 'arya-service-desk' );
        }

        return $text;
    }

    /**
     * Adds the answer column to the question list table.
     *
     * @since 1.0.0
     */
    public function columns( $columns )
    {
        $top    = array_slice( $columns, 0,    2, true );
        $bottom = array_slice( $columns, 2, null, true );

        $answer = [
            'answer' => __( 'Answer', 'arya-service-desk' )
        ];

        return $top + $answer + $bottom;;
    }

    /**
     * Displays the column data.
     *
     * @since 1.0.0
     */
    public function answer( $column_name, $post_id )
    {
        switch( $column_name ) {
            case 'answer':
                echo wp_kses_post( get_post_field( 'post_content', $post_id ) );
                break;
            default:
        }
    }
}
