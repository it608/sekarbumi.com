<?php
$skbm_sustains_report_link  = get_post_meta(get_the_ID(), 'skbm_sustains_report_link', true);
$current_language = get_locale();
?>
<div>
  <div class="skbm-cpt-card skbm-cpt-card__annual uk-box-shadow-medium uk-box-shadow-hover-large uk-border-rounded uk-overflow-hidden uk-text-center" data-uk-scrollspy="cls:uk-animation-slide-bottom">
    <div class="skbm-cpt-card__featured-image">
      <?php the_post_thumbnail('full'); ?>
    </div>
    <div class="skbm-cpt-card__inner">
      <span class="skbm-cpt-card__misc">
        <?php
        if ($current_language == 'id_ID') {
          printf('%s', __('LAPORAN KEBERLANJUTAN', 'skbm-extender'));
        } else printf('%s', __('SUSTAINABILITY REPORT', 'skbm-extender'))
        ?>
      </span>
      <h1 class="skbm-cpt-card__post-title uk-margin-bottom"><?php the_title(); ?></h1>
      <a href="<?php echo !empty($skbm_sustains_report_link) ? esc_url($skbm_sustains_report_link) : ''; ?>" target="_blank" class="skbm-cpt-card__button uk-border-rounded">
        <?php
        if ($current_language == 'id_ID') {
          printf('%s', __('BACA LAPORAN', 'skbm-extender'));
        } else printf('%s', __('READ THE REPORT', 'skbm-extender'))
        ?>
      </a>
    </div>
  </div>
</div>