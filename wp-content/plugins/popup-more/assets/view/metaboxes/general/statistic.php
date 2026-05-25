<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
$postId = 0;
if (!empty($_GET['post'])) {
    $postId = (int)sanitize_text_field($_GET['post']);
}
$count = (int)get_option('YpmPopupCount'.esc_attr($postId));
?>
<div class="ycf-bootstrap-wrapper">
    <div class="row form-group">
        <div class="col-md-4"><?php esc_attr_e('Views')?></div>
        <div class="col-md-8">
            <div class='ypm-count-view-box'><?php echo esc_attr($count); ?></div>
            <?php if (!empty($count)): ?>
                <input type="button" data-id="<?php echo esc_attr($postId); ?>" class="button ypm-reset-count-btn" value="<?php esc_attr_e('reset', 'popup_master')?>">
            <?php endif; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <label for="disable-statistic"><?php esc_attr_e('Disable')?></label>
        </div>
        <div class="col-md-8">
            <label class="ypm-switch">
                <input type="checkbox" id="disable-statistic" name="ypm-popup-disable-statistic" <?php echo wp_kses($popupTypeObj->getOptionValue('ypm-popup-disable-statistic'), YpmAdminHelper::getAllowedTags()); ?>>
                <span class="ypm-slider ypm-round"></span>
            </label>
        </div>
    </div>
</div>