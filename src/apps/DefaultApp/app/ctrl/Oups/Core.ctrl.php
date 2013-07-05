<?php
if (!defined('_WEB')) {

	exit();
}

class Controller_Oups_Core extends ControllerApp {

	public function Oups404() {

		$this->InitController();

		$data = $this->Module()->GetDefaultData($this->_mRequest);

		$data['title'] = __('Page not found');

		$data['message'] = __('Sorry!') . ' ' . __('Page not found') . '.';

		header(__('HTTP/1.1 404 Not Found'));
			
		echo $this->GetOutput($data, 'error');

		return TRUE;
	}
}
?>