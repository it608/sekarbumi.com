<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
$formId = 0;
if (!empty($_GET['post'])) {
	$formId = (int)$_GET['post'];
}
global $YpmDefaultsData;
$defaults = YpmPopupData::defaultsData();
$subscriptionTabs = $defaults['subscriptionTabs'];

$options = array(
	'popupTypeObj' => $popupTypeObj,
	'viewPath' => YPM_POPUP_VIEW.'subscription/'
);
$savedData = $popupTypeObj->getOptionValue('ypm-subscription-section');
?>

<div class="ycf-bootstrap-wrapper">
	<?php
	if ($formId) {
		echo YpmAdminHelper::createTypePopupNotice(YPM_SUBSCRIPTION_POST_TYPE, $formId);
	}
	?>
	<?php echo new YpmTabBuilder($subscriptionTabs, $savedData, $options);?>
	<input type="hidden" id="ycf-form-id" value="<?php echo esc_attr($formId); ?>">
	<input type="hidden" name="ypm-subscription-section" class="ypm-section-tabs" value="<?php echo esc_attr($savedData); ?>">
</div>