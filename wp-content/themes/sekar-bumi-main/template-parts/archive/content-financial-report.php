<?php
$skbm_financial_report_link  = get_post_meta(get_the_ID(), 'skbm_financial_report_link', true);
$current_language = get_locale();
?>
<div>
  <div class="skbm-cpt-card skbm-cpt-card__financial uk-box-shadow-medium uk-box-shadow-hover-large uk-border-rounded uk-overflow-hidden uk-text-center" data-uk-scrollspy="cls:uk-animation-slide-bottom">
    <span class="skbm-cpt-card__misc">
      <?php
      if ($current_language == 'id_ID') {
        printf('%s', __('LAPORAN KEUANGAN', 'skbm-extender'));
      } else printf('%s', __('FINANCIAL REPORT', 'skbm-extender'))
      ?>
    </span>
    <h1 class="skbm-cpt-card__post-title uk-margin-bottom"><?php the_title(); ?></h1>
    <a href="<?php echo !empty($skbm_financial_report_link) ? esc_url($skbm_financial_report_link) : ''; ?>" target="_blank" class="skbm-cpt-card__button uk-border-rounded">
      <?php
      if ($current_language == 'id_ID') {
        printf('%s', __('BACA LAPORAN', 'skbm-extender'));
      } else printf('%s', __('READ THE REPORT', 'skbm-extender'))
      ?>
    </a>
  </div>
</div>