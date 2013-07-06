<?php
if (!defined('_WEB'))
{
	exit();
}

class Controller_Users_Admin extends ControllerApp {

	protected $_mTemplatePrefix = 'admin_';

	public function Users() {

		$this->InitController();

		$data = $this->Module()->GetDefaultData($this->_mRequest);

		$data['title'] = __('Users');

		if (!$this->CheckAdminAccess($data)) {

			return FALSE;
		}

		$data['items'] = $this->Module()->Get();

		echo $this->GetOutput($data);

		return TRUE;
	}

	public function User() {

		$this->InitController();

		$data = $this->Module()->GetDefaultData($this->_mRequest);

		$data['user_id'] = !empty($this->_mRequest['query']['id']) ? $this->_mRequest['query']['id'] : 0;

		$data['title'] = __('User');

		if (!$this->CheckAdminAccess($data)) {

			return FALSE;
		}

		$data['item'] = $this->Module()->Get($data['user_id']);

		echo $this->GetOutput($data);

		return TRUE;
	}
}
?>
