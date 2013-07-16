<?php
if (!defined('_WEB')) {
	exit ();
}

class Module_Items extends ModuleApp {

	protected $_mConditions = array();

	protected $_mOrder = array();

	protected $_mLimit = NULL;

	private $_mQueryConditions = NULL;

	private $_mQueryOrder = NULL;

	private $_mQueryLimit = NULL;

	public function SetCondition($key, $value) {

		$this->_mConditions[$key] = $value;
			
		return TRUE;
	}

	public function SetOrder($key, $value) {

		$this->_mOrder[$key] = $value;
			
		return TRUE;
	}

	public function SetLimit($value) {

		$this->_mLimit = $value;
			
		return TRUE;
	}

	public function SetFilters($query) {

		if (!empty($query['filter_status'])) {

			$this->SetCondition('status', $query['filter_status']);
		}

		return TRUE;
	}

	private function _SetQueryExtra() {

		if (!empty($this->_mConditions['id'])) {

			$this->_mQueryConditions .= " AND M.item_id = '{$this->_mConditions['id']}'";
		}

		if (!empty($this->_mConditions['status'])) {

			$this->_mQueryConditions .= " AND M.item_status = '{$this->_mConditions['status']}'";
		}

		if (!empty($this->_mConditions['i18n_locale'])) {

			$this->_mQueryConditions .= " AND D.i18n_locale = '{$this->_mConditions['i18n_locale']}'";
		}

		if (!empty($this->_mOrder)) {

			$this->_mQueryOrder .= " ORDER BY";

			$q_order_items = array();

			foreach ($this->_mOrder as $key => $value) {

				$q_order_items[] = " {$key} {$val}";
			}

			$this->_mQueryOrder .= implode(", ", $q_order_items);
		}

		if (!empty($this->_mLimit)) {

			$this->_mQueryLimit = " LIMIT {$this->_mLimit}";
		}

		return TRUE;
	}

	public function Get($itemId = NULL) {

		$data = array();

		if (!empty($itemId)) {

			$this->_mConditions['id'] = $itemId;
		}

		$this->_SetQueryExtra();

		$q = "SELECT M.item_id, M.item_status, D.item_title, D.item_description
		FROM {$this->_mAppPrefix}items_main AS M
		LEFT JOIN {$this->_mAppPrefix}items_details AS D ON (D.item_id = M.item_id)
		WHERE 1
		{$this->_mQueryConditions}
		{$this->_mQueryOrder}
		{$this->_mQueryLimit}";

		$rs = Core::Database()->Query($q);

		$t = $rs->num_rows;

		if (0 < $t) {

			if ((1 == $t) && !empty($itemId)) {

				return $rs->fetch_assoc();
			}

			while($r = $rs->fetch_assoc()) {

				$data[$r['item_id']] = $r;
			}
		}

		return $data;
	}

	public function GetPagination($pageNum = 1) {

		$items = array();

		$items_per_page = Core::Config()->Get('items.pagination.items');

		if (empty($items_per_page)) {

			$items_per_page = Core::Config()->Get('settings.pagination.items');
		}

		$data = $this->Get();

		if (!empty($data)) {

			$items['data']['total'] = array_chunk($data, $items_per_page);
			$items['count']['total'] = count($data);

			$items['count']['page'] = !empty($pageNum) ? $pageNum : 1;
			$items['count']['pages'] = count($items['data']['total']);


			$items['count']['index'] = !empty($items['count']['page']) ? ($items['count']['page'] - 1) : 0;

			$items['data']['current'] = !empty($items['data']['total'][$items['count']['index']]) ? $items['data']['total'][$items['count']['index']] : array();
			$items['count']['current'] = count($items['data']['current']);

		}

		return $items;
	}

	public function GetMain($itemId) {

		$q = "SELECT item_id, item_status
		FROM {$this->_mAppPrefix}items_main
		WHERE item_id = '{$itemId}' LIMIT 1";

		$rs = Core::Database()->Query($q);

		$t = $rs->num_rows;

		if (1 == $t) {

			return  $rs->fetch_assoc();
		}

		return NULL;
	}

	public function GetDetails($itemId) {

		$data = array();

		$app_langs = Core::I18n()->GetAvailableLangs();

		$q = "SELECT item_id, i18n_locale, item_title, item_description
		FROM {$this->_mAppPrefix}items_details
		WHERE item_id = '{$itemId}'";

		$rs = Core::Database()->Query($q);

		$t = $rs->num_rows;

		if (0 < $t) {

			while($r = $rs->fetch_assoc()) {

				$data[$r['i18n_locale']] = $r;
			}
		}

		return $data;
	}

	public function Add($itemData) {

		$form = array(
				'data' => $itemData,
				'errors' => NULL,
		);

		$app_langs = Core::I18n()->GetAvailableLangs();

		if (empty($itemData['item_status'])) {

			$form['errors']['fields']['item_status'] = sprintf(__('The field "%s" is required.'), __('Status'));
		}

		foreach ($app_langs as $lang => $locale) {

			$form['data'][$locale]['item_title'] = $itemData["item_title_{$lang}"];
			$form['data'][$locale]['item_description'] = $itemData["item_description_{$lang}"];

			if (empty($form['data'][$locale]['item_title'])) {

				$form['errors']['fields']["item_title_{$lang}"] = sprintf(__('The field "%s" is required.'), __('Title') . " ({$lang})");
			}
		}

		if (!empty($form['errors'])) {

			return $form;
		}

		$q_main = "INSERT INTO {$this->_mAppPrefix}items_main (item_status) VALUES('{$itemData['item_status']}')";

		if (!Core::Database()->Query($q_main)) {

			$form['errors']['result'][] = __('Error saving item data.');
		}

		$item_id = Core::Database()->GetInsertId();

		if (empty($item_id)) {

			$form['errors']['result'][] = __('Error getting new item id.');
		}

		$q_details = "INSERT INTO {$this->_mAppPrefix}items_details (item_id, i18n_locale, item_title, item_description) VALUES ";

		$q_details_values = array();

		foreach ($app_langs as $lang => $locale) {

			if (isset($itemData["item_title_{$lang}"]) && isset($itemData["item_description_{$lang}"])) {

				$q_details_values[] = "('{$item_id}', '{$locale}', '{$itemData["item_title_{$lang}"]}', '{$itemData["item_description_{$lang}"]}')";
			}
		}

		if (!empty($q_details_values)) {

			$q_details .= implode(", ", $q_details_values);

			$q_details .= " ON DUPLICATE KEY UPDATE item_title = VALUES(item_title), item_description = VALUES(item_description)";

			if (!Core::Database()->Query($q_details)) {
					
				$form['errors']['result'][] = __('Error saving item details.');
			}
		}

		if (!empty($form['errors'])) {

			return $form;
		}

		Core::Router()->Redirect("Items/Admin/Added/id/{$item_id}");

		exit();
	}

	public function Modify($itemId, $itemData, $next = NULL) {

		/**
		 * Data filtering is already done, so we can just go ahead and use it.
		 */

		$form = array(
				'data' => $itemData,
				'errors' => NULL,
		);

		$app_langs = Core::I18n()->GetAvailableLangs();

		if (empty($itemData['item_status'])) {

			$form['errors']['fields']['item_status'] = sprintf(__('The field "%s" is required.'), __('Status'));
		}

		foreach ($app_langs as $lang => $locale) {

			$itemData[$locale]['item_title'] = $itemData["item_title_{$lang}"];
			$itemData[$locale]['item_description'] = $itemData["item_description_{$lang}"];

			if (empty($itemData[$locale]['item_title'])) {

				$form['errors']['fields']["item_title_{$lang}"] = sprintf(__('The field "%s" is required.'), __('Title') . " ({$lang})");
			}
		}

		if (!empty($form['errors'])) {

			return $form;
		}

		$q_main = "UPDATE {$this->_mAppPrefix}items_main SET item_status = '{$itemData['item_status']}' WHERE item_id = '{$itemId}' LIMIT 1";

		if (!Core::Database()->Query($q_main)) {

			$form['errors']['result'][] = __('Error saving item data.');
		}

		$q_details = "INSERT INTO {$this->_mAppPrefix}items_details (item_id, i18n_locale, item_title, item_description) VALUES ";

		$q_details_values = array();

		foreach ($app_langs as $lang => $locale) {

			if (isset($itemData["item_title_{$lang}"]) && isset($itemData["item_description_{$lang}"])) {

				$q_details_values[] = "('{$itemId}', '{$locale}', '{$itemData["item_title_{$lang}"]}', '{$itemData["item_description_{$lang}"]}')";
			}
		}

		if (!empty($q_details_values)) {

			$q_details .= implode(", ", $q_details_values);

			$q_details .= " ON DUPLICATE KEY UPDATE item_title = VALUES(item_title), item_description = VALUES(item_description)";

			if (!Core::Database()->Query($q_details)) {
					
				$form['errors']['result'][] = __('Error saving item details.');
			}
		}

		if (!empty($form['errors'])) {

			return $form;
		}
		
		Core::Router()->Redirect("Items/Admin/Modified/id/{$itemId}{$next}");

		exit();
	}

	public function Delete($itemId, $next = NULL) {

		$q_main = "DELETE FROM {$this->_mAppPrefix}items_main WHERE item_id = '{$itemId}' LIMIT 1";

		if (!Core::Database()->Query($q_main)) {

			return FALSE;
		}

		$q_details = "DELETE FROM {$this->_mAppPrefix}items_details WHERE item_id = '{$itemId}'";


		if (!Core::Database()->Query($q_details)) {

			return FALSE;
		}
		
		Core::Router()->Redirect("Items/Admin/Deleted/id/{$itemId}{$next}");

		exit();return TRUE;
	}
}
?>