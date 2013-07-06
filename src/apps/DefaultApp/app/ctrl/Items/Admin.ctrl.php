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

		$this->Module()->SetCondition('i18n_locale', Core::I18n()->GetLocale());

		$data['items'] = $this->Module()->Get();

		echo $this->GetOutput($data);

		return TRUE;

		echo $this->GetOutput($data);

		return TRUE;
	}

	public function Item() {

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
					
				$data['form'] = $this->Module()->SaveItem($data['item_id'], $_POST);
					
			} else {
					
				$data_main = $this->Module()->GetMain($data['item_id']);
					
				$data_details = $this->Module()->GetDetails($data['item_id']);

				$data['form']['data'] = array_merge($data_main, $data_details);
			}
		}

		echo $this->GetOutput($data);

		return TRUE;
	}

	public function ItemSaved() {

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

		$data['strings']['message'] = sprintf(__('Item ID %s was succesfully saved'), $data['item_id']);

		echo $this->GetOutput($data);

		return TRUE;
	}
}
?>
