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

function em_block_posts_grid_content( $attributes ) {
	$args = array(
		'posts_per_page'   => $attributes['postsToShow'],
		'post_status'      => 'publish',
		'order'            => $attributes['order'],
		'orderby'          => $attributes['orderBy'],
		'suppress_filters' => false,
	);

	if ( isset( $attributes['categories'] ) ) {
		$args['category'] = $attributes['categories'];
	}

	$recent_posts = get_posts( $args );

	$list_items_markup = '';

	$excerpt_length = $attributes['excerptLength'];

	foreach ( $recent_posts as $post ) {
		$title = get_the_title( $post );
		if ( ! $title ) {
			$title = __( '(no title)' );
		}
		$list_items_markup .= sprintf(
			'<li><a href="%1$s">%2$s</a>',
			esc_url( get_permalink( $post ) ),
			$title
		);

		if ( isset( $attributes['displayPostDate'] ) && $attributes['displayPostDate'] ) {
			$list_items_markup .= sprintf(
				'<time datetime="%1$s" class="wp-block-latest-posts__post-date">%2$s</time>',
				esc_attr( get_the_date( 'c', $post ) ),
				esc_html( get_the_date( '', $post ) )
			);
		}

		if ( isset( $attributes['displayPostContent'] ) && $attributes['displayPostContent']
			&& isset( $attributes['displayPostContentRadio'] ) && 'excerpt' === $attributes['displayPostContentRadio'] ) {
			$post_excerpt = $post->post_excerpt;
			if ( ! ( $post_excerpt ) ) {
				$post_excerpt = $post->post_content;
			}
			$trimmed_excerpt = esc_html( wp_trim_words( $post_excerpt, $excerpt_length, ' &hellip; ' ) );

			$list_items_markup .= sprintf(
				'<div class="wp-block-latest-posts__post-excerpt">%1$s',
				$trimmed_excerpt
			);

			if ( strpos( $trimmed_excerpt, ' &hellip; ' ) !== false ) {
				$list_items_markup .= sprintf(
					'<a href="%1$s">%2$s</a></div>',
					esc_url( get_permalink( $post ) ),
					__( 'Read more' )
				);
			} else {
				$list_items_markup .= sprintf(
					'</div>'
				);
			}
		}

		if ( isset( $attributes['displayPostContent'] ) && $attributes['displayPostContent']
			&& isset( $attributes['displayPostContentRadio'] ) && 'full_post' === $attributes['displayPostContentRadio'] ) {
			$list_items_markup .= sprintf(
				'<div class="wp-block-latest-posts__post-full-content">%1$s</div>',
				wp_kses_post( html_entity_decode( $post->post_content, ENT_QUOTES, get_option( 'blog_charset' ) ) )
			);
		}

		$list_items_markup .= "</li>\n";
	}

	$class = 'wp-block-latest-posts wp-block-latest-posts__list';
	if ( isset( $attributes['align'] ) ) {
		$class .= ' align' . $attributes['align'];
	}

	if ( isset( $attributes['postLayout'] ) && 'grid' === $attributes['postLayout'] ) {
		$class .= ' is-grid';
	}

	if ( isset( $attributes['columns'] ) && 'grid' === $attributes['postLayout'] ) {
		$class .= ' columns-' . $attributes['columns'];
	}

	if ( isset( $attributes['displayPostDate'] ) && $attributes['displayPostDate'] ) {
		$class .= ' has-dates';
	}

	if ( isset( $attributes['className'] ) ) {
		$class .= ' ' . $attributes['className'];
	}

	return sprintf(
		'<ul class="%1$s">%2$s</ul>',
		esc_attr( $class ),
		$list_items_markup
	);	      
	
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function em_block_posts_grid_em_block_posts_grid_block_init() {
	register_block_type( __DIR__ . '/build', array(
		'render_callback' => 'em_block_posts_grid_content'
	) );
}
add_action( 'init', 'em_block_posts_grid_em_block_posts_grid_block_init' );
