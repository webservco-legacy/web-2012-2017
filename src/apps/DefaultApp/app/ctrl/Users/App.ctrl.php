<?php
if (!defined('_WEB'))
{
	exit();
}

class Controller_Users_App extends ControllerApp
{
	/**
	 * Account.
	 *
	 * @return mixed
	 */
	public function Account()
	{
		$this->InitController();

		$data = $this->Module()->GetDefaultData($this->_mRequest);

		$data['title'] = __('Account');

		if (Core::User()->IsLogged()) {
			
			$template = 'account';
			
		} else {
			
			$data['title'] = __('Login');
			
			$data['form'] = $this->Module()->GetLoginForm();
			
			$template = 'login';
		}

		echo $this->GetOutput($data, $template);

		return TRUE;
	}

	public function Register() {

		$this->InitController();

		$data = $this->Module()->GetDefaultData($this->_mRequest);

		$data['title'] = __('Register');

		if (Core::User()->IsLogged()) {

			$template = 'account';

		} else {

			$data['form'] = $this->Module()->GetRegisterForm();

			$template = 'register';
		}

		echo $this->GetOutput($data, $template);

		return TRUE;
	}

	public function ResetPassword() {

		$this->InitController();

		$data = $this->Module()->GetDefaultData($this->_mRequest);

		$data['title'] = __('Reset password');

		if (Core::User()->IsLogged()) {

			$template = 'account';

		} else {

			$data['form'] = $this->Module()->GetResetPasswordForm();

			$template = 'reset_password';
		}

		echo $this->GetOutput($data, $template);

		return TRUE;
	}

	public function ResetPasswordDone() {

		$this->InitController();

		$data = $this->Module()->GetDefaultData($this->_mRequest);

		$data['title'] = __('Reset password');

		echo $this->GetOutput($data, 'reset_password_done');

		return TRUE;

	}

	/**
	 * Login error.
	 *
	 * @return mixed
	 */
	public function LoginError()
	{
		$this->InitController();

		$data = $this->Module()->GetDefaultData($this->_mRequest);

		$data['title'] = __('Login error');

		$data['error_message'] = __('Login error') . ': ' . __('Please make sure your credentials are correct.');

		echo $this->GetOutput($data,  'login');
	}

	/**
	 * Logout.
	 *
	 * @return mixed
	 */
	public function Logout()
	{
		Core::User()->Logout();

		Core::Router()->Redirect('Users/App/Account');

		exit();
	}

	/**
	 * Display captcha image.
	 *
	 * @return mixed
	 */
	public function Captcha()
	{
		/**
		 * Needed so we have needed configs available for captcha lib.
		 */
		$this->InitController();

		echo Core::Captcha()->GetImage();

		return TRUE;
	}
}
?>
