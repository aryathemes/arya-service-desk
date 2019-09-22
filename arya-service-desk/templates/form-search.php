<?php
/**
 * The Template for displaying search form.
 *
 * This template can be overridden by copying it to your-theme/service-desk/form-search.php.
 *
 * @package Arya\ServiceDesk\Templates
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit; ?>

<div class="search-container">
    <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	    <label for="search-form-documentation">
	    	<span class="screen-reader-text">Search for:</span>
	    </label>
	    <input type="hidden" name="post_type[]" value="service-desk-article" />
	    <input type="search" id="search-form-documentation" class="search-field" placeholder="Search &hellip;" value="" name="s" />
	    <button type="submit" class="search-submit"><span class="screen-reader-text">Search</span></button>
    </form>
</div>
