<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package sekarbumi
 */
?>

<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<header id="masthead" class="site-header uk-box-shadow-medium" data-uk-sticky="animation: uk-animation-slide-top; top: 50vh; offset: 0;">
		<div class="uk-container-expand header-wrapper">
			<div class="uk-flex uk-flex-middle">
				<div class="site-branding uk-flex uk-flex-middle">
					<?php do_action( 'sekarbumi_logo' ); ?>
				</div><!-- .site-branding -->
        <div class="menu-wrapper uk-navbar-container uk-navbar-transparent uk-visible@m" data-uk-navbar="offset: 50;">
          <?php
            wp_nav_menu(
              array(
                'theme_location' 	=> 'primary',
                'items_wrap'		  => '<ul id="%1$s" class="%2$s uk-nav-parent-icon uk-flex" data-uk-nav>%3$s</ul>',
                'walker'			     => new Sekarbumi_Walker_Menu(),
                'menu_id'        	=> 'primary-menu',
              )
            );
          ?>
        </div>
        <div class="mobile-nav uk-hidden@m">
          <a href="#off-canvas" uk-toggle>
            <span uk-icon="icon: menu; ratio: 2"></span>
          </a>
        </div>
			</div>
		</div>
	</header><!-- #masthead -->