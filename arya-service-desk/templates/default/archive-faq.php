<?php
/**
 * The Template for displaying faq archives.
 *
 * This template can be overridden by copying it to your-theme/service-desk/archive-faq.php.
 *
 * @package Arya\ServiceDesk\Templates
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'service-desk' );

$args = [
    'post_type'   => 'service-desk-faq',
    'post_status' => 'publish',
    'nopaging'    => true
];
$query = new WP_Query( $args ); ?>

<?php do_action( 'service_desk_before_main_content' ); ?>

<header class="entry-header">
    <h1 class="entry-title"><?php esc_html_e( 'Frequently Asked Questions', 'service-desk' ); ?></h1>
</header>

<?php if( $query->have_posts() ) : ?>

<div id="questions" class="questions">

<?php while( $query->have_posts() ) : $query->the_post(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">

        <h3 itemprop="name"><?php the_title() ?></h3>

        <div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
            <div itemprop="text"><?php the_content() ?></div>
        </div>

    </article>

<?php endwhile; ?>

</div>

<?php else : ?>

    <p><?php esc_html_e( 'There are no frequently asked questions.', 'service-desk' ); ?></p>

<?php endif; ?>

<?php do_action( 'service_desk_after_main_content' ); ?>

<?php get_footer( 'service-desk' ); ?>
