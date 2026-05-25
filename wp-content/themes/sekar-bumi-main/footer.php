<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package sekarbumi
 */

?>
	<footer id="footer" class="site-footer">
		<div class="footer-wrapper uk-container-expand uk-padding-large">
			<div class="footer-section-1">
				<div class="uk-grid-small" data-uk-grid>
					<div class="uk-width-expand@m">
            <div class="footer-section-1__wrapper uk-child-width-1-2@s uk-child-width-auto@m uk-grid-small" data-uk-grid>
              <div class="footer-section-1__widgets">
                <div class="uk-text-center uk-text-left@m">
                  <ul class="uk-padding-remove uk-margin-remove">	
                    <?php
                      if( is_active_sidebar( 'footer-1' ) ){
                        dynamic_sidebar( 'footer-1' );
                      }
                    ?>
                  </ul>
                </div>
              </div>
              <div class="footer-section-1__widgets">
                <div class="uk-text-center uk-text-left@m">
                  <ul class="uk-padding-remove uk-margin-remove">	
                    <?php
                      if( is_active_sidebar( 'footer-2' ) ){
                        dynamic_sidebar( 'footer-2' );
                      }
                    ?>
                  </ul>
                </div>
              </div>
              <div class="footer-section-1__widgets">
                <div class="uk-text-center uk-text-left@m">
                  <ul class="uk-padding-remove uk-margin-remove">	
                    <?php
                      if( is_active_sidebar( 'footer-3' ) ){
                        dynamic_sidebar( 'footer-3' );
                      }
                    ?>
                  </ul>
                </div>
              </div>
              <div class="footer-section-1__widgets">
                <div class="uk-text-center uk-text-left@m">
                  <ul class="uk-padding-remove uk-margin-remove">	
                    <?php
                      if( is_active_sidebar( 'footer-4' ) ){
                        dynamic_sidebar( 'footer-4' );
                      }
                    ?>
                  </ul>
                </div>
              </div>
              <div class="footer-section-1__widgets">
                <div class="uk-text-center uk-text-left@m">
                  <ul class="uk-padding-remove uk-margin-remove">	
                    <?php
                      if( is_active_sidebar( 'footer-5' ) ){
                        dynamic_sidebar( 'footer-5' );
                      }
                    ?>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <div class="custom-footer-width">
						<?php do_action( 'sekarbumi_footer_logo' ); ?>
            <?php
              if( is_active_sidebar( 'footer-2-1' ) ){
                dynamic_sidebar( 'footer-2-1' );
              }
            ?>
					</div>
				</div>
			</div><!-- .footer-section-1 -->
      <div class="footer-section-2">
          <div class="uk-grid-small" data-uk-grid>
            <div class="uk-width-expand@m">
              <?php do_action('sekarbumi_copyright'); ?>
            </div>
            <ul class="custom-footer-width uk-padding-remove uk-margin-remove uk-flex">
              <?php
                if( is_active_sidebar( 'footer-2-2' ) ){
                  dynamic_sidebar( 'footer-2-2' );
                }
              ?>
            </ul>
          </div>
      </div><!-- .footer-section-2 -->
		</div>
	</footer><!-- #footer -->
</div><!-- #page -->
<div id="off-canvas" class="off-canvas-wrapper" uk-offcanvas>
  <div class="uk-offcanvas-bar">
    <a href="#" class="uk-offcanvas-close" data-uk-icon="icon: close; ratio: 1.5;">
    </a>
    <?php
      wp_nav_menu(
        array(
          'theme_location' 	=> 'side_menu',
          'items_wrap'		  => '<ul id="%1$s" class="%2$s uk-nav-default uk-nav-parent-icon" data-uk-nav>%3$s</ul>',
          'walker'			    => new Sekarbumi_Side_Menu(),
          'menu_id'        	=> 'side-menu',
        )
      );
      echo do_shortcode( '[sekarbumi_widget_social_account icon_ratio="1.2"]' );
    ?>
  </div>
</div>
<?php wp_footer(); ?>
</body>
</html>