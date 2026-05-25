<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php

global $YpmDefaultsData;
$defaults = YpmPopupData::defaultsData();
$restrictionButtons = $defaults['ageRestriction'];
$savedData = $popupTypeObj->getOptionValue('ypm-age-restriction-type');
$options = array(
	'popupTypeObj' => $popupTypeObj,
	'viewPath' => YPM_POPUP_VIEW.'ageRestriction/'
);
?>
<div class="ycf-bootstrap-wrapper">
	<?php
		if ($popupTypeObj->getOptionValue('ypm-popup-id')) {
			echo YpmAdminHelper::createTypePopupNotice(YPM_AGE_RESTRICTION_POST_TYPE, $popupTypeObj->getOptionValue('ypm-popup-id'));
		}
	?>
	<?php echo new YpmTabBuilder($restrictionButtons, $savedData, $options);?>
	<input type="hidden" name="ypm-age-restriction-type" class="ypm-section-tabs" value="<?php echo esc_attr($savedData); ?>">
</div>