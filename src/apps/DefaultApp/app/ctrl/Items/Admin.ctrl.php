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
	
		$data['item_id'] = !empty($data['query']['id']) ? $data['query']['id'] : NULL;
	
		echo $this->GetOutput($data);
	
		return TRUE;
	}
}
?>
