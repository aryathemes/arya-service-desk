<?php
/**
 * The Template for displaying search form.
 *
 * This template can be overridden by copying it to your-theme/service-desk/form-ticket.php.
 *
 * @package Arya\ServiceDesk\Templates
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit; ?>

<div class="service-desk-ticket">

    <form id="new-ticket" method="POST" action="<?php the_permalink(); ?>">

        <div>
            <label for="subject"><?php esc_html_e( 'Subject:', 'arya-service-desk' ); ?></label>
            <input type="text" name="subject" id="subject">
        </div>

        <div>
            <label for="message"><?php esc_html_e( 'Message:', 'arya-service-desk' ); ?></label>
            <textarea name="message" id="message" rows="5" cols="50"></textarea>
        </div>

        <?php wp_nonce_field( 'service-desk-new-ticket' ); ?>
	    <input type="hidden" name="action" value="new-ticket" />

	    <button type="submit" class="ticket-submit"><?php _e( 'Submit', 'arya-service-desk' ); ?></button>

    </form>

</div>
