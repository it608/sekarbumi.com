<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
use YpmPopup\MultipleChoiceButton;
$defaults = YpmPopupData::defaultsData();
$popupId = 0;
if (!empty($_GET['post'])) {
	$popupId = (int)sanitize_text_field($_GET['post']);
}
$popups = YpmPopup\Popup::getPopupIdTitleData(array('exclude_ids' => array($popupId)));
$allowedTags = YpmAdminHelper::getAllowedTags();
?>
<div class="ycf-bootstrap-wrapper">
	<div class="ypm-multichoice-wrapper">
		<?php
			$multipleChoiceButton = new MultipleChoiceButton($defaults['close-behavior'], esc_attr($popupTypeObj->getOptionValue('ypm-popup-close-behavior')));
			echo wp_kses($multipleChoiceButton, $allowedTags);
		?>
	</div>
	<div id="ypm-popup-close-redirect" class="ypm-sub-options-wrapper ypm-hide">
		<div class="row form-group">
			<div class="col-xs-3">
				<label class="control-label" for="ypm-popup-close-redirection-url"><?php esc_attr_e('URL', 'popup_master');?>:</label>
			</div>
			<div class="col-xs-4">
				<input name="ypm-popup-close-redirection-url" id="ypm-popup-close-redirection-url" placeholder="https://" class="form-control" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-popup-close-redirection-url'));?>">
			</div>
		</div>
		<div class="row form-group">
			<div class="col-xs-3">
				<label class="control-label" for="ypm-popup-close-redirection-url-tab"><?php esc_attr_e('Redirect to new tab', 'popup_master');?>:</label>
			</div>
			<div class="col-xs-4">
				<label class="ypm-switch">
					<input type="checkbox" id="ypm-popup-close-redirection-url-tab" name="ypm-popup-close-redirection-url-tab" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-popup-close-redirection-url-tab')); ?>>
					<span class="ypm-slider ypm-round"></span>
				</label>
			</div>
		</div>
	</div>
	<div id="ypm-popup-close-open-popup" class="ypm-sub-options-wrapper ypm-hide">
		<div class="row form-group">
			<div class="col-xs-3">
				<label class="control-label" for="ypm-popup-close-popup"><?php esc_attr_e('Select popup', 'popup_master');?>:</label>
			</div>
			<div class="col-xs-4">
				<?php echo wp_kses(YpmFunctions::createSelectBox($popups, $popupTypeObj->getOptionValue('ypm-popup-close-popup'),  array('name' => 'ypm-popup-close-popup', 'class' => 'js-basic-select form-control')), $allowedTags); ?>
			</div>
		</div>
	</div>
</div>