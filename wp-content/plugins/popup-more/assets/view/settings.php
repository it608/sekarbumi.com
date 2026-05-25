<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$proSpan = '';
$isPro = '';
if(ypm_is_free()) {
	$isPro = '-pro';
	$proSpan = '<span class="ypm-pro-span">'.__('Pro', 'popup_master').'</span>';
}
?>
<?php
$defaultData = YpmPopupData::defaultsData();
$allowedTags = YpmAdminHelper::getAllowedTags();
?>
<div class="ycf-bootstrap-wrapper">
<div class="row ypm-margin-bottom-15">
	<div class="col-xs-4">
		<label class="control-label" for="ypm-popup-enable-start-date"><?php esc_attr_e('Enable start date', 'popup_master');?>:</label>
		<?php echo wp_kses(ypm_info(esc_attr__('If this option is enabled, the popup will start to appear from your specified day.', 'popup_master')), $allowedTags); ?>
	</div>
	<div class="col-xs-4">
		<label class="ypm-switch">
			<input type="checkbox" id="ypm-popup-enable-start-date" class="js-ypm-accordion" name="ypm-popup-enable-start-date" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-popup-enable-start-date')); ?>>
			<span class="ypm-slider ypm-round"></span>
		</label>
		<br>
	</div>
</div>
<div class="ypm-margin-bottom-15">
	<div class="row form-group">
		<div class="col-md-4">
			<label for="ypm-popup-start-date" class="ycd-label-of-input">
				<?php esc_attr_e('Date', 'popup_master'); ?>
			</label>
		</div>
		<div class="col-md-6">
			<input type="text" id="ypm-popup-start-date" class="form-control ypm-date-time-picker" name="ypm-popup-start-date" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-popup-start-date')); ?>">
		</div>
	</div>
	<div class="row form-group">
		<div class="col-md-4">
			<label for="ypm-popup-start-date-time-zone" class="ycd-label-of-input">
				<?php esc_attr_e('Time zone', 'popup_master'); ?>
			</label>
		</div>
		<div class="col-md-6">
			<?php echo wp_kses(YpmFunctions::createSelectBox(YpmAdminHelper::getTimeZones(), $popupTypeObj->getOptionValue('ypm-popup-start-date-time-zone'), array('name' => 'ypm-popup-start-date-time-zone', 'class' => 'js-basic-select form-control ypm-fblike-share-layout ypm-fblike-option')), $allowedTags); ?>
		</div>
	</div>
</div>
<div class="row ypm-margin-bottom-15">
	<div class="col-xs-4">
		<label class="control-label" for="ypm-popup-enable-end-date"><?php esc_attr_e('Enable show end date', 'popup_master'); echo wp_kses($proSpan, $allowedTags);?>:</label>
		<?php echo wp_kses(ypm_info(esc_attr__('If this option is enabled, the popup will do not show after the date.', 'popup_master')), $allowedTags); ?>
	</div>
	<div class="col-xs-4 ypm-option-wrapper<?php echo esc_attr($isPro); ?>">
		<label class="ypm-switch">
			<input type="checkbox" id="ypm-popup-enable-end-date" class="js-ypm-accordion" name="ypm-popup-enable-end-date" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-popup-enable-end-date')); ?>>
			<span class="ypm-slider ypm-round"></span>
		</label>
		<br>
	</div>
</div>
<div class="ypm-margin-bottom-15">
	<?php if (!ypm_is_free()): ?>
	<div class="row form-group">
		<div class="col-md-4">
			<label for="ypm-popup-end-date" class="ycd-label-of-input">Date</label>
		</div>
		<div class="col-md-6">
			<input type="text" id="ypm-popup-end-date" class="form-control ypm-date-time-picker" name="ypm-popup-end-date" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-popup-end-date')); ?>">
		</div>
	</div>
	<div class="row form-group">
		<div class="col-md-3">
			<label for="ypm-date-time-picker" class="ypm-label-of-input"><?php _e('Time Zone', 'popup_master'); ?></label>
		</div>
		<div class="col-md-6">
			<div class="ypm-select-wrapper">
			<?php
				$timeZone = YpmAdminHelper::selectBox(YpmPopupProData::getTimeZones(), esc_attr($popupTypeObj->getOptionValue('ypm-popup-end-date-time-zone')), array('name' => 'ypm-popup-end-date-time-zone', 'class' => 'js-ycd-select js-circle-time-zone'));
				echo wp_kses($timeZone, $allowedTags);
			?>
			</div>
		</div>
	</div>
	<?php endif; ?>
</div>
<div class="row ypm-margin-bottom-15">
	<div class="col-xs-4">
		<label class="control-label" for="ypm-popup-schedule-working-hours"><?php esc_attr_e('Schedule for working hours', 'popup_master'); echo wp_kses($proSpan, $allowedTags);?>:</label>
		<?php echo wp_kses(ypm_info(esc_attr__('If this option is enabled, the popup can show during working hours', 'popup_master')), $allowedTags); ?>
	</div>
	<div class="col-xs-4 ypm-option-wrapper<?php echo esc_attr($isPro); ?>">
		<label class="ypm-switch">
			<input type="checkbox" id="ypm-popup-schedule-working-hours" class="js-ypm-accordion" name="ypm-popup-schedule-working-hours" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-popup-schedule-working-hours')); ?>>
			<span class="ypm-slider ypm-round"></span>
		</label>
		<br>
	</div>
</div>
<div class="ypm-margin-bottom-15">
	<div class="row form-group">
		<div class="col-md-4">
			<label for="ypm-popup-schedule-working-hours-time-zone" class="ycd-label-of-input">
				<?php esc_attr_e('Time zone', 'popup_master'); ?>
			</label>
		</div>
		<div class="col-md-6">
			<?php echo wp_kses(YpmFunctions::createSelectBox(YpmAdminHelper::getTimeZones(), $popupTypeObj->getOptionValue('ypm-popup-schedule-working-hours-time-zone'), array('name' => 'ypm-popup-schedule-working-hours-time-zone', 'class' => 'js-basic-select form-control ypm-fblike-share-layout ypm-fblike-option')), $allowedTags); ?>
		</div>
	</div>
	<div class="row form-group">
		<div class="col-md-4">
			<label for="ypm-popup-schedule-working-hours-day" class="ycd-label-of-input">
				<?php esc_attr_e('Week day(s)', 'popup_master'); ?>
			</label>
		</div>
		<div class="col-md-6">
			<?php
				$schedule = YpmFunctions::createSelectBox(
					@$defaultData['week-days'],
					$popupTypeObj->getOptionValue('ypm-popup-schedule-working-hours-day'),
					array(
						'name' => 'ypm-popup-schedule-working-hours-day[]',
						'multiple' => 'multiple',
						'data-week-number-key' => 'startDayNumber',
						'class' => 'js-basic-select form-control '
					));

					echo wp_kses($schedule, $allowedTags);
			?>
		</div>
	</div>
	<div class="row form-group">
		<div class="col-md-4">
		</div>
		<div class="col-md-2">
			<label class="ycd-label-of-input" for="ypm-popup-schedule-working-hours-from"><?php _e('from', 'popup_master'); ?></label>
		</div>
		<div class="col-md-4">
			<input type="text" name="ypm-popup-schedule-working-hours-from" id="ypm-schedule2-from" class="form-control js-datetimepicker-seconds" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-popup-schedule-working-hours-from')); ?>" autocomplete="off">
		</div>
	</div>
	<div class="row form-group">
		<div class="col-md-4">
		</div>
		<div class="col-md-2">
			<label class="ycd-label-of-input" for="ypm-popup-schedule-working-hours-to"><?php _e('To', 'popup_master'); ?></label>
		</div>
		<div class="col-md-4">
			<input type="text" name="ypm-popup-schedule-working-hours-to" id="ypm-schedule2-to" class="form-control js-datetimepicker-seconds" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-popup-schedule-working-hours-to')); ?>" autocomplete="off">
		</div>
	</div>
</div>
<div class="row ypm-margin-bottom-15">
	<div class="col-xs-4">
		<label class="control-label" for="ypm-popup-title"><?php esc_attr_e('Show popup title', 'popup_master');?>:</label>
		<?php echo wp_kses(ypm_info(esc_attr__('if this option is enabled, the popup will show popup title.', 'popup_master')), $allowedTags); ?>
	</div>
	<div class="col-xs-4">
		<label class="ypm-switch">
			<input type="checkbox" id="ypm-popup-title" class="js-ypm-accordion" name="ypm-popup-title" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-popup-title')); ?>>
			<span class="ypm-slider ypm-round"></span>
		</label>
		<br>
	</div>
</div>
<div>
<div class="row ypm-margin-bottom-15">
	<div class="col-xs-4">
		<label class="control-label"><?php esc_attr_e('popup title color', 'popup_master');?>:</label>
	</div>
	<div class="col-xs-4">
		<div id="ypm-color-picker"><input  class="ypm-color-picker" id="ypm-title-color" type="text" name="ypm-title-color" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-title-color')); ?>" /></div>
	</div>
</div>
<div class="row ypm-margin-bottom-15">
	<div class="col-xs-4">
		<label class="control-label" for="ypm-popup-title-font-size"><?php esc_attr_e('popup title font size', 'popup_master');?>:</label>
	</div>
	<div class="col-xs-4">
        <input type="text" id="ypm-popup-title-font-size" class="form-control" name="ypm-popup-title-font-size" value="<?php echo  esc_attr($popupTypeObj->getOptionValue('ypm-popup-title-font-size')); ?>" placeholder="Font size ex 18px">
	</div>
</div>
</div>
<div class="row ypm-margin-bottom-15">
	<div class="col-xs-4">
		<label class="control-label" for="ypm-esc-key"><?php esc_attr_e('Dismiss on "esc" key', 'popup_master');?>:</label>
		<?php echo wp_kses(ypm_info(esc_attr__('The popup will close if the "Esc" key of your keyboard is clicked.', 'popup_master')), $allowedTags); ?>
	</div>
	<div class="col-xs-4">
		<label class="ypm-switch">
			<input type="checkbox" id="ypm-esc-key" name="ypm-esc-key" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-esc-key')); ?>>
			<span class="ypm-slider ypm-round"></span>
		</label>
		<br>
	</div>
</div>
<div class="row ypm-margin-bottom-15">
	<div class="col-xs-4">
		<label class="control-label" for="ypm-close-button"><?php esc_attr_e('Show "close" button', 'popup_master');?>:</label>
		<?php echo wp_kses(ypm_info(esc_attr__('Disable this option if you don\'t want to show a "close" button on the popup.', 'popup_master')), $allowedTags); ?>
	</div>
	<div class="col-xs-4">
		<label class="ypm-switch">
			<input type="checkbox" id="ypm-close-button" class="js-ypm-accordion" name="ypm-close-button" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-close-button')); ?>>
			<span class="ypm-slider ypm-round"></span>
		</label>
		<br>
	</div>
</div>
<div class="row ypm-margin-bottom-15 ypm-sub-option">
	<div class="col-xs-12">
		<div class="row ypm-margin-bottom-15">
			<div class="col-xs-4">
				<label class="control-label" for="js-ypm-enable-close-delay"><?php esc_attr_e('Enable delay', 'popup_master');?>:</label>
				<?php echo wp_kses(ypm_info(esc_attr__('Enable popup close showing delay', 'popup_master')), $allowedTags); ?>
			</div>
			<div class="col-xs-4">
				<label class="ypm-switch">
					<input type="checkbox" id="js-ypm-enable-close-delay" class="js-ypm-enable-close-delay js-ypm-accordion" name="ypm-enable-close-delay" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-enable-close-delay')); ?>>
					<span class="ypm-slider ypm-round"></span>
				</label>
			</div>
		</div>
		<div class="row ypm-margin-bottom-15 ypm-sub-option">
			<div class="row ypm-margin-bottom-15">
				<div class="col-xs-4">
					<label class="control-label" for="ypm-close-button-delay"><?php esc_attr_e('Delay', 'popup_master');?>:</label>
				</div>
				<div class="col-xs-4">
					<input type="number" id="ypm-close-button-delay" class="form-control" name="ypm-close-button-delay" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-close-button-delay')); ?>">
				</div>
				<div class="col-xs-1">
					<?php esc_attr_e('Seconds', 'popup_master')?>
				</div>
			</div>
			<div class="row ypm-margin-bottom-15">
				<div class="col-xs-4">
					<label class="control-label" for="js-ypm-show-close-delay"><?php esc_attr_e('show delay', 'popup_master');?>:</label>
					<?php echo wp_kses(ypm_info(esc_attr__('When you enable this option, it will display the remaining seconds until the close button appears', 'popup_master')), $allowedTags); ?>
				</div>
				<div class="col-xs-4">
					<label class="ypm-switch">
						<input type="checkbox" id="js-ypm-show-close-delay" class="js-ypm-enable-close-delay js-ypm-accordion" name="ypm-show-close-delay" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-show-close-delay')); ?>>
						<span class="ypm-slider ypm-round"></span>
					</label>
				</div>
			</div>
			<div class="row ypm-margin-bottom-15 ypm-sub-option">
				<div class="row ypm-margin-bottom-15">
					<div class="col-xs-4">
						<label class="control-label" for="ypm-close-delay-font-size"><?php esc_attr_e('Font size', 'popup_master');?>:</label>
					</div>
					<div class="col-xs-4">
						<input type="text" id="ypm-close-delay-font-size" class="form-control" name="ypm-close-delay-font-size" value="<?php echo  esc_attr($popupTypeObj->getOptionValue('ypm-close-delay-font-size')); ?>">
					</div>
				</div>
				<div class="row ypm-margin-bottom-15">
					<div class="col-xs-4">
						<label class="control-label" for="ypm-close-delay-color"><?php esc_attr_e('Change Text Color', 'popup_master');?>:</label>
					</div>
					<div class="col-xs-4">
						<div id="ypm-color-picker"><input  class="ypm-color-picker" id="ypm-close-delay-color" type="text" name="ypm-close-delay-color" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-close-delay-color')); ?>" /></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row ypm-margin-bottom-15">
	<div class="col-xs-4">
		<label class="control-label" for="ypm-close-button-click-sound"><?php esc_attr_e('Close button click sound', 'popup_master');?>:</label>
		<?php echo wp_kses(ypm_info(esc_attr__('If this option is enabled will be play click sound', 'popup_master')), $allowedTags); ?>
	</div>
	<div class="col-xs-4">
		<label class="ypm-switch">
			<input type="checkbox" id="ypm-close-button-click-sound" name="ypm-close-button-click-sound" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-close-button-click-sound')); ?>>
			<span class="ypm-slider ypm-round"></span>
		</label>
		<br>
	</div>
</div>
<div class="row ypm-margin-bottom-15">
	<div class="col-xs-4">
		<label class="control-label" for="ypm-overlay-click"><?php esc_attr_e('Dismiss on overlay click', 'popup_master');?>:</label>
		<?php echo wp_kses(ypm_info(esc_attr__('The popup will close when clicked on the overlay of the popup.', 'popup_master')), $allowedTags); ?>
	</div>
	<div class="col-xs-4">
		<label class="ypm-switch">
			<input type="checkbox" id="ypm-overlay-click" name="ypm-overlay-click" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-overlay-click')); ?>>
			<span class="ypm-slider ypm-round"></span>
		</label>
		<br>
	</div>
</div>
<div class="row ypm-margin-bottom-15">
	<div class="col-xs-4">
		<label class="control-label" for="ypm-content-click-status"><?php esc_attr_e('Dismiss on content click', 'popup_master');?>:</label>
		<?php echo wp_kses(ypm_info(esc_attr__('The popup will close when clicked on the content of the popup.', 'popup_master')), $allowedTags); ?>
	</div>
	<div class="col-xs-4">
		<label class="ypm-switch">
			<input type="checkbox" id="ypm-content-click-status" class="js-ypm-accordion" name="ypm-content-click-status" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-content-click-status')); ?>>
			<span class="ypm-slider ypm-round"></span>
		</label>
		<br>
	</div>
</div>
<div class="ypm-sub-option">
	<div class="row ypm-margin-bottom-15">
		<div class="col-xs-4">
			<label class="control-label" for="ypm-content-click-count"><?php esc_attr_e('Click count', 'popup_master');?>:</label>
		</div>
		<div class="col-xs-4">
			<input type="number" id="ypm-content-click-count" name="ypm-content-click-count" class="form-control" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-content-click-count')); ?>">
		</div>
	</div>
	<div class="row ypm-margin-bottom-15">
		<div class="col-xs-3">
			<label class="control-label" for="ypm-content-click-redirect-enable"><?php esc_attr_e('Enable redirect', 'popup_master');?>:</label>
			<?php echo wp_kses(ypm_info(esc_attr__('After popup content click enable redirection.', 'popup_master')), $allowedTags); ?>
		</div>
		<div class="col-xs-4">
			<label class="ypm-switch">
				<input type="checkbox" id="ypm-content-click-redirect-enable" class="js-ypm-accordion" name="ypm-content-click-redirect-enable" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-content-click-redirect-enable')); ?>>
				<span class="ypm-slider ypm-round"></span>
			</label>
		</div>
	</div>
	<div>
		<div class="row ypm-margin-bottom-15">
			<div class="col-xs-3">
				<label class="control-label" for="ypm-content-click-redirect-url"><?php esc_attr_e('URL', 'popup_master');?>:</label>
			</div>
			<div class="col-xs-4">
				<input type="url" id="ypm-content-click-redirect-url" class="form-control" name="ypm-content-click-redirect-url" placeholder="https://" value="<?php echo esc_url($popupTypeObj->getOptionValue('ypm-content-click-redirect-url')); ?>">
			</div>
		</div>
		<div class="row ypm-margin-bottom-15">
			<div class="col-xs-3">
				<label class="control-label" for="ypm-content-click-redirect-tab"><?php esc_attr_e('Redirect to new tab', 'popup_master');?>:</label>
				<?php echo wp_kses(ypm_info(esc_attr__("It's will be redirect to new tab.", 'popup_master')), $allowedTags); ?>
			</div>
			<div class="col-xs-4">
				<label class="ypm-switch">
					<input type="checkbox" id="ypm-content-click-redirect-tab" name="ypm-content-click-redirect-tab" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-content-click-redirect-tab')); ?>>
					<span class="ypm-slider ypm-round"></span>
				</label>
			</div>
		</div>
	</div>
</div>
<div class="row ypm-margin-bottom-15">
	<div class="col-xs-4">
		<label class="control-label" for="ypm-disable-page-scrolling"><?php esc_attr_e('Disable page scrolling', 'popup_master');?>:</label>
		<?php echo wp_kses(ypm_info(esc_attr__('If this option is enabled, the page won\'t scroll until the popup is open', 'popup_master')), $allowedTags) ?>
	</div>
	<div class="col-xs-4">
		<label class="ypm-switch">
			<input type="checkbox" id="ypm-disable-page-scrolling" name="ypm-disable-page-scrolling" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-disable-page-scrolling')); ?>>
			<span class="ypm-slider ypm-round"></span>
		</label>
		<br>
	</div>
</div>
<div class="row ypm-margin-bottom-15">
	<div class="col-xs-4">
		<label class="control-label" for="ypm-popup-location"><?php esc_attr_e('Popup location', 'popup_master');?>:</label>
		<?php echo wp_kses(ypm_info(esc_attr__('If this option is enabled, you can specify the position of the popup on the screen.', 'popup_master')), $allowedTags); ?>
	</div>
	<div class="col-xs-4">
		<label class="ypm-switch">
			<input type="checkbox" id="ypm-popup-location" class="js-ypm-accordion" name="ypm-popup-location" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-popup-location')); ?>>
			<span class="ypm-slider ypm-round"></span>
		</label>
		<br>
	</div>
</div>
<div class="row ypm-margin-bottom-15">
	<div class="col-xs-3">
	</div>
	<div class="col-xs-9">
		<div class="ypm-fixed-wrapper">
			<div class="js-ypm-fixed-position-style" id="ypm-fixed-position1" data-ypm-value="1"></div>
			<div class="js-ypm-fixed-position-style" id="ypm-fixed-position2" data-ypm-value="2"></div>
			<div class="js-ypm-fixed-position-style" id="ypm-fixed-position3" data-ypm-value="3"></div>
			<div class="js-ypm-fixed-position-style" id="ypm-fixed-position4" data-ypm-value="4"></div>
			<div class="js-ypm-fixed-position-style" id="ypm-fixed-position5" data-ypm-value="5"></div>
			<div class="js-ypm-fixed-position-style" id="ypm-fixed-position6" data-ypm-value="6"></div>
			<div class="js-ypm-fixed-position-style" id="ypm-fixed-position7" data-ypm-value="7"></div>
			<div class="js-ypm-fixed-position-style" id="ypm-fixed-position8" data-ypm-value="8"></div>
			<div class="js-ypm-fixed-position-style" id="ypm-fixed-position9" data-ypm-value="9"></div>
		</div>
		<input type="hidden" name="ypm-popup-fixed-position" class="js-ypm-fixed-position" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-popup-fixed-position'));?>">
	</div>
</div>
<div class="row ypm-margin-bottom-15">
	<div class="col-xs-4">
		<label class="control-label" for="ypm-popup-showing-limitation"><?php esc_attr_e('Popup showing limitation', 'popup_master');?>:</label>
		<?php echo wp_kses(ypm_info(esc_attr__('If this option is enabled, you can estimate the popup showing frequency to the same user.', 'popup_master')), $allowedTags); ?>
	</div>
	<div class="col-xs-4">
		<label class="ypm-switch">
			<input type="checkbox" id="ypm-popup-showing-limitation" class="js-ypm-accordion" name="ypm-popup-showing-limitation" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-popup-showing-limitation')); ?>>
			<span class="ypm-slider ypm-round"></span>
		</label>
		<br>
	</div>
</div>
<div class="row ypm-margin-bottom-15 ypm-sub-option">
	<div class="col-xs-12">
		<div class="row ypm-margin-bottom-15">
			<div class="col-xs-3">
				<label class="control-label" for="ypm-limitation-shwoing-count"><?php esc_attr_e('Popup showing count', 'popup_master');?>:</label>
				<?php echo wp_kses(ypm_info(esc_attr__('Select how many times the popup will be shown for the same user.', 'popup_master')), $allowedTags); ?>
			</div>
			<div class="col-xs-4">
				<input type="number" min="1" id="ypm-limitation-shwoing-count" class="form-control" name="ypm-limitation-shwoing-count" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-limitation-shwoing-count')); ?>">
			</div>
		</div>
		<div class="row ypm-margin-bottom-15">
			<div class="col-xs-3">
				<label class="control-label" for="ypm-limitation-shwoing-expiration"><?php esc_attr_e('Popup showing expiry', 'popup_master');?>:</label>
				<?php echo wp_kses(ypm_info(__('Select the count of the days after which the popup will be shown to the same user, or set the value "0" if you want to save cookies by session.', 'popup_master')), $allowedTags); ?>
			</div>
			<div class="col-xs-4">
				<input type="number" min="1" id="ypm-limitation-shwoing-expiration" class="form-control" name="ypm-limitation-shwoing-expiration" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-limitation-shwoing-expiration')); ?>">
			</div>
		</div>
		<div class="row">
			<div class="col-xs-3">
				<label class="control-label" for="ypm-show-popup-same-user-page-level"><?php esc_attr_e('Apply option on each page', 'popup_master');?>:</label>
				<?php echo wp_kses(ypm_info(__('If this option is checked the popup showing limitation will be saved for the current page. Otherwise, the limitation will refer site wide, and the popup will be shown for specific times on each page selected.The previously specified count of days will be reset every time you check/uncheck this option.', 'popup_master')), $allowedTags); ?>
			</div>
			<div class="col-xs-4">
				<label class="ypm-switch">
					<input type="checkbox" id="ypm-show-popup-same-user-page-level" name="ypm-show-popup-same-user-page-level" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-show-popup-same-user-page-level')); ?>>
					<span class="ypm-slider ypm-round"></span>
				</label>
			</div>
		</div>
	</div>
</div>
<div class="row ypm-margin-bottom-15">
	<div class="col-xs-4">
		<label class="control-label" for="ypm-popup-opening-animation"><?php esc_attr_e('Popup opening animation', 'popup_master');?>:</label>
		<?php echo wp_kses(ypm_info(__('Select the popup opening animation type', 'popup_master')), $allowedTags); ?>
	</div>
	<div class="col-xs-3">
		<?php echo wp_kses(YpmAdminHelper::selectBox($defaultData['openAnimationEffects'], $popupTypeObj->getOptionValue('ypm-popup-opening-animation'), array('name' => 'ypm-popup-opening-animation', 'class' => 'js-basic-select ypm-popup-opening-animation')), $allowedTags); ?>
		<div class="js-open-animation-effect ypm-js-opening-animation-effect"></div>
	</div>
	<div class="col-xs-1">
		<div class="ypm-animation-preview" data-type="opening"></div>
	</div>
</div>
<div class="ypm-sub-options-wrapper">
	<div class="row ypm-margin-bottom-15">
		<div class="col-xs-4">
			<label class="control-label" for="ypm-popup-opening-animation-speed"><?php esc_attr_e('Animation speed', 'popup_master');?>:</label>
			<?php echo wp_kses(ypm_info(__('Set popup opening animation duration', 'popup_master')), $allowedTags); ?>
		</div>
		<div class="col-xs-3">
			<input name="ypm-popup-opening-animation-speed" id="ypm-popup-opening-animation-speed" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-popup-opening-animation-speed'));?>" class="form-control">
		</div>
		<div class="col-xs-1">
			Seconds
		</div>
	</div>
</div>
	<div class="row ypm-margin-bottom-15">
		<div class="col-xs-4">
			<label class="control-label" for="ypm-popup-close-animation"><?php esc_attr_e('Popup close animation', 'popup_master');?>:</label>
			<?php echo wp_kses(ypm_info(__('Select the popup close animation type', 'popup_master')), $allowedTags); ?>
		</div>
		<div class="col-xs-3">
			<?php echo wp_kses(YpmAdminHelper::selectBox($defaultData['closeAnimationEffects'], $popupTypeObj->getOptionValue('ypm-popup-close-animation'), array('name' => 'ypm-popup-close-animation', 'class' => 'js-basic-select ypm-popup-close-animation')), $allowedTags); ?>
			<div class="js-close-animation-effect ypm-js-close-animation-effect"></div>
		</div>
		<div class="col-xs-1">
			<div class="ypm-animation-preview" data-type="close"></div>
		</div>
	</div>
	<div class="ypm-sub-options-wrapper">
		<div class="row ypm-margin-bottom-15">
			<div class="col-xs-4">
				<label class="control-label" for="ypm-popup-close-animation-speed"><?php esc_attr_e('Animation speed', 'popup_master');?>:</label>
				<?php echo wp_kses(ypm_info(__('Set popup close animation duration', 'popup_master')), $allowedTags); ?>
			</div>
			<div class="col-xs-3">
				<input name="ypm-popup-close-animation-speed" id="ypm-popup-close-animation-speed" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-popup-close-animation-speed'));?>" class="form-control">
			</div>
			<div class="col-xs-1">
				Seconds
			</div>
		</div>
	</div>
<div class="row ypm-margin-bottom-15">
	<div class="col-xs-4">
		<label class="control-label" for="ypm-close-delay"><?php esc_attr_e('Popup close delay', 'popup_master');?>:</label>
	</div>
	<div class="col-xs-3">
		<input type="number" min="0" id="ypm-close-delay" class="form-control" name="ypm-close-delay" value="<?php echo esc_attr((int)$popupTypeObj->getOptionValue('ypm-close-delay')); ?>"><br>
	</div>
	<div class="col-xs-1">
		<spa><?php esc_attr_e('Seconds', 'popup_master')?></spa>
	</div>
</div>
<div class="row ypm-margin-bottom-15">
	<div class="col-xs-4">
		<label class="control-label" for="ypm-delay"><?php esc_attr_e('Popup opening delay', 'popup_master');?>:</label>
	</div>
	<div class="col-xs-3">
		<input type="number" min="0" id="ypm-delay" class="form-control" name="ypm-delay" value="<?php echo esc_attr((int)$popupTypeObj->getOptionValue('ypm-delay')); ?>"><br>
	</div>
	<div class="col-xs-1">
		<spa><?php esc_attr_e('Seconds', 'popup_master')?></spa>
	</div>
</div>
<div class="row ypm-margin-bottom-15">
	<div class="col-xs-4">
		<label class="control-label" for="ypm-z-index"><?php esc_attr_e('Popup Z index', 'popup_master');?>:</label>
		<?php echo wp_kses(ypm_info(__('Increase or dicrease the value to set the priority of displaying the popup content in comparison of other elements on the page. The highest value of z-index is 2147483647.', 'popup_master')), $allowedTags); ?>
	</div>
	<div class="col-xs-3">
		<input type="number" min="0" id="ypm-z-index" class="form-control" name="ypm-z-index" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-z-index')); ?>"><br>
	</div>
</div>
<div class="row">
	<div class="col-xs-4">
		<label class="control-label" for="textinput"><?php esc_attr_e('Popup Content Styles', 'popup_master');?></label>
	</div>
</div>
<div class="ypm-sub-options-wrapper">
	<div class="row ypm-margin-bottom-15">
		<div class="col-xs-4">
			<label class="control-label" for="ypm-content-custom-class"><?php esc_attr_e('Custom class', 'popup_master');?>:</label>
		</div>
		<div class="col-xs-4">
			<input type="text" placeholder="EX: content-custom-class"  id="ypm-content-custom-class" class="form-control" name="ypm-content-custom-class" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-content-custom-class')); ?>">
		</div>
	</div>
	<div class="row ypm-margin-bottom-15">
		<div class="col-xs-4">
			<label class="control-label" for="ypm-content-padding"><?php esc_attr_e('Padding', 'popup_master');?>:</label>
			<?php echo wp_kses(ypm_info(__('Added popup content padding ex 10, 10px, 10rem', 'popup_master')), $allowedTags); ?>
		</div>
		<div class="col-xs-4">
			<input type="text" min="0" id="ypm-content-padding" class="form-control" name="ypm-content-padding" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-content-padding')); ?>"><br>
		</div>
	</div>
	<div class="row ypm-margin-bottom-15">
		<div class="col-xs-4">
			<label class="control-label" for="ypm-content-border-radius"><?php esc_attr_e('Border radius', 'popup_master');?>:</label>
			<?php echo wp_kses(ypm_info(__('Added popup content border radius ex 50, 50px, 50%, Note: use with 2th and 3th themes', 'popup_master')), $allowedTags); ?>
		</div>
		<div class="col-xs-4">
			<input type="text" min="0" id="ypm-content-border-radius" class="form-control" name="ypm-content-border-radius" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-content-border-radius')); ?>"><br>
		</div>
	</div>
	<div class="row ypm-margin-bottom-15">
		<div class="col-xs-4">
			<label class="control-label" for="ypm-content-bg-color"><?php esc_attr_e('Change Background Color', 'popup_master');?>:</label>
		</div>
		<div class="col-xs-4">
			<div id="ypm-color-picker"><input  class="ypm-color-picker" id="ypm-content-bg-color" type="text" name="ypm-content-bg-color" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-content-bg-color')); ?>" /></div>
		</div>
	</div>
	<div class="row ypm-margin-bottom-15">
		<div class="col-xs-4">
			<label class="control-label" for="ypm-content-text-color"><?php esc_attr_e('Change Text Color', 'popup_master');?>:</label>
		</div>
		<div class="col-xs-4">
			<div id="ypm-color-picker"><input  class="ypm-color-picker" id="ypm-content-text-color" type="text" name="ypm-content-text-color" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-content-text-color')); ?>" /></div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-4">
			<label class="control-label" for="ypm-enable-bg-image"><?php esc_attr_e('Enable background image', 'popup_master');?>:</label>
		</div>
		<div class="col-xs-4">
			<label class="ypm-switch">
				<input type="checkbox" id="ypm-enable-bg-image" class="js-ypm-accordion" name="ypm-enable-bg-image" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-enable-bg-image')); ?>>
				<span class="ypm-slider ypm-round"></span>
			</label>
		</div>
	</div>
	<div class="row ypm-margin-bottom-15 ypm-sub-option">
		<div class="row">
			<label for="redirect-to-url" class="col-md-5 control-label ypm-static-padding-top ypm-double-sub-option">
				<?php esc_attr_e('Image', 'popup_master')?>:
			</label>
			<div class="col-md-6 form-group">
				<div class="row">
					<div>
						<div class="ypm-button-image-uploader-wrapper">
							<input  id="js-background-upload-image" type="hidden" size="36" name="ypm-background-image" value="<?php echo (esc_attr($popupTypeObj->getOptionValue('ypm-background-image'))) ? esc_attr($popupTypeObj->getOptionValue('ypm-background-image')) : '' ; ?>" autocomplete="off">
						</div>
					</div>

					<div class="col-md-12 form-group">
						<div class="ypm-show-background-image-container">
							<span class="ypm-no-image">(<?php esc_attr_e('No image selected', 'popup_master');?>)</span>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 ypm-background-change-image-wrapper">
						<input id="js-background-upload-image-button" class="btn btn-sm btn-default" type="button" value="<?php esc_attr_e('Change image', 'popup_master');?>">
					</div>
					<div class="col-md-4 js-ypm-remove-background-image<?php echo (!$popupTypeObj->getOptionValue('ypm-background-image')) ? ' ypm-hide' : '';?>">
						<input id="js-background-upload-image-remove-button" class="btn btn-sm btn-danger" type="button" value="<?php esc_attr_e('Remove', 'popup_master');?>">
					</div>
				</div>
			</div>
		</div>
		<div class="row form-group">
			<label for="content-padding" class="col-md-5 control-label ypm-static-padding-top ypm-double-sub-option">
				<?php esc_attr_e('Mode', 'popup_master')?>:
			</label>
			<div class="col-md-6 form-group">
				<?php echo wp_kses(YpmFunctions::createSelectBox($defaultData['backroundImageModes'], $popupTypeObj->getOptionValue('ypm-background-image-mode'), array('name' => 'ypm-background-image-mode', 'class'=>'js-basic-select')), $allowedTags); ?>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-4">
		<label class="control-label" for="textinput"><?php esc_attr_e('Popup Overlay Styles', 'popup_master');?></label>
	</div>
</div>
<div class="ypm-sub-options-wrapper">
	<div class="row ypm-margin-bottom-15">
		<div class="col-xs-4">
			<label class="control-label" for="ypm-overlay-custom-class"><?php esc_attr_e('Custom class', 'popup_master');?>:</label>
		</div>
		<div class="col-xs-4">
			<input type="text" placeholder="EX: content-custom-class"  id="ypm-overlay-custom-class" class="form-control" name="ypm-overlay-custom-class" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-overlay-custom-class')); ?>">
		</div>
	</div>
	<div class="row">
		<div class="col-xs-4">
			<label class="control-label" for="textinput"><?php esc_attr_e('Change overlay color', 'popup_master');?>:</label>
		</div>
		<div class="col-xs-4">
			<div id="ypm-color-picker"><input  class="ypm-color-picker" id="ypm-overlay-color" type="text" name="ypm-overlay-color" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-overlay-color')); ?>" /></div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-4">
			<label class="control-label" for="ypm-disable-overlay"><?php esc_attr_e('Disable overlay', 'popup_master');?>:</label>
		</div>
		<div class="col-xs-4">
			<label class="ypm-switch">
				<input type="checkbox" id="ypm-disable-overlay" name="ypm-disable-overlay" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-disable-overlay')); ?>>
				<span class="ypm-slider ypm-round"></span>
			</label>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-4 overlay-opacity-label">
			<label class="control-label" for="textinput"><?php esc_attr_e('Background opacity', 'popup_master');?>:</label>
			<?php echo wp_kses(ypm_info(__('Choose the popup overlay opacity.', 'popup_master')), $allowedTags); ?>
		</div>
		<div class="col-xs-4">
			<input type="text" id="range" class="ypm-range" name="ypm-overlay-opacity" value="<?php echo esc_attr($popupTypeObj->getOptionValue('ypm-overlay-opacity')); ?>">
		</div>
	</div>
</div>
</div>