<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package sekarbumi
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function sekarbumi_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'sekarbumi_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function sekarbumi_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'sekarbumi_pingback_header' );

/**
 * Get Post Excerpt
 */
if( !function_exists( 'sekarbumi_post_excerpt' ) ){
	function sekarbumi_post_excerpt( $length = 20, $more = '...', $is_echo = true ){
		global $post;
		
		$excerpt = get_the_excerpt( $post->ID );

		$trimed_excerpt = wp_trim_words( $excerpt, $length, $more );

		if( $is_echo ){
			echo apply_filters( 'sekarbumi_post_excerpt', $trimed_excerpt );
		} else {
			return apply_filters( 'sekarbumi_post_excerpt', $trimed_excerpt );
		}
	}
}

/**
 * Archive Pagination
 */
if( !function_exists( 'sekarbumi_pagination' ) ){
	function sekarbumi_pagination() {
	
		if( is_singular() ){return;}
	
		global $wp_query;
	
		/** Stop execution if there's only 1 page */
		if( $wp_query->max_num_pages <= 1 ){
			return;
		}
	
		$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
		$max   = intval( $wp_query->max_num_pages );
	
		/** Add current page to the array */
		if ( $paged >= 1 )
			$links[] = $paged;
	
		/** Add the pages around the urrent page to the array */
		if ( $paged >= 3 ) {
			$links[] = $paged - 1;
			$links[] = $paged - 2;
		}
	
		if ( ( $paged + 2 ) <= $max ) {
			$links[] = $paged + 2;
			$links[] = $paged + 1;
		}
	
		echo '<div class="cf-pagination uk-margin-medium-top"><ul class="uk-pagination uk-flex-center">' . "\n";
	
		/** Previous Post Link */
		if ( get_previous_posts_link() ){
			printf( '<li>%s</li>' . "\n", get_previous_posts_link( '<span data-uk-pagination-previous></span>' ) );
		}
	
		/** Link to first page, plus ellipses if necessary */
		if ( ! in_array( 1, $links ) ) {
			$class = 1 == $paged ? ' class="active"' : '';
	
			printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );
	
			if ( ! in_array( 2, $links ) ){
				echo '<li>…</li>';
			}
		}
	
		/** Link to current page, plus 2 pages in either direction if necessary */
		sort( $links );
		foreach ( (array) $links as $link ) {
			$class = $paged == $link ? ' class="active"' : '';
			printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
		}
	
		/** Link to last page, plus ellipses if necessary */
		if ( ! in_array( $max, $links ) ) {
			if ( ! in_array( $max - 1, $links ) ){
				echo '<li>…</li>' . "\n";
			}
	
			$class = $paged == $max ? ' class="active"' : '';
			printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
		}
	
		/** Next Post Link */
		if ( get_next_posts_link() ){
			printf( '<li>%s</li>' . "\n", get_next_posts_link('<span data-uk-pagination-next></span>') );
		}
	
		echo '</ul></div>' . "\n";
	
	}
}

/**
 * Post Pagination
 */
if( !function_exists( 'sekarbumi_post_pagination' ) ){
	function sekarbumi_post_pagination(){
		$prev_post = get_adjacent_post( false, '', true );
		$next_post = get_adjacent_post( false, '', false );

		$post_pagination = '<div class="cf-post-pagination">';

		if( !empty( $prev_post ) ){
			$post_pagination .= sprintf(
				'<a href="%1$s" class="cf-post-prev"><i data-uk-icon="icon: chevron-left; ratio:0.8;"></i>Previous Post</a>',
				esc_url( get_the_permalink( $prev_post->ID ) )
			);
		}

		if( !empty( $next_post ) ){
			$post_pagination .= sprintf(
				'<a href="%1$s" class="cf-post-next">Next Post<i data-uk-icon="icon: chevron-right; ratio:0.8;"></i></a>',
				esc_url( get_the_permalink( $next_post->ID ) )
			);
		}

		$post_pagination .= '</div>';
		
		echo apply_filters( 'sekarbumi_post_pagination', $post_pagination );
	}
}

/**
 * Single Post social button
 */
if( !function_exists( 'sekarbumi_social_button' ) ){
	function sekarbumi_social_button() {
		global $post;
		if( is_singular() ){
		
			// Get current page URL 
			$sb_url = urlencode(get_permalink());
	
			// Get current page title
			$sb_title = str_replace( ' ', '%20', get_the_title());
	
			// Construct sharing URL without using any script
			$twitterURL = 'https://twitter.com/intent/tweet?text='.$sb_title.'&amp;url='.$sb_url.'&amp;';
			$facebookURL = 'https://www.facebook.com/sharer/sharer.php?u='.$sb_url;
	
			// Add sharing button at the end of page/page content
			$content = '<div class="cf-social-share"><span>'. __( 'SHARE CERITA INI:', 'sekarbumi' ) .'</span><div class="cf-social-btn">';
			$content .= '<a class="share-popup" href="'. esc_url( $facebookURL ) .'" target="_blank" rel="noopener" alt="Share on Facebook"><i class="uk-icon-button" data-uk-icon="icon: facebook; ratio: 1;"></i></a>';
			$content .= '<a class="share-popup" href="'. esc_url( $twitterURL ) .'" target="_blank" rel="noopener" alt="Share on Twitter"><i class="uk-icon-button" data-uk-icon="icon: twitter; ratio: 1;"></i></a>';
			$content .= '</div></div>';
			
			echo apply_filters( 'sekarbumi_social_button', $content );
		}else{
			// if not a post/page then don't include sharing button
			echo apply_filters( 'sekarbumi_social_button', $content );
		}
	}
	add_action( 'sekarbumi_social_share', 'sekarbumi_social_button' );
}

/**
 * Header Meta Tags for Facebook and Twitter
 */
if( !function_exists( 'sekarbumi_header_tags' ) ){
	function sekarbumi_header_tags(){
		global $post;
		if( !is_singular( 'kisah-sukses') && !is_singular( 'berita' ) ){
			return;
		}
		
		$post_url			= urlencode( get_permalink() );
		$post_description 	= sekarbumi_post_excerpt( 30, '...', false );

		if( !has_post_thumbnail( $post->ID ) ){
			// the post does not have featured image, use a default image
			$default_image 		= get_theme_mod( 'sekarbumi_site_logo' );
			$meta_tags_image 	= esc_url( $default_image );
		} else {
			$thumbnail_src 		= wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
			$meta_tags_image 	= esc_url( $thumbnail_src[0] );
		} 

		$meta_tags = sprintf(
			'
			<!–– For Facebook ––>
			<meta property="og:title" content="%1$s" />
			<meta property="og:description" content="%2$s" />
			<meta property="og:image" content="%3$s"/>
			<meta property="og:image:width" content="1200" />
			<meta property="og:image:height" content="628" />

			<!–– For Twitter ––>
			<meta name="twitter:card" content="summary_large_image" />
			<meta name="twitter:label1" content="Est. reading time" />
			<meta name="twitter:data1" content="15 minutes" />
			',
			esc_html( $post->post_title ),
			esc_html( $post_description ),
			$meta_tags_image,
			esc_url( $post_url )
		);

		echo apply_filters( 'sekarbumi_header_tags', $meta_tags );
	}
	
	add_action( 'wp_head', 'sekarbumi_header_tags' );
}