<?php
if (!defined('_WEB')) {

	exit();
}

class Controller_Oups_Core extends ControllerApp {

	public function Oups404() {

		$this->Init();

		$data = $this->Module()->GetData($this->_mRequest);

		$data['strings']['title'] = __('Page not found');

		$data['strings']['message'] = __('Sorry!') . ' ' . __('Page not found') . '.';

		header(__('HTTP/1.1 404 Not Found'));
			
		echo $this->GetOutput($data, '_main/error');

		return TRUE;
	}
}
?>