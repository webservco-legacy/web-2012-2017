<?php
/**
 * $Id$
 *
 * App controller.
 *
 * Module: Users
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2013, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    DefaultApp
 * @subpackage UsersModule
 * @version    $Revision$
 * @since      Version 7
 * @access     public
 * @filesource
 */
if (!defined('_WEB')) {
	exit();
}

/**
 * Controller_Users_App
 * App Controller for Users module.
 *
 * @author Radu Maier <radu@webserv.co>
 * @package DefaultApp
 * @subpackage UsersModule
 */
class Controller_Users_App extends ControllerApp {

	/**
	 * Account.
	 *
	 * @return mixed
	 */
	public function Account() {

		$this->Init();
		
		$data = $this->Module()->GetData($this->_mRequest);
		
		/**
		 * Custom functionality below this line.
		 */
		
		$data['strings']['title'] = __('Account');
		
		if (!empty($data['user']['Logged'])) {
			
			$template = 'account';
		} else {
			
			$data['strings']['title'] = __('Login');
			
			$data['form'] = $this->Module()->GetLoginForm($data['info']['url_next']);
			
			$template = 'login';
		}
		
		echo $this->GetOutput($data, $template);
		
		return TRUE;
	}

	public function Register() {

		$this->Init();
		
		$data = $this->Module()->GetData($this->_mRequest);
		
		if (!empty($data['user']['Logged'])) {
			
			Core::Router()->Redirect('Users/App/Account');
			
			exit();
		} else {
			
			$data['strings']['title'] = __('Register');
			
			$data['strings']['description'] = __('Please fill this data in order to complete the registration.');
			
			$data['form'] = $this->Module()->GetRegisterForm();
		}
		
		echo $this->GetOutput($data);
		
		return TRUE;
	}

	public function ResetPassword() {

		$this->Init();
		
		$data = $this->Module()->GetData($this->_mRequest);
		
		/**
		 * Custom functionality below this line.
		 */
		
		$data['strings']['title'] = __('Reset password');
		
		if (!empty($data['user']['Logged'])) {
			
			Core::Router()->Redirect('Users/App/Account');
			
			exit();
		} else {
			
			$data['form'] = $this->Module()->GetResetPasswordForm();
		}
		
		echo $this->GetOutput($data);
		
		return TRUE;
	}

	public function ChangePassword() {

		$this->Init();
		
		$data = $this->Module()->GetData($this->_mRequest);
		
		/**
		 * Custom functionality below this line.
		 */
		
		$data['strings']['title'] = __('Reset password');
		
		if (empty($data['user']['Logged'])) {
			
			Core::Router()->Redirect('Users/App/Account');
			
			exit();
		} else {
			
			if (!empty($data['user']['Prop']['user_social_id'])) {
				
				Core::Router()->Redirect('Users/App/Account');
				
				exit();
			}
			
			$data['form'] = $this->Module()->GetChangePasswordForm($data['user']['Id'], $data['user']['Prop']['user_email']);
		}
		
		echo $this->GetOutput($data);
		
		return TRUE;
	}

	public function PasswordDone() {

		$this->Init();
		
		$data = $this->Module()->GetData($this->_mRequest);
		
		/**
		 * Custom functionality below this line.
		 */
		
		$data['strings']['title'] = __('Change password');
		
		echo $this->GetOutput($data);
		
		return TRUE;
	}

	public function Edit() {

		$this->Init();
		
		$data = $this->Module()->GetData($this->_mRequest);
		
		/**
		 * Custom functionality below this line.
		 */
		
		$data['strings']['title'] = __('Edit account');
		
		if (empty($data['user']['Logged'])) {
			
			Core::Router()->Redirect('Users/App/Account');
			
			exit();
		} else {
			
			$data['form'] = $this->Module()->GetEditForm($data['user']['Id'], $data['user']['Prop']);
		}
		
		echo $this->GetOutput($data);
		
		return TRUE;
	}

	/**
	 * Login error.
	 *
	 * @return mixed
	 */
	public function LoginError() {

		$this->Init();
		
		$data = $this->Module()->GetData($this->_mRequest);
		
		/**
		 * Custom functionality below this line.
		 */
		
		$data['strings']['title'] = __('Login error');
		
		$data['strings']['error_message'] = __('Login error') . ': ' . __('Please make sure your credentials are correct.');
		
		echo $this->GetOutput($data, 'login');
	}

	/**
	 * Logout.
	 *
	 * @return mixed
	 */
	public function Logout() {

		$this->Init();
		
		$data = $this->Module()->GetData($this->_mRequest);
		
		if (!empty($_SESSION['FacebookApi']['logout_url'])) {
			
			Core::Router()->Redirect("{$_SESSION['FacebookApi']['logout_url']}");
			
			exit();
		}
		
		Core::User()->Logout();
		
		Core::Router()->Redirect('Users/App/LogoutDone');
		
		exit();
	}

	public function LogoutDone() {

		$this->Init();
		
		$data = $this->Module()->GetData($this->_mRequest);
		
		/**
		 * Custom functionality below this line.
		 */
		
		$data['strings']['message'] = __('You have been succesfully logged out.');
		
		echo $this->GetOutput($data, 'logout');
	}

	/**
	 * Display captcha image.
	 *
	 * @return mixed
	 */
	public function Captcha() {

		/**
		 * Controller initializations needed so we have needed configs available for captcha lib.
		 */
		$this->Init();
		
		/**
		 * Custom functionality below this line.
		 */
		
		echo Core::Captcha()->GetImage();
		
		return TRUE;
	}

	public function Facebook() {

		$this->Init();
		
		$data = $this->Module()->GetData($this->_mRequest);
		
		$_SESSION['FacebookApi']['user_id'] = Core::FacebookApi()->GetUserId();
		
		$data['facebook']['errors'] = array();
		
		if (!empty($data['query']['error']) && !empty($data['query']['error_description'])) {
			
			$data['facebook']['errors'][$data['query']['error']] = $data['query']['error_description'];
		}
		
		if (!empty($_SESSION['FacebookApi']['user_id'])) {
			
			try {
				
				$_SESSION['FacebookApi']['logout_url'] = Core::FacebookApi()->GetLogoutUrl("{$data['url']['www']}Users/App/FacebookLogout/");
				
				$data['facebook']['profile'] = Core::FacebookApi()->GetProfile();
			} catch (FacebookApiException $e) {
				
				$data['facebook']['login_url'] = Core::FacebookApi()->GetLoginUrl("{$data['url']['www']}Users/App/Facebook/");
				
				$data['facebook']['errors'][$e->getType()] = $e->getMessage();
			}
		} else {
			
			$data['facebook']['login_url'] = Core::FacebookApi()->GetLoginUrl("{$data['url']['www']}Users/App/Facebook/");
		}
		
		// echo '<pre>';
		// print_r($_SESSION);
		// echo '<hr />';
		// print_r($data['facebook']);
		return; // XXX
	}

	public function FacebookLogin() {

		$this->Init();
		
		$data = $this->Module()->GetData($this->_mRequest);
		
		if (!empty($data['user']['Logged'])) {
			
			Core::Router()->Redirect('Users/App/Account');
			
			exit();
		}
		
		$_SESSION['FacebookApi']['user_id'] = Core::FacebookApi()->GetUserId();
		
		$data['facebook']['errors'] = array();
		
		if (!empty($data['query']['error']) && !empty($data['query']['error_description'])) {
			
			$data['facebook']['errors'][$data['query']['error']] = $data['query']['error_description'];
		}
		
		if (!empty($_SESSION['FacebookApi']['user_id'])) {
			
			try {
				
				$_SESSION['FacebookApi']['logout_url'] = Core::FacebookApi()->GetLogoutUrl("{$data['url']['www']}Users/App/FacebookLogout/");
				
				$data['facebook']['profile'] = Core::FacebookApi()->GetProfile();
				
				$_SESSION['FacebookApi']['data']['main'] = array(
						'id' => $data['facebook']['profile']['id'],
						'email' => $data['facebook']['profile']['email'] 
				);
				
				if (!empty($data['facebook']['profile']['location']['name'])) {
					
					$location = explode(',', $data['facebook']['profile']['location']['name']);
					
					if (!empty($location[0])) {
						
						$_SESSION['FacebookApi']['data']['form']['city'] = trim($location[0]);
					}
					
					if (!empty($location[1])) {
						
						$_SESSION['FacebookApi']['data']['form']['country_code'] = Core::Geo()->GetCountryCode(trim($location[1]));
					}
				}
				
				$_SESSION['FacebookApi']['data']['custom'] = array(
						'name_first' => $data['facebook']['profile']['first_name'],
						'name_last' => $data['facebook']['profile']['last_name'] 
				);
				
				$this->Module()->DoFacebookLogin();
				
				return;
			} catch (FacebookApiException $e) {
				
				$data['facebook']['login_title'] = __('Please log in again');
				
				$data['facebook']['login_url'] = Core::FacebookApi()->GetLoginUrl("{$data['url']['www']}Users/App/FacebookLogin/");
				
				$data['facebook']['errors'][$e->getType()] = $e->getMessage();
			}
		} else {
			
			$data['facebook']['login_title'] = __('Facebook login');
			
			$data['facebook']['login_url'] = Core::FacebookApi()->GetLoginUrl("{$data['url']['www']}Users/App/FacebookLogin/");
			
			if (empty($data['facebook']['errors'])) {
				
				Core::Router()->Redirect($data['facebook']['login_url']);
				
				exit();
			}
		}
		
		echo $this->GetOutput($data);
		
		return TRUE;
	}

	public function FacebookRegister() {

		$this->Init();
		
		$data = $this->Module()->GetData($this->_mRequest);
		
		if (!empty($data['user']['Logged'])) {
			
			Core::Router()->Redirect('Users/App/Account');
			
			exit();
		}
		
		if (empty($_SESSION['FacebookApi']['user_id'])) {
			
			Core::Router()->Redirect('Users/App/FacebookLogin');
			
			exit();
		}
		
		$data['strings']['title'] = __('Facebook Register');
		
		$data['strings']['description'] = __('Please fill this data in order to complete the registration.');
		
		$data['form'] = $this->Module()->GetRegisterFacebookForm();
		
		$data['is_social_registration'] = TRUE;
		
		$template = 'register';
		
		echo $this->GetOutput($data, $template);
		
		return TRUE;
	}

	public function FacebookLogout() {

		Core::FacebookApi()->Logout();
		
		Core::Router()->Redirect('Users/App/Logout');
		
		exit();
	}

	/**
	 * Login error.
	 *
	 * @return mixed
	 */
	public function FacebookRegisterError() {

		$this->Init();
		
		$data = $this->Module()->GetData($this->_mRequest);
		
		//Core::FacebookApi()->Logout();
		
		$data['strings']['title'] = __('Login error');
		
		$data['strings']['error_message'] = __('Login error') . ': ' . __('This email is already registered. Please use the options to "Login" or "Reset password".');
		
		$data['form'] = $this->Module()->GetLoginForm();
		
		//echo '<pre>'; print_r($_SESSION); exit();
		
		echo $this->GetOutput($data, 'login');
	}
}
?>
