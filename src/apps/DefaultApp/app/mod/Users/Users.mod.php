<?php
/**
 * $Id$
 *
 * Users module.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2012, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    DefaultApp
 * @subpackage UsersModule
 * @version    $Revision$
 * @since      Version 5
 * @access     public
 * @filesource
 */

/**
 * Security check.
 */
if (!defined('_WEB')) {
	/**
	 * Stop script execution.
	 */
	exit();
}

/**
 * Module_Users
 *
 * Model class for Users module.
 *
 * @author Radu Maier <radu@webserv.co>
 * @package RaduApp
 * @subpackage UsersModule
 *            
 */
class Module_Users extends ModuleApp {

	/**
	 * Handles login functionality.
	 *
	 * @return mixed
	 */
	public function GetLoginForm($nextUrl = NULL) {

		Core::Form()->SetMethod('post');
		
		$login_form = Core::Config()->Get('users.form.login');
		
		if (empty($login_form)) {
			
			return FALSE;
		}
		
		Core::Form()->SetData($login_form);
		
		/**
		 *
		 * @var array
		 */
		$form['meta'] = Core::Form()->GetMeta();
		
		/**
		 *
		 * @var array
		 */
		$form['errors'] = array();
		
		if (!empty($_POST)) {
			/**
			 *
			 * @var bool
			 */
			$result_validation = Core::Form()->Validate();
			
			if (FALSE !== $result_validation) {
				/**
				 *
				 * @var array
				 */
				$form['data'] = Core::Form()->GetFilteredData();
				
				/**
				 *
				 * @var bool
				 */
				$result_login = Core::User()->Login($form['data']['email'], $form['data']['password'], (bool) $form['data']['remember']);
				
				if (TRUE !== $result_login) {
					Core::Captcha()->GetNewToken();
					
					Core::Router()->Redirect('Users/App/LoginError');
					
					exit();
				} else {
					/**
					 * perform any aditional user initialization.
					 */
					
					$redirect = !empty($nextUrl) ? $nextUrl : 'Users/App/Account';
					
					Core::Router()->Redirect($redirect);
					
					exit();
				}
			} else {
				Core::Captcha()->GetNewToken();
				
				/**
				 *
				 * @var array
				 */
				$form['data'] = Core::Form()->GetData();
				
				/**
				 *
				 * @var array
				 */
				$form['errors'] = Core::Form()->GetErrors();
			}
		} else {
			/**
			 *
			 * @var array
			 */
			$form['data'] = Core::Form()->GetData();
		}
		
		return $form;
	}

	public function GetRegisterForm() {

		Core::Form()->SetMethod('post');
		
		Core::Form()->SetData(Core::Config()->Get('users.form.register'));
		
		$departments = Core::Geo()->GetDepartments('IT');
		
		if (!empty($departments)) {
			
			Core::Form()->SetMeta('department', 'options', $departments);
		}
		
		$countries = Core::Geo()->GetCountries();
		
		if (!empty($countries)) {
			
			Core::Form()->SetMeta('country', 'options', $countries);
		}
		
		$form['meta'] = Core::Form()->GetMeta();
		
		$form['errors'] = array();
		
		if (!empty($_POST)) {
			
			$result_validation = Core::Form()->Validate();
			
			if (FALSE !== $result_validation) {
				
				$form['data'] = Core::Form()->GetFilteredData();
				
				$email_exists = Core::Database()->ValueExists("{$this->_mAppPrefix}acl_users", 'user_email', $form['data']['email']);
				
				if ($email_exists) {
					
					$form['errors']['email'][] = sprintf(Core::Config()->Get('form.error.alreadyregistered.text'), $form['meta']['email']['title']);
				}
				
				if (empty($form['errors'])) {
					
					$user_salt = Core::Security()->GetSalt($form['data']['email']);
					
					$user_auth = Core::Security()->GetToken($user_salt, $form['data']['password']);
					
					$q = "INSERT INTO {$this->_mAppPrefix}acl_users (user_email, user_auth, owner_id, user_date_add) VALUES
					('{$form['data']['email']}', '{$user_auth}', 1, NOW())";
					
					Core::Database()->Query($q);
					
					$user_id = Core::Database()->GetInsertId();
					
					$qc = "INSERT INTO {$this->_mAppPrefix}acl_users_custom (user_id_key, user_name_first, user_name_last, user_city, user_department, user_country) VALUES
					($user_id, '{$form['data']['name_first']}', '{$form['data']['name_last']}', '{$form['data']['city']}', '{$form['data']['department']}', '{$form['data']['country']}')";
					
					Core::Database()->Query($qc);
					
					if (empty($user_id)) {
						
						/**
						 * Registration error.
						 */
						
						echo 'Registration error, please try again later.';
						
						exit();
					}
					
					Core::User()->Login($form['data']['email'], $form['data']['password']);
					
					$result_login = Core::User()->Login($form['data']['email'], $form['data']['password']);
					
					if (TRUE !== $result_login) {
						/**
						 * Autologin error.
						 */
						
						echo 'Autologin error, please try again later.';
						
						exit();
					}
					
					Core::Router()->Redirect('Users/App/Account');
				} else {
					
					$form['data'] = Core::Form()->GetData();
				}
			} else {
				
				$form['data'] = Core::Form()->GetData();
				
				$form['errors'] = Core::Form()->GetErrors();
			}
		} else {
			
			$form['data'] = Core::Form()->GetData();
		}
		
		return $form;
	}

	public function GetResetPasswordForm() {

		Core::Form()->SetMethod('post');
		
		Core::Form()->SetData(Core::Config()->Get('users.form.resetpassword'));
		
		$form['meta'] = Core::Form()->GetMeta();
		
		$form['errors'] = array();
		
		if (!empty($_POST)) {
			
			$result_validation = Core::Form()->Validate();
			
			if (FALSE !== $result_validation) {
				
				$form['data'] = Core::Form()->GetFilteredData();
				
				$email_exists = Core::Database()->ValueExists("{$this->_mAppPrefix}acl_users", 'user_email', $form['data']['email']);
				
				if (!$email_exists) {
					
					$form['errors']['email'][] = sprintf(Core::Config()->Get('form.error.notregistered.text'), $form['meta']['email']['title']);
				}
				
				if (empty($form['errors'])) {
					
					$new_pass = rand(11111, 32767);
					
					$user_salt = Core::Security()->GetSalt($form['data']['email']);
					
					$user_auth = Core::Security()->GetToken($user_salt, $new_pass);
					
					$q = "UPDATE {$this->_mAppPrefix}acl_users SET user_auth = '{$user_auth}' WHERE user_email = '{$form['data']['email']}' LIMIT 1";
					
					Core::Database()->Query($q);
					
					$app_name = __(Core::Config()->Get('app.title'), 'App');
					
					$mail_subject = "{$app_name}: Password reset";
					
					$mail_message = "{$app_name} password reset.<br />You new password is <b>{$new_pass}</b>";
					
					$this->SendMail($form['data']['email'], $mail_subject, $mail_message);
					
					Core::Router()->Redirect('Users/App/ResetPasswordDone');
				} else {
					
					$form['data'] = Core::Form()->GetData();
				}
			} else {
				
				$form['data'] = Core::Form()->GetData();
				
				$form['errors'] = Core::Form()->GetErrors();
			}
		} else {
			
			$form['data'] = Core::Form()->GetData();
		}
		
		return $form;
	}

	public function Get($userId = NULL, $i18nLocale = NULL) {

		$data = array();
		
		$i18n_locale = !empty($i18nLocale) ? $i18nLocale : Core::I18n()->GetLocale();
		
		$q_conditions = NULL;
		
		$q_limit = NULL;
		
		if (!empty($userId)) {
			
			$q_conditions .= "AND user_id = '{$userId}'";
			
			$q_limit = "LIMIT 1";
		}
		
		$q = "SELECT user_id, user_email, user_nickname, user_status, user_name_first, user_name_last, user_city, user_department, user_country
		FROM {$this->_mAppPrefix}acl_object_user
		WHERE 1
		{$q_conditions}
		ORDER BY user_name_last, user_name_first
		{$q_limit}";
		
		$rs = Core::Database()->Query($q);
		
		$t = $rs->num_rows;
		
		if (0 < $t) {
			
			if ((1 == $t) && !empty($userId)) {
				
				return $rs->fetch_assoc();
			}
			
			while ($r = $rs->fetch_assoc()) {
				
				$data[$r['user_id']] = $r;
			}
		}
		
		return $data;
	}
}
?>