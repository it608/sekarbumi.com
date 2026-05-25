<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
use YpmPopup\SubscriptionPopup as Subscription;
use ypmDataTable\Subscribers as SubscribersTable;
$subscriptionsList = Subscription::getAllSubscriptionForms();
?>
<div class="popup-type-sub-options-wrapper">
	<div id="ypm-subscription-content">
		<div class="ycf-bootstrap-wrapper">
			<h3><?php esc_attr_e('Select Subscription form to export', 'popup_master')?></h3>
			<select name="ypm-subscription-id" id="ypm-subscription-id">
				<?php
				$list .= '<option value="all">'.esc_attr__('All subscriptions', 'popup_master').'</option>';
				foreach ($subscriptionsList as $id => $postTitle) {

					$selected = '';

					$list .= '<option value="'.esc_attr($id).'"'.esc_attr($selected).'>'.esc_attr($postTitle).'</option>';
				}
				echo wp_kses($list, YpmAdminHelper::getAllowedTags());
				?>
			</select>
			<div class="create-button-wrapper">
				<button class="btn btn-success ypm-export-subscriptions">Export Subscription</button>
			</div>
		</div>
	</div>
</div>
<div class="wrap">
	<h1 class="wp-heading-inline"><?php esc_attr_e('Export Subscriptions', 'popup_master')?></h1>
	<a class="page-title-action ypm-export-link"><?php esc_attr_e('Export', 'popup_master')?></a>
<?php
	$table = new SubscribersTable();
	echo esc_attr($table, YpmAdminHelper::getAllowedTags());
	$list = '';
?>
	<div class="ycf-bootstrap-wrapper">

	</div>
</div>
