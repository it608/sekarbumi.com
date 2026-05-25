<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
$formObj = $popupTypeObj->getSubscriptionFormObj();
$fieldsObj = $formObj->getFormDefaultConfig();
$formSavedFields = $formObj->getFormData();
$allowed_html = YpmAdminHelper::getAllowedTags();

$savedTypes = array_reduce($formSavedFields, function ($carry, $item) {

	$carry[] = $item['fieldType'];

	return $carry;
}, array());

?>

<div class="active-elements-wrapper">
	<div id="active-elements" class="connectedSortable">
		<?php echo wp_kses($formObj->getFormOptionsData(), YpmAdminHelper::getAllowedTags()); ?>
	</div>
</div>
<div>
	<?php if (ypm_is_free()): ?>
		<div class="subs-fields-wrapper subs-free-fields-wrapper">
			<h4><?php esc_attr_e('Select a field type to add', 'popup_master')?>:</h4>
			<?php foreach ($fieldsObj as $fieldObj):
				if (empty($fieldObj['isFree'])) {
					continue;
				}
				echo wp_kses($formObj->getFieldButton($fieldObj), $allowed_html);
				?>

			<?php endforeach;?>
		</div>
	<?php endif; ?>
</div>
<div class="sortable-all-elements-wrapper <?php echo ypm_is_free() ? 'ypm-pro-fields-wrapper ypm-free-options-wrapper': 'ycf-hide-element';?>" data-toggle-status="true">
	<?php if (ypm_is_free()): ?>
		<div><?php echo YpmUpgradeText('Unlock Subscription Fields'); ?></div>
		<div class="ypm-pro-options"></div>
	<?php endif; ?>
	<div id="sortable-all-elements" class="connectedSortable">
		<h4><?php esc_attr_e('Select a field type to add', 'popup_master')?>:</h4>
		<div class="sub-fields-wrapper">
			<?php foreach ($fieldsObj as $fieldObj):  ?>
				<?php echo wp_kses($formObj->getFieldButton($fieldObj), $allowed_html);?>
			<?php endforeach;?>
		</div>
	</div>
</div>
<div class="clear"></div>
<?php if (!ypm_is_free()): ?>
	<div class="add-element-button-wrapper">
		<span class="ycf-add-a-field"><?php esc_attr_e('Add A Field', 'popup_master')?></span>
	</div>
<?php endif; ?>
<input type="hidden" id="ypm-field-types" value="<?php echo esc_attr(json_encode($savedTypes))?>">
<input type="hidden" id="form-key-val" value="YpmSubscription">