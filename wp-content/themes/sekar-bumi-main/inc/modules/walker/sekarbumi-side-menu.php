<?php
/**
	 * Custom Walker
	 * Class Sekarbumi_Side_Menu
	 *
	 * @access      public
	 * @since       1.0.0
	 * @return      void
	*/
if( !class_exists( 'Sekarbumi_Side_Menu' ) ){

	class Sekarbumi_Side_Menu extends Walker_Nav_Menu {

		public $text_alignment_level_0 = '';

		public $_output = '';

		public $anchor_id = "";
		
		public $anchor_id_counter = 1;

		public $link_back =  '';

    
		/**
		 * start_lvl
		 * @param &$output, $depth, $args
		 * @return
		 */
		function start_lvl( &$output, $depth = 0, $args = array() ) {
			// depth dependent classes
			$indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' ); // code indent

			$display_depth = ( $depth + 1); // because it counts the first submenu as 0

			$classes = array(
				'uk-nav-sub',
				( $display_depth % 2  ? 'menu-odd' : 'menu-even' ),
				( $display_depth >=2 ? 'sub-sub-menu' : '' ),
				'cf-mobile-nav__depth-' . $display_depth,
				'uk-nav-default'
			);
			// megamenu condition
			$class_names = implode( ' ', $classes );
			
			$output .= "\n" . $indent . '<ul class="' . $class_names . '" data-uk-nav="multiple:false;">' . "\n";
		}
		/**
		* end_lvl
		* @param &$output, $depth, $args
		* @return
		*/
		function end_lvl( &$output, $depth = 0, $args = array() ) {

			if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
				$t = '';
				$n = '';
			} else {
				$t = "\t";
				$n = "\n";
			}

			$indent = str_repeat( $t, $depth );
			
			$output .= "$indent</ul>{$n}";

		}
		/**
		 * start_el
		 * @param &$output, $item, $depth, $args, $id
		 */
		function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
			global $wp_query;

			$walkerObj = $args->walker;

			$has_children_item_menu = $walkerObj->has_children;

			if( $depth === 0 ){
				$this->anchor_id_counter++;
				$this->anchor_id = 'secondary-menu-' . uniqid() . '-' . $this->anchor_id_counter;
			}

			$indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); // code indent
			// depth dependent classes
			$depth_classes = array(
				'menu-item-depth-' . $depth,
				( $depth == 0 ? 'main-menu-item' : 'sub-menu-item' ),
				( $depth >= 2 ? 'sub-sub-menu-item' : '' ),
				( $depth  % 2 ? 'menu-item-odd' : 'menu-item-even' ),
				( $has_children_item_menu ? 'uk-parent' : '' ),
			);

			$depth_class_names = esc_attr( implode( ' ', $depth_classes ) );
			// passed classes
			$classes = empty( $item->classes ) ? array() : (array) $item->classes;

			$class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );
			
			$output .= $indent . '<li id="nav-menu-item-'. $item->ID . '" class="' . $depth_class_names . ' ' . $class_names . '">';
			// link attributes
			$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
			$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
			$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
			$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
			$attributes .= ' class="menu-link ' . ( $depth > 0 ? 'sub-menu-link' : 'main-menu-link' ) . ( $depth ===1 ? ' ' : '' ) . '"';

			$item_output = sprintf(
				'
					%1$s
					<a%2$s>
						%3$s%4$s%5$s
					</a>%6$s
				',
				$args->before,
				$attributes,
				$args->link_before,
				apply_filters( 'the_title', $item->title, $item->ID ),
				$args->link_after,
				$args->after
			);
			
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
		/**
		* end_lvl
		* @param &$output, $depth, $args
		* @return
		*/
		function end_el( &$output, $item, $depth = 0, $args = array() ) {

			if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
				$t = '';
				$n = '';
			} else {
				$t = "\t";
				$n = "\n";
			}
			
			$output .= "</li>{$n}";

		}

	}

}