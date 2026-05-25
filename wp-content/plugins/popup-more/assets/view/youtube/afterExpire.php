<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
	use YpmPopup\MultipleChoiceButton;
	$defaults = YpmPopupData::defaultsData();
?>
<div class="row form-group">
	<div class="col-md-6">
		<label class="ypm-label-of-switch"><?php esc_attr_e('After expiration', 'popup_master')?></label>
	</div>
	<div class="col-md-6">
	</div>
</div>
<div class="ypm-multichoice-wrapper">
	<?php
	$multipleChoiceButton = new MultipleChoiceButton($defaults['youtube-after-expire'], esc_attr($popupTypeObj->getOptionValue('ypm-popup-expiration-behavior')));
	echo wp_kses($multipleChoiceButton, YpmAdminHelper::getAllowedTags());
	?>
</div>
<div id="ypm-popup-expiration-redirect" class="ypm-sub-options-wrapper ypm-hide">
	<div class="row form-group">
		<div class="col-md-6">
			<label for="ypm-youtube-redirect-url"><?php esc_attr_e('URL', 'popup_master')?></label>
		</div>
		<div class="col-md-6">
			<input id="ypm-youtube-redirect-url" class="form-control" type="url" name="ypm-youtube-redirect-url" placeholder="https://" value="<?php echo esc_url($popupTypeObj->getOptionValue('ypm-youtube-redirect-url'))?>">
		</div>
	</div>
	<div class="row form-group">
		<div class="col-md-6">
			<label for="ypm-youtube-redirect-url-tab" class="ypm-label-of-switch"><?php esc_attr_e('Redirect to new tab', 'popup_master'); ?></label>
		</div>
		<div class="col-md-6">
			<label class="ypm-switch">
				<input type="checkbox" id="ypm-youtube-redirect-url-tab" name="ypm-youtube-redirect-url-tab" class="ypm-accordion-checkbox" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-youtube-redirect-url-tab')); ?>>
				<span class="ypm-slider ypm-round"></span>
			</label>
		</div>
	</div>
</div>