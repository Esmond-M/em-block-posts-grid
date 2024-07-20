<?php
/**
 * Plugin Name:       EM Posts Grid
 * Description:       Show posts in various formats
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            Esmond Mccain
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       em-block-posts-grid
 *
 * @package EmBlockPostsGrid
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function em_block_posts_grid_em_block_posts_grid_block_init() {
	register_block_type( __DIR__ . '/build' );
}
add_action( 'init', 'em_block_posts_grid_em_block_posts_grid_block_init' );
