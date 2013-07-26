<?php
if (!defined('_WEB')) {
	exit();
}

class Controller_Content_App extends ControllerApp {

	public function Home() {

		return $this->GetDefaultOutput();
	}

	public function Info() {

		return $this->GetDefaultOutput(__('Info'));
	}

	public function Credits() {

		$this->Init();
		
		$data = $this->Module()->GetData($this->_mRequest);
		
		$data['strings']['title'] = __('Credits');
		
		$data['apps'] = $this->Module()->GetAppList();
		
		$data['app_intro'] = $this->Module()->GetAppIntro($data['webapp']['app_id']);
		
		echo $this->GetOutput($data);
		
		return TRUE;
	}
}
?>
