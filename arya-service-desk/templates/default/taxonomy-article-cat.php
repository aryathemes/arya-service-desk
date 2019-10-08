<?php
/**
 * The Template for displaying article archives.
 *
 * This template can be overridden by copying it to your-theme/service-desk/archive-article.php.
 *
 * @package Arya\ServiceDesk\Templates
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'service-desk' );

do_action( 'service_desk_before_main_content' );

$args = [
    'post_type'   => 'service-desk-article',
    'post_status' => 'publish',
    'taxonomy'    => 'service-desk-article-cat'
];
$query = new WP_Query( $args ); ?>

<?php if( $query->have_posts() ) : ?>

    <?php while( $query->have_posts() ) : $query->the_post(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

        <header class="entry-header">
            <?php the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' ); ?>
        </header>

        <div class="entry-content">
            <?php the_excerpt() ?>
        </div>

    </article>

    <?php endwhile; ?>

<?php else : ?>

    <p><?php esc_html_e( 'There are no articles.', 'service-desk' ); ?></p>

<?php endif; ?>

<?php do_action( 'service_desk_after_main_content' ); ?>

<?php get_footer( 'service-desk' ); ?>
