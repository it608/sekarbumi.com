<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
use YpmPopup\EventsConditionBuilder;
$savedData = $popupTypeObj->getOptionValue('ypm-events-settings');

$obj = new EventsConditionBuilder();
$obj->setSavedData($savedData);

?>
<div class="ycf-bootstrap-wrapper">
	<?php if(ypm_is_free()): ?>
		<?php require_once('openEventsFree.php'); ?>
	<?php endif;?>
	<?php echo wp_kses($obj->render(), YpmAdminHelper::getAllowedTags()); ?>
</div>