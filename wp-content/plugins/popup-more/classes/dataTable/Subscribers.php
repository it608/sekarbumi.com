<?php
namespace ypmDataTable;
use \YpmAdminHelper as AdminHelper;

require_once dirname(__FILE__).'/Table.php';

class Subscribers extends YPMTable
{
	public function __construct()
	{
		global $wpdb;
		parent::__construct('');

		$this->setRowsPerPage(20);
		$this->setTablename($wpdb->prefix.YPM_SUBSCRIBERS_TABLE_NAME);
		$this->setColumns(array(
			esc_attr($this->tablename).'.id',
			esc_attr($this->tablename).'.email',
			esc_attr($this->tablename).'.cDate',
			'postTitle'
		));
		$this->setDisplayColumns(array(
			'bulk'=>'<input class="subs-bulk" type="checkbox" autocomplete="off">',
			'id' => 'ID',
			'email' => esc_attr__('Email', 'popup_master'),
			'cDate' => esc_attr__('Date', 'popup_master'),
			'subscriptionType' => esc_attr__('Subscription', 'popup_master'),
			'view' => esc_attr__('View', 'popup_master'),
		));
		$this->setSortableColumns(array(
			'id' => array('id', false),
			'email' => array('email', true),
			'cDate' => array('cDate', true),
			'subscriptionType' => array('subscriptionType', true),
			$this->setInitialSort(array(
				'id' => 'DESC'
			))
		));
	}

	public function customizeRow(&$row)
	{
		$title = $row[3];
		if (empty($title)) {
			$title = esc_attr__('(no title)', 'popup_master');
		}
		$id = $row[0];
		$contentPopup = $this->getPopupContent($row);
		$row[5] = "<button class='button view-subscription-details' data-id='".esc_attr($row[0])."'>View</button>".
		"<div style='height: 0px;width: 300px; visibility: hidden;display:flex;'>
			<div id='ypm-subscription-details-".esc_attr($id)."' style='width: 300px;' class='ycf-bootstrap-wrapper'>
				".wp_kses($contentPopup, \YpmAdminHelper::getAllowedTags())."
			</div>
		</div>";
		$row[4] = $title;
		$row[3] = AdminHelper::getFormattedDate($row[2]);
		$row[2] = $row[1];
		$row[1] = $row[0];
		$id = $row[0];
		$row[0] = '<input type="checkbox" name="ypm-delete-checkbox[]" value="'.esc_attr($id).'" class="ypm-delete-checkbox" data-delete-id="'.esc_attr($id).'">';
	}

	public function customizeQuery(&$query)
	{
		global $wpdb;
		$order = "Desc";
		if (!empty($_GET['order'])) {
			$order = sanitize_text_field($_GET['order']);
		}
		$search = '';
		if (!empty($_GET['s'])) {
			$search = sanitize_text_field($_GET['s']);
		}
		$subscriptionId = null;
		if (!empty($_GET['ypm-subscription-id'])) {
			$subscriptionId = (int)sanitize_text_field($_GET['ypm-subscription-id']);
		}
		$postsTableName = $wpdb->prefix . 'posts';
		$subscribersTableName = $wpdb->prefix . YPM_SUBSCRIBERS_TABLE_NAME;
		$query = $wpdb->prepare("SELECT 
			wp_ypm_subscribers.id, 
			wp_ypm_subscribers.email, 
			wp_ypm_subscribers.cDate,
			%i.post_title as postTitle,
			%i.firstName,
			%i.lastname
			FROM wp_ypm_subscribers
			LEFT JOIN %i on %i.formId = %i.ID
			WHERE wp_ypm_subscribers.email LIKE %s
			
		",
			$postsTableName,
			$subscribersTableName,
			$subscribersTableName,
			$postsTableName,
			$subscribersTableName,
			$postsTableName,
			'%' . $search . '%'
		);

		if (!empty($subscriptionId)) {
			$query .= $wpdb->prepare(" AND wp_ypm_subscribers.popupId = %d", $subscriptionId);
		}
		$query .= " ORDER BY wp_ypm_subscribers.id {$order};";
	}

	public function getPopupContent($row) {
		ob_start();
		?>
		<div class="row">
			<div class="col-md-12">
				<h3 style="margin-bottom: 25px;">Subscription details</h3>
			</div>
		</div><div class="row">
			<div class="col-md-6">
				<label>Email</label>
			</div>
			<div class="col-md-6">
				<span><?php echo esc_attr($row[1]);?></span>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<label>First name</label>
			</div>
			<div class="col-md-6">
				<span><?php echo esc_attr($row[4]);?></span>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<label>Last name</label>
			</div>
			<div class="col-md-6">
				<span><?php echo esc_attr($row[5]);?></span>
			</div>
		</div>
		<?php
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}
}
