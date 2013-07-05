<?php
if (!defined('_WEB')) {
	exit();
}

class Controller_Content_App extends ControllerApp {
	
	public function Home() {
		
		$this->InitController();
		
		$data = $this->Module()->GetDefaultData($this->_mRequest);
		
		echo $this->GetOutput($data);
		
		return TRUE;
	}
}
?>
