<div class="skbm-metabox">
  <div class="skbm-metabox__item">
    <div class="skbm-metabox__left">
      <h4><?php _e( 'Report Link', 'skbm-extender' ); ?></h4>
    </div>
    <div class="skbm-metabox__right">
      <input id="skbm_report_link" type="text" name="skbm_report_link" value="<?php esc_attr_e( get_post_meta( get_the_ID(), 'skbm_report_link', true ) ); ?>">
    </div>
  </div>
</div>