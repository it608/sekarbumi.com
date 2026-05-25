<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
use YpmPopup\Popup;
use \YpmAdminHelper as AdminHelper;
$types = Popup::getPopupTypes();
$allowed_html = AdminHelper::getAllowedTags();
?>
<div class="ycf-bootstrap-wrapper">
<h2 class="popup-types-h2">Popup types</h2>
<?php foreach ($types as $type):
	$isAvailable = $type->isAvailable();
?>
	<?php if(!$type->isVisible()): ?>
		<?php continue; ?>
	<?php endif; ?>
<a class="create-popups-link <?php echo !$isAvailable? 'ypm-pro-options-wrapper':  ''?>"
	<?php echo wp_kses(AdminHelper::buildCreatePopupAttrs($type), $allowed_html); ?>
    href="<?php echo esc_attr(AdminHelper::buildCreatePopupUrl($type)); ?>"
   data-hasType="<?php echo esc_attr($type->getHasType() ? 1:0)?>"
   data-type="<?php echo esc_attr($type->getType());?>"
   data-is-available="<?php echo esc_attr($isAvailable ? 1: 0);?>"
>
	<div class="popups-div">
		<?php if (!$isAvailable): ?>
			<div class="ypm-upgrade-button-wrapper">
				<button type="button" class="ypm-upgrade-button-red ypm-metabox-upgrade-button">
					<b>Upgrade </br>Now</b>
				</button>
			</div>
<!--			<span class="ypm-pro-budge">PRO Feature</span>-->
		<?php endif; ?>
		<div class="ypm-type-div <?php echo wp_kses(AdminHelper::getPopupThumbClass($type), $allowed_html); ?>"></div>
		<div class="ypm-type-view-footer">
			<span class="ypm-promotion-video"><?php echo esc_attr($type->getName()); echo !$isAvailable ? '<span style="color: red"> (Pro)</span>': '';?></span>
			<?php if (!empty($type->getOptions()['videoURL'])): ?>
			<span class="ypm-play-promotion-video" data-href="<?php echo esc_attr($type->getOptions()['videoURL']);?>"></span>
			<?php endif; ?>
		</div>
	</div>
</a>
<?php if ($type->getHasType()): ?>
	<div class="popup-type-sub-options-wrapper <?php !$isAvailable ? 'popup-type-sub-options-wrapper-pro': ''?>">
		<div class="popup-type-sub-options ycf-bootstrap-wrapper" id="popup-options-<?php echo esc_attr($type->getType());?>">
			<h3><?php echo esc_attr('Add New '.esc_attr($type->getName()).' Popup')?></h3>
			<label style="display: inline-block; width: 100px;font-weight: bold;">Select Module</label>
			<?php

				$popupData = Popup::getModulesDataArray(array('type' => $type->getType(), 'returnFalse' => true));
				$creationLink = admin_url().'post-new.php?post_type='.esc_attr($type->getType());
				if (empty($popupData)) { ?>
					<a href="<?php echo esc_attr($creationLink) ?>">Please create your first <?php echo esc_attr($type->getName()) ?></a>
				<?php }
				else {
					echo YpmFunctions::createSelectBox($popupData, '', array('id' => 'ypm-popup-type-value'));
				}
			?>
			<?php if (!empty($popupData)): ?>
			<div class="ypm-sub-creation">
				<span><b>Or</b> <a href="<?php echo esc_attr($creationLink);?>">Create New</a></span>
			</div>
			<?php endif; ?>
			<div class="create-button-wrapper">
				<button
					class="btn btn-success ypm-create-popup-button"
					data-type="<?php echo esc_attr($type->getType());?>"
					data-href="<?php echo esc_attr(AdminHelper::buildCreatePopupUrl($type)); ?>"
				><?php esc_attr_e('Create popup', 'popup_master')?></button>
			</div>
		</div>
	</div>
<?php endif; ?>
<?php endforeach; ?>
</div>