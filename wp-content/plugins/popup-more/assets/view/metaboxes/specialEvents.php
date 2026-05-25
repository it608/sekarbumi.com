<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
use YpmPopup\SpecialEventsConditionBuilder;
$savedData = $popupTypeObj->getOptionValue('ypm-popup-special-events-settings');

$obj = new SpecialEventsConditionBuilder();
$obj->setSavedData($savedData);

?>
<div class="ycf-bootstrap-wrapper">
	<?php echo wp_kses($obj->render(), YpmAdminHelper::getAllowedTags()); ?>
</div>