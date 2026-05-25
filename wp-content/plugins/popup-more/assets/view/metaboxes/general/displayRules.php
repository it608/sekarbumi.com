<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
use YpmPopup\DisplayConditionBuilder;
$savedData = $popupTypeObj->getOptionValue('ypm-display-settings');
$obj = new DisplayConditionBuilder();
$obj->setSavedData($savedData);
$type = '';
if (!empty($_GET['ypm_type'])) {
	$type = sanitize_text_field($_GET['ypm_type']);
}
if (empty($type)) {
	$type = $popupTypeObj->getOptionValue('ypm-popup-type');
}

$subId = 0;
if (!empty($_GET['ypm_module_id'])) {
	$subId = sanitize_text_field($_GET['ypm_module_id']);
}
if (empty($subId)) {
	$subId = $popupTypeObj->getOptionValue('ypm-popup-sub-id');
}
?>
<div class="ycf-bootstrap-wrapper">
	<?php echo wp_kses($obj->render(), YpmAdminHelper::getAllowedTags()); ?>
	<input type="hidden" name="ypm-popup-type" value="<?php echo esc_attr($type);?>">
	<input type="hidden" name="ypm-popup-sub-id" value="<?php echo esc_attr($subId);?>">
</div>