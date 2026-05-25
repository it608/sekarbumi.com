<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
use YpmPopup\ConditionsConditionBuilder;
$savedData = $popupTypeObj->getOptionValue('ypm-conditions-settings');
$obj = new ConditionsConditionBuilder();
$obj->setSavedData($savedData);

?>
<div class="ycf-bootstrap-wrapper">
	<?php if(ypm_is_free()): ?>
		<?php require_once(YPM_POPUP_VIEW.'conditions-free.php'); ?>
	<?php endif;?>
	<?php echo wp_kses($obj->render(), YpmAdminHelper::getAllowedTags()); ?>
</div>