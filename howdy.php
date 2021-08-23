<?php
/**
 * Plugin Name: Howdy
 * Description: Remove "Howdy" from the admin bar.
 * Version:     1.0.0
 * Author:      Brad Parbs
 * Author URI:  https://bradparbs.com/
 * License:     GPLv2
 * Text Domain: howdy
 * Domain Path: /lang/
 *
 * @package howdy
 */

namespace Howdy;

add_filter( 'admin_bar_menu', function( $bar ) {
	if ( is_admin_bar_showing() ) {
		$bar->add_node( [
			'id'    => 'my-account',
			'title' => wp_get_current_user()->display_name,
		]);

		$bar->add_node(
			[
				'parent' => 'user-actions',
				'id'     => 'user-info',
				'title'  => sprintf( "<span class='display-name'>%s</span>", wp_get_current_user()->display_name ),
				'href'   => get_dashboard_url( get_current_user_id(), 'profile.php' ),
				'meta'   => [ 'tabindex' => -1 ],
			]
		);

	}

	return $bar;
}, 999 );

add_action( 'admin_enqueue_scripts', function() {
	if ( is_admin_bar_showing() ) {
		wp_add_inline_style( 'admin-bar', "#wpadminbar #wp-admin-bar-user-actions > li { margin-left: 16px !important; }" );
	}
});
