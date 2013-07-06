<?php
if (!defined('_WEB'))
{
	exit();
}

class Controller_Users_Admin extends ControllerApp {

	protected $_mTemplatePrefix = 'admin_';

	public function Users() {

		$this->Init();

		$data = $this->Module()->GetData($this->_mRequest);

		$data['strings']['title'] = __('Users');

		if (!$this->CheckAdminAccess($data)) {

			return FALSE;
		}
		
		/**
		 * Custom functionality below this line.
		 */

		$data['items'] = $this->Module()->Get();

		echo $this->GetOutput($data);

		return TRUE;
	}

	public function User() {

		$this->Init();

		$data = $this->Module()->GetData($this->_mRequest);

		if (!$this->CheckAdminAccess($data)) {

			return FALSE;
		}
		
		$data['strings']['title'] = __('User');
		
		$data['user_id'] = !empty($data['query']['id']) ? $data['query']['id'] : 0;
		
		/**
		 * Custom functionality below this line.
		 */

		$data['item'] = $this->Module()->Get($data['user_id']);

		echo $this->GetOutput($data);

		return TRUE;
	}
}
?>
