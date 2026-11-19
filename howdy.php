<?php
/**
 * Plugin Name:       Howdy
 * Description:       Remove "Howdy" from the admin bar.
 * Version:           1.0.1
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Brad Parbs
 * Author URI:        https://bradparbs.com/
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       howdy
 *
 * @package howdy
 */

namespace Howdy;

defined( 'ABSPATH' ) || exit;

// Core adds the "Howdy, name" my-account node at priority 9991, so this needs to run after it.
add_action(
	'admin_bar_menu',
	function ( $bar ) {
		if ( ! is_user_logged_in() ) {
			return;
		}

		$name    = esc_html( wp_get_current_user()->display_name );
		$account = $bar->get_node( 'my-account' );

		if ( $account ) {
			$bar->add_node(
				[
					'id'    => 'my-account',
					'title' => $name,
					// Core also uses "Howdy, name" as the submenu's screen reader label.
					'meta'  => array_merge( (array) $account->meta, [ 'menu_title' => $name ] ),
				]
			);
		}

		$bar->add_node(
			[
				'parent' => 'user-actions',
				'id'     => 'user-info',
				'title'  => sprintf( "<span class='display-name'>%s</span>", $name ),
				'href'   => get_edit_profile_url(),
				'meta'   => [ 'tabindex' => -1 ],
			]
		);
	},
	9999
);

add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\add_styles' );
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\add_styles' );

/**
 * Adjust spacing now that the avatar is gone.
 */
function add_styles() {
	if ( is_admin_bar_showing() ) {
		wp_add_inline_style( 'admin-bar', '#wpadminbar #wp-admin-bar-user-actions > li { margin-left: 16px !important; }' );
	}
}
