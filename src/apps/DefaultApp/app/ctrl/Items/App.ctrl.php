<?php
if (!defined('_WEB')) {
	exit();
}

class Controller_Items_App extends ControllerApp {

	public function Items() {

		$this->Init();

		$data = $this->Module()->GetData($this->_mRequest);
		
		/**
		 * Custom functionality below this line.
		 */
		
		$data['strings']['title'] = __('Items');

		$this->Module()->SetCondition('i18n_locale', Core::I18n()->GetLocale());

		$this->Module()->SetCondition('status', 'on');

		$data['items'] = $this->Module()->Get();

		echo $this->GetOutput($data);

		return TRUE;
	}

	public function Item() {

		$this->Init();

		$data = $this->Module()->GetData($this->_mRequest);
		
		/**
		 * Custom functionality below this line.
		 */

		$this->Module()->SetCondition('i18n_locale', Core::I18n()->GetLocale());

		$this->Module()->SetCondition('status', 'on');

		$data['item_id'] = !empty($data['query']['id']) ? $data['query']['id'] : NULL;

		$data['item'] = !empty($data['item_id']) ? $this->Module()->Get($data['item_id']) : NULL;
		
		if (!empty($data['item']['item_title'])) {
			
			$data['strings']['title'] = $data['item']['item_title'];
		}

		echo $this->GetOutput($data);

		return TRUE;
	}
}
?>
