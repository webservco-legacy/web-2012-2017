<?php
if (!defined('_WEB')) {
	exit();
}

class Controller_Content_App extends ControllerApp {

	public function Home() {

		$this->Init();

		$data = $this->Module()->GetData($this->_mRequest);

		echo $this->GetOutput($data);

		return TRUE;
	}
}
?>
