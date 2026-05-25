<?php

namespace ypmDataTable;
use YpmPopup\SubscriptionPopup as Subscription;
require_once(dirname(__FILE__).'/ListTable.php');

class YPMTable extends YpmListTable
{
	protected $id = '';
	protected $columns = array();
	protected $displayColumns = array();
	protected $sortableColumns = array();
	protected $tablename = '';
	protected $rowsPerPage = 10;
	protected $initialOrder = array();

	public function __construct($id)
	{
		$this->id = $id;
		parent::__construct(array(
			'singular'=> 'wp_'.esc_attr($id), //singular label
			'plural' => 'wp_'.esc_attr($id).'s', //plural label
			'ajax' => false
		));
	}
	
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function setRowsPerPage($rowsPerPage)
	{
		$this->rowsPerPage = $rowsPerPage;
	}

	public function setColumns($columns)
	{
		$this->columns = $columns;
	}

	public function getColumns()
	{
		return $this->columns;
	}

	public function setDisplayColumns($displayColumns)
	{
		$this->displayColumns = $displayColumns;
	}

	public function setSortableColumns($sortableColumns)
	{
		$this->sortableColumns = $sortableColumns;
	}

	public function setTablename($tablename)
	{
		$this->tablename = $tablename;
	}

	public function setInitialSort($orderableColumns)
	{
		$this->initialOrder = $orderableColumns;
	}

	public function get_columns()
	{
		return $this->displayColumns;
	}

	public function prepare_items()
	{
		global $wpdb;

		$query = '';
		$this->customizeQuery($query);; // Use prepared values in query

		$totalItems = count($wpdb->get_results($query));
		$perPage = $this->rowsPerPage;

		$totalPages = ceil($totalItems/$perPage);

		$paged = isset($_GET["paged"]) ? (int)$_GET["paged"] : '';

		if (empty($paged) || !is_numeric($paged) || $paged <= 0) {
			$paged = 1;
		}

		$this->set_pagination_args(array(
			"total_items" => $totalItems,
			"total_pages" => $totalPages,
			"per_page" => $perPage,
		));

		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);
		$items = $wpdb->get_results($query, ARRAY_N);
		/*Remove data when its class does not exist.*/
		$this->customizeRowsData($items);

		$this->items = $items;
	}

	public function customizeRowsData(&$items) {

	}

	public function get_sortable_columns() {
		return $this->sortableColumns;
	}

	public function display_rows()
	{
		//get the records registered in the prepare_items method
		$records = $this->items;

		//get the columns registered in the get_columns and get_sortable_columns methods
		list($columns, $hidden) = $this->get_column_info();

		if (!empty($records)) {
			foreach($records as $rec) {
				echo '<tr>';

				$this->customizeRow($rec);
				for ($i = 0; $i<count($rec); $i++) {
					echo '<td>'.stripslashes($rec[$i]).'</td>';
				}

				echo '</tr>';
			}
		}
	}

	public function customizeRow(&$row)
	{

	}

	public function customizeQuery(&$query)
	{

	}

	public function __toString()
	{
		$this->prepare_items(); ?>
		<form method="get" id="posts-filter">
		<p class="search-box">
			 <input type="hidden" name="post_type" value="<?php echo esc_attr(YPM_POPUP_POST_TYPE);?>" >
			 <input type="hidden" name="page" value="<?php echo esc_attr(YPM_SUBSCRIBERS_PAGE);?>" >
			 <?php $this->search_box('search', 'search_id'); ?>
		</p>
		<?php $this->display();?>
		</form>
		<?php
		return '';
	}

	// parent class method overriding
	public function extra_tablenav($which)
	{
		$subscriptionsList = Subscription::getAllSubscriptionForms();
		$subscribersDates = Subscription::getAllSubscribersDate();
		$uniqueDates = array();
		$uniqueDates[] = array('date-title' => 'All dates', 'date-value' => 'all');
		foreach ($subscribersDates as $arr) {
			$uniqueDates[] = $arr;
		}
		$uniqueDates = array_unique($uniqueDates, SORT_REGULAR);
		$selectedPopup = '';
		$selectedDate = '';
		$list = '';
		if (isset($_GET['ypm-subscription-id'])) {
			$selectedCountdown = (int)$_GET['ypm-subscription-id'];
		}
		if (isset($_GET['ypm-subscribers-date'])) {
			$selectedDate = sanitize_text_field($_GET['ypm-subscribers-dates']);
		}
		?>
        <div class="alignleft actions daterangeactions">
            <select name="ypm-bulk-action">
                <option value=""><?php esc_attr_e('Bulk Actions', 'popup_master') ?></option>
                <option value="delete"><?php esc_attr_e('Delete', 'popup_master') ?></option>
            </select>
            <input name="apply_action" id="post-query-submit" class="button ypm-delete-subscribers" value="<?php esc_attr_e('Apply', 'popup_master')?>" type="submit">
        </div>
        <form method="get" action="/">
            <input name="_wp_http_referer" value="" type="hidden">
            <div class="alignleft actions daterangeactions">
                <label class="screen-reader-text" for="ypm-subscription-id"><?php esc_attr_e('Filter by subscription', 'popup_master')?></label>
                <input type="hidden" class="ypm-subscription-id" name="ypm-subscription-id" value="<?php echo esc_attr($selectedPopup);?>">
                <select name="ypm-subscription-id" id="ypm-subscription-id">
                    <?php
                    $list .= '<option value="all">'.esc_attr__('All subscriptions', 'popup_master').'</option>';
                    foreach ($subscriptionsList as $id => $postTitle) {

                        if ($selectedCountdown == $id) {
                            $selected = ' selected';
                        }
                        else {
                            $selected = '';
                        }
                        $list .= '<option value="'.esc_attr($id).'"'.esc_attr($selected).'>'.esc_attr($postTitle).'</option>';
                    }
                    echo wp_kses($list, \YpmAdminHelper::getAllowedTags());
                    ?>
                </select>
                <label class="screen-reader-text" for="ypm-subscribers-dates"><?php esc_attr_e('Filter by date', 'popup_master')?></label>
                <input type="hidden" class="ypm-subscribers-date" name=ypm-subscribers-date" value="<?php echo esc_attr($selectedDate);?>">
                <select name="ypm-subscribers-dates" id="ypm-subscribers-dates">
                    <?php
                    $dateList = '';
                    foreach ($uniqueDates as $date) {
                        if ($selectedDate == $date['date-value']) {
                            $selected = ' selected';
                        }
                        else {
                            $selected = '';
                        }
                        $dateList .= '<option value="'.esc_attr($date['date-value']).'"'.esc_attr($selected).'>'.esc_attr($date['date-title']).'</option>';
                    }
                    echo wp_kses($dateList, \YpmAdminHelper::getAllowedTags());
                    ?>
                </select>
                <input name="filter_action" id="post-query-submit" class="button" value="<?php esc_attr_e('Filter', 'popup_master')?>" type="submit">
            </div>
        </form>
		<?php
	}
}
