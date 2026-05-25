<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
$winChance = YpmAdminHelper::winningChance();
?>
<div class="row form-group">
	<label class="col-md-6 control-label">
		<?php esc_attr_e('Winning chance', 'popup_master'); ?>
		<?php if(ypm_is_free()): ?>
			<?php echo YpmAdminHelper::proSpan(); ?>
		<?php endif;?>
		:
	</label>
	<div class="col-md-6">
		<?php if(!ypm_is_free()): ?>
			<?php echo YpmFunctions::createSelectBox($winChance, esc_html($popupTypeObj->getOptionValue('ypm-gamification-win-chance')), array('name' => 'ypm-gamification-win-chance', 'class'=>'js-ypm-select2')); ?>
		<?php endif; ?>

		<?php if(ypm_is_free()): ?>
			<?php echo YpmFunctions::createSelectBox(array('0' => '0%'), esc_html($popupTypeObj->getOptionValue('ypm-gamification-win-chance')), array('name' => 'ypm-gamification-win-chance', 'class'=>'js-ypm-select2', 'disabled' => true)); ?>
		<?php endif; ?>
	</div>
</div>

<div class="row form-group">
	<label class="col-md-6 control-label" for="ypm-gamification-already-subscribed">
		<?php esc_attr_e('Hide for already played users', 'popup_master'); ?>:
	</label>
	<div class="col-md-6">
		<label class="ypm-switch">
			<input type="checkbox" id="ypm-gamification-already-subscribed" name="ypm-gamification-already-subscribed" class="ypm-accordion-checkbox" <?php echo esc_attr($popupTypeObj->getOptionValue('ypm-gamification-already-subscribed')); ?>>
			<span class="ypm-slider ypm-round"></span>
		</label>
	</div>
</div>