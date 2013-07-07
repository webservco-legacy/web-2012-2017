<?php
if (!defined('_WEB'))
{
	exit();
}

class Controller_Content_Admin extends ControllerApp {

	protected $_mTemplatePrefix = 'admin_';

	public function Home() {

		$this->Init();

		$data = $this->Module()->GetData($this->_mRequest);

		if (!$this->CheckAdminAccess($data)) {

			return FALSE;
		}

		/**
		 * Custom functionality below this line.
		 */

		$data['strings']['title'] = __('Home');

		echo $this->GetOutput($data);

		return TRUE;
	}
}
?>
