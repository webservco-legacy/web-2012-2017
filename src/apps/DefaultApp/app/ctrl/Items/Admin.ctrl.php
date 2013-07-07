<?php
if (!defined('_WEB'))
{
	exit();
}

class Controller_Items_Admin extends ControllerApp {

	protected $_mTemplatePrefix = 'admin_';

	public function Items() {

		$this->Init();

		$data = $this->Module()->GetData($this->_mRequest);

		if (!$this->CheckAdminAccess($data)) {

			return FALSE;
		}

		/**
		 * Custom functionality below this line.
		 */

		$data['strings']['title'] = __('Items');
		
		if (!empty($data['query']['filter_status'])) {
			
			$this->Module()->SetCondition('status', $data['query']['filter_status']);
		}

		$this->Module()->SetCondition('i18n_locale', Core::I18n()->GetLocale());

		$data['items'] = $this->Module()->Get();

		echo $this->GetOutput($data);

		return TRUE;
	}

	public function Add() {

		$this->Init();

		$data = $this->Module()->GetData($this->_mRequest);

		if (!$this->CheckAdminAccess($data)) {

			return FALSE;
		}

		/**
		 * Custom functionality below this line.
		 */

		$data['strings']['title'] = __('Item');

		$data['errors'] = NULL;

		if (!empty($_POST)) {

			$data['form'] = $this->Module()->Add($_POST);

		} else {

			$data['form']['data'] = NULL;
		}

		echo $this->GetOutput($data);

		return TRUE;
	}

	public function Added() {

		$this->Init();

		$data = $this->Module()->GetData($this->_mRequest);

		if (!$this->CheckAdminAccess($data)) {

			return FALSE;
		}

		/**
		 * Custom functionality below this line.
		 */

		$data['strings']['title'] = __('Item');

		$data['item_id'] = !empty($data['query']['id']) ? $data['query']['id'] : NULL;

		$data['strings']['message'] = sprintf(__('Item ID %s was succesfully added'), $data['item_id']);

		echo $this->GetOutput($data, 'result');

		return TRUE;
	}

	public function Modify() {

		$this->Init();

		$data = $this->Module()->GetData($this->_mRequest);

		if (!$this->CheckAdminAccess($data)) {

			return FALSE;
		}

		/**
		 * Custom functionality below this line.
		 */

		$data['strings']['title'] = __('Item');

		$data['item_id'] = !empty($data['query']['id']) ? $data['query']['id'] : NULL;

		$data['errors'] = NULL;

		if (!empty($data['item_id'])) {

			if (!empty($_POST)) {
					
				$data['form'] = $this->Module()->Modify($data['item_id'], $_POST);
					
			} else {
					
				$data_main = $this->Module()->GetMain($data['item_id']);
					
				$data_details = $this->Module()->GetDetails($data['item_id']);

				$data['form']['data'] = array_merge($data_main, $data_details);
			}
		}

		echo $this->GetOutput($data);

		return TRUE;
	}

	public function Modified() {

		$this->Init();

		$data = $this->Module()->GetData($this->_mRequest);

		if (!$this->CheckAdminAccess($data)) {

			return FALSE;
		}

		/**
		 * Custom functionality below this line.
		 */

		$data['strings']['title'] = __('Item');

		$data['item_id'] = !empty($data['query']['id']) ? $data['query']['id'] : NULL;

		$data['strings']['message'] = sprintf(__('Item ID %s was succesfully modified.'), $data['item_id']);

		echo $this->GetOutput($data, 'result');

		return TRUE;
	}

	public function Delete() {

		$this->Init();

		$data = $this->Module()->GetData($this->_mRequest);

		if (!$this->CheckAdminAccess($data)) {

			return FALSE;
		}

		/**
		 * Custom functionality below this line.
		 */
		
		$data['strings']['title'] = __('Item');
		
		$data['item_id'] = !empty($data['query']['id']) ? $data['query']['id'] : NULL;

		if (!empty($data['query']['confirm'])) {

			$this->Module()->Delete($data['item_id']);

			return TRUE;
		}

		$data['strings']['message'] = sprintf(__('Please confirm you really want to delete Item ID %s and all related data.'), $data['item_id']);

		echo $this->GetOutput($data);

		return TRUE;
	}

	public function Deleted() {

		$this->Init();

		$data = $this->Module()->GetData($this->_mRequest);

		if (!$this->CheckAdminAccess($data)) {

			return FALSE;
		}

		/**
		 * Custom functionality below this line.
		 */

		$data['strings']['title'] = __('Item');

		$data['item_id'] = !empty($data['query']['id']) ? $data['query']['id'] : NULL;

		$data['strings']['message'] = sprintf(__('Item ID %s was succesfully deleted.'), $data['item_id']);

		echo $this->GetOutput($data, 'deleted');

		return TRUE;
	}
}
?>
