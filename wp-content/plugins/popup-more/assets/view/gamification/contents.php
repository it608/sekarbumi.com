<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
$tyneMceArgs = YpmAdminHelper::getTyneMceArgs();
?>
<div class="row form-group">
	<div class="col-md-12">
		<div class="form-group row">
			<label class="col-md-12 control-label">
				<?php esc_attr_e('Main screen message', 'popup_master'); ?>:
			</label>
		</div>
		<?php
		$editorId = 'ypm-gamification-start-text';
		$content = $popupTypeObj->getOptionValue($editorId);
		$settings = array(
			'wpautop' => false,
			'tinymce' => array(
				'width' => '100%',
			),
			'textarea_rows' => '6',
			'media_buttons' => true
		);
		wp_editor(stripslashes($content), $editorId, $settings);
		?>
	</div>
</div>
<div class="row form-group">
	<div class="col-md-12">
		<div class="form-group row">
			<label class="col-md-12 control-label">
				<?php esc_attr_e('Play screen message', 'popup_master'); ?>:
			</label>
		</div>
		<?php
		$editorId = 'ypm-gamification-play-text';
		$content = $popupTypeObj->getOptionValue($editorId);
		wp_editor($content, $editorId, $tyneMceArgs);
		?>
	</div>
</div>
<div class="row form-group">
	<div class="col-md-12">
		<div class="form-group row">
			<label class="col-md-12 control-label">
				<?php esc_attr_e('Win screen message', 'popup_master'); ?>:
			</label>
		</div>
		<?php
		$editorId = 'ypm-gamification-win-text';
		$content = $popupTypeObj->getOptionValue($editorId);
		wp_editor($content, $editorId, $tyneMceArgs);
		?>
	</div>
</div>
<div class="row form-group">
	<div class="col-md-12">
		<div class="form-group row">
			<label class="col-md-12 control-label">
				<?php esc_attr_e('Lose screen message', 'popup_master'); ?>:
			</label>
		</div>
		<?php
		$editorId = 'ypm-gamification-lose-text';
		$content = $popupTypeObj->getOptionValue($editorId);
		wp_editor($content, $editorId, $tyneMceArgs);
		?>
	</div>
</div>