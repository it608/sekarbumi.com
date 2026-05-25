<?php

/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package sekarbumi
 */

get_header();
?>

<main id="primary" class="site-main">
  <div class="archive-wrapper">
    <?php if (have_posts()) : ?>

      <?php do_action('skbm_before_content'); ?>

      <div class="general-meeting uk-child-width-1-2@m uk-grid-small" data-uk-grid>
      <?php
      /* Start the Loop */
      while (have_posts()) :
        the_post();

        /*
              * Include the Post-Type-specific template for the content.
              * If you want to override this in a child theme, then include a file
              * called content-___.php (where ___ is the Post Type name) and that will be used instead.
              */
        get_template_part('template-parts/archive/content', 'general-meeting');

      endwhile;

    else :

      get_template_part('template-parts/content', 'none');

    endif;
      ?>
      </div>

      <?php do_action('skbm_after_content'); ?>
  </div>
  <div class="archive-bg--1"></div>
  <div class="archive-bg--2"></div>
</main><!-- #main -->

<?php
get_footer();
