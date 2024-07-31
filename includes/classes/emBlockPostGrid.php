<?php

/**
* Main plugin file.
* PHP version 7.3

* @category Wordpress_Plugin
* @package  Esmond-M
* @author   Esmond Mccain <esmondmccain@gmail.com>
* @license  https://www.gnu.org/licenses/gpl-3.0.en.html GNU General Public License
* @link     esmondmccain.com
* @return
*/

declare(strict_types=1);
namespace emBlockPostGrid;

if (!class_exists('emBlockPostGrid')) {
/**
* Declaring class

* @category Wordpress_Plugin
* @package  Esmond-M
* @author   Esmond Mccain <esmondmccain@gmail.com>
* @license  https://www.gnu.org/licenses/gpl-3.0.en.html GNU General Public License
* @link     esmondmccain.com
* @return
*/

    class emBlockPostGrid
{
    //begin class


    /**
    *  Declaring constructor
    */
    public function __construct()
    {
        add_action(
            'init',
            [$this, 'em_block_posts_grid_em_block_posts_grid_block_init']
        );
    }
          
    public function em_block_posts_grid_content( $attributes ) {
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
    public function em_block_posts_grid_em_block_posts_grid_block_init() {
        register_block_type(WP_PLUGIN_DIR . '/em-block-posts-grid/build', array(
            'render_callback' =>  [$this, 'em_block_posts_grid_content' ] 
        ) );
    }
   
}
}