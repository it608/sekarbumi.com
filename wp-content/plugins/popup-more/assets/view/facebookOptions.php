<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
global $YpmDefaultsData;
$defaults = YpmPopupData::defaultsData();
$fbButtons = $defaults['fbButtons'];
$facebookLayout = $YpmDefaultsData['fblikeLayout'];
$fblikeAction = $YpmDefaultsData['fblikeAction'];
$fblikeSize = $YpmDefaultsData['fblikeSize'];
$fbLikeAlignment = $YpmDefaultsData['fbLikeAlignment'];
$savedData = $popupTypeObj->getOptionValue('ypm-facebook-type');
$options = array(
	'popupTypeObj' => $popupTypeObj,
	'viewPath' => YPM_POPUP_VIEW.'facebook-types/'
);
$post = 0;
if (!empty($_GET['post'])) {
	$post = (int)$_GET['post'];
}
?>
<div class="ycf-bootstrap-wrapper">
	<?php
		if (!empty($post)) {
			echo YpmAdminHelper::createTypePopupNotice(YPM_FACEBOOK_POST_TYPE, $post);
		}
	?>
	<?php echo new YpmTabBuilder($fbButtons, $savedData, $options);?>
	<input type="hidden" name="ypm-facebook-type" class="ypm-section-tabs" value="<?php echo esc_attr($savedData); ?>">
</div>