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

		$q = "SELECT M.item_id, M.item_status = 'on', D.item_title, D.item_description
		FROM items_main AS M
		LEFT JOIN items_details AS D ON (D.item_id = M.item_id)
		WHERE  1
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
}
?>