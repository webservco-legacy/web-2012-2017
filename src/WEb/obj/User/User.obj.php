<?php
/**
 * $Id$
 *
 * User object.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2012, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    WEb
 * @subpackage WebObject
 * @version    $Revision$
 * @since      Version 5
 * @access     public
 * @filesource
 */

/**
 * Security check.
 */
if ( !defined( '_WEB' ) )
{
	/**
	 * Stop script execution.
	 */
	exit();
}

/**
 * User
 *
 * User object.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @package    WEb
 * @subpackage WebObject
 *
 */
class User extends Object
{
	/**
	 * Object properties.
	 *
	 * @var array
	 */
	public $mProp = array(
		'list' => array(
			'bin.user_id' => NULL,
			'user_login' => NULL,
			'user_status' => NULL,
	),
	);

	/**
	 * Object tables.
	 *
	 * @var array
	 */
	protected $_mTable = array( 'list' => 'acl_users', 'store' => 'acl_object_user', 'logins' => 'acl_logins' );

	/**
	 * Initialize the object.
	 *
	 * @return bool
	 */
	public function Init()
	{
		/**
		 * Check if user is already logged in.
		 */
		if ( !empty( $_SESSION[__CLASS__]['Logged'] ) )
		{
			/**
			 * Set user data.
			 */
			return $this->_SetUserData();
		}

		/**
		 * This is not the first load of the page, so we don't check anything else.
		 */
		if ( !empty( $_SESSION[__CLASS__] ) )
		{
			$_SESSION[__CLASS__]['InitialVisit'] = FALSE;
			
			return TRUE;
		}

		/**
		 * Set current stats.
		 *
		 * @var array
		 */
		$_SESSION[__CLASS__]['StatsCurrent'] = array(
				'time'	=> date( 'Y-m-d H:i:s' ),
				'ip'	=> Core::Geo()->GetIp(),
				'agent'	=> ( !empty( $_SERVER['HTTP_USER_AGENT'] ) ) ? $_SERVER['HTTP_USER_AGENT'] : NULL,
				'auto'	=> FALSE,
		);
		
		$_SESSION[__CLASS__]['InitialVisit'] = TRUE;

		/**
		 * Check returning user.
		 *
		 * @var mixed
		 */
		$returning_data = Core::Session()->CheckPersistentSession();

		if ( !empty( $returning_data ) )
		{
			/**
			 * @var string
			 */
			$user_id = $returning_data[0];

			/**
			 * Persistent session token.
			 *
			 * @var string
			 */
			$persistent_token = $returning_data[1];

			/**
			 * Timestamp of persistent session expiring date.
			 *
			 * @var int
			 */
			$persistent_expire = $returning_data[2];

			/**
			 * Check if user is already logged in from another session.
			 */
			if ( $this->IsAlreadyLogged( $user_id ) )
			{
				return FALSE;
			}

			/**
			 * Remove current persistent session.
			 */
			Core::Session()->RemovePersistentSession( $user_id, $persistent_token );

			/**
			 * @var string
			 */
			$user_login = $this->GetLoginName( $user_id );

			/**
			 * @var string
			 */
			$user_salt = Core::Security()->GetSalt( $user_login );

			/**
			 * @var string
			 */
			$new_persistent_token = Core::Security()->GetToken( $user_salt );

			/**
			 * Add new persistent session,
			 * keeping current expire date.
			 */
			Core::Session()->AddPersistentSession( $user_id, $new_persistent_token, $persistent_expire );

			/**
			 * @var string
			 */
			$now = date( 'Y-m-d H:i:s' );

			/**
			 * Record auto login.
			 */
			$this->_RecordLogin( $user_id, NULL, $now );

			/**
			 * Set updated stats time
			 *
			 * @var array
			 */
			$_SESSION[__CLASS__]['StatsCurrent']['time'] = $now;

			/**
			 * Set persistent flag.
			 *
			 * @var bool
			 */
			$_SESSION[__CLASS__]['Persistent'] = TRUE;

			/**
			 * Set persistent statistic.
			 *
			 * @var array
			 */
			$_SESSION[__CLASS__]['StatsCurrent']['auto'] = $new_persistent_token;

			/**
			 * @var array
			 */
			$options['conditions'][] = array ( 'bin.user_id', $user_id );

			return $this->_SetUserData( $options );
		}

		return TRUE;
	}
	
	public function InitialVisit() {
		
		return (!empty($_SESSION[__CLASS__]['InitialVisit'])) ? TRUE : FALSE;
	}

	/**
	 * Check if user is logged in.
	 *
	 * @return bool
	 */
	public function IsLogged()
	{
		return ( !empty( $_SESSION[__CLASS__]['Logged'] ) ) ? $_SESSION[__CLASS__]['Logged'] : FALSE;
	}

	/**
	 * Check if user is logged in and if the session is persistent.
	 *
	 * @return bool
	 */
	public function IsPersistent()
	{
		if ( empty( $_SESSION[__CLASS__]['Logged'] ) )
		{
			return FALSE;
		}

		return  ( !empty( $_SESSION[__CLASS__]['Persistent'] ) ) ? $_SESSION[__CLASS__]['Persistent'] : FALSE;
	}

	/**
	 * Check if user is already logged in
	 * in another session.
	 *
	 * @param string $userId
	 *
	 * @return mixed
	 */
	public function IsAlreadyLogged( $userId )
	{
		/**
		 * @var array
		 */
		$options['conditions'][] = array ( 'bin.user_id', $userId );

		/**
		 * Get timeout expression
		 *
		 * @var string
		 */
		$timeout = date( 'Y-m-d H:i:s', time() - Core::Config()->Get( 'user.login.time' ) * 60 );

		/**
		 * @var array
		 */
		$options['conditions'][] = array ( 'login_date_add', $timeout, 'gte' );

		/**
		 * @var array
		 */
		$data = Core::Database()->RetrieveOne( array( 'login_date_add' ), $this->_mTable['logins'], $options );

		if ( empty( $data ) )
		{
			return FALSE;
		}

		return  $data['login_date_add'];
	}

	/**
	 * Login user.
	 *
	 * @param string $userLogin
	 * @param string $userPass
	 * @param bool $userRemember
	 *
	 * @return bool
	 */
	public function Login( $userLogin, $userPass, $userRemember = FALSE )
	{
		if ( empty( $_SESSION[__CLASS__] ) )
		{
			$this->Init();
		}

		/**
		 * @var bool
		 */
		$login = $this->_LoginUser( $userLogin, $userPass );

		$this->_RecordLogin( $this->_mId, $userLogin );

		if ( TRUE !== $login )
		{
			return FALSE;
		}

		if ( FALSE !== $userRemember )
		{
			/**
			 * @var string
			 */
			$user_salt = Core::Security()->GetSalt( $userLogin );

			/**
			 * @var string
			 */
			$persistent_token = Core::Security()->GetToken( $user_salt );

			/**
			 * Add persistent session.
			 */
			Core::Session()->AddPersistentSession( $this->_mId, $persistent_token );
		}

		return TRUE;
	}

	/**
	 * Logout user.
	 *
	 * @return bool
	 */
	public function Logout()
	{
		/**
		 * If multiple persistent sessions per user is activated,
		 * we will only remove the current persistent session.
		 *
		 * @var string
		 */
		$persistent_token = ( 1 == Core::Config()->Get( 'session.multiple' ) ) ? $_SESSION[__CLASS__]['StatsCurrent']['auto'] : NULL;

		/**
		 * Delete all persistent sessions.
		 */
		Core::Session()->RemovePersistentSession( $this->_mId, $persistent_token );

		/**
		 * Re-initialize session array.
		 */
		$_SESSION = array();

		/**
		 * Destroy current session.
		 */
		session_destroy();

		/**
		 * Set a dummy session cookie.
		 */
		Core::Session()->RemoveCookie( '.session' );

		/**
		 * Set a dummy persistence cookie.
		 */
		Core::Session()->RemoveCookie( '.persistence' );

		/**
		 * @var array
		 */
		$this->mProp = array(
		'list' => array(
			'bin.user_id' => NULL,
			'user_login' => NULL,
			'user_status' => NULL,
		),
		);

		/**
		 * @var string
		 */
		$this->_mId = NULL;

		/**
		 * Re-initialize object.
		 */
		$this->Init();

		return TRUE;
	}

	/**
	 * Get user login name.
	 *
	 * @param string $userId
	 *
	 * @return mixed
	 */
	public function GetLoginName( $userId )
	{
		/**
		 * @var array
		 */
		$options['conditions'][] = array ( 'bin.user_id', $userId );

		/**
		 * @var array
		 */
		$data = Core::Database()->RetrieveOne( array( 'user_login' ), $this->_mTable['list'], $options );

		if ( empty( $data ) )
		{
			return FALSE;
		}

		return  $data['user_login'];
	}

	public function GetFailedLogins( $checkType = NULL, $checkAsset = NULL )
	{
		/**
		 * @var array
		 */
		$options['conditions'][] = array ( 'bin.user_id', '00' );

		if ( !empty( $checkType ) && !empty( $checkAsset ) )
		{
			/**
			 * @var array
			 */
			$options['conditions'][] = array ( $checkType, $checkAsset );
		}

		/**
		 * Get timeout expression
		 *
		 * @var string
		 */
		$timeout = date( 'Y-m-d H:i:s', time() - Core::Config()->Get( 'user.login.timeout' ) * 60 );

		/**
		 * @var array
		 */
		$options['conditions'][] = array ( 'login_date_add', $timeout, 'gte' );

		/**
		 * @var array
		 */
		$data = Core::Database()->Count( 'login_id', $this->_mTable['logins'], $options );

		return ( !empty( $data ) ) ? $data['total'] : FALSE;
	}

	/**
	 * Check and login user.
	 *
	 * @param string $userLogin
	 * @param string $userPass
	 *
	 * @return bool
	 */
	private function _LoginUser( $userLogin, $userPass )
	{
		/**
		 * @var string
		 */
		$user_salt = Core::Security()->GetSalt( $userLogin );

		/**
		 * @var string
		 */
		$user_auth = Core::Security()->GetToken( $user_salt, $userPass );

		/**
		 * @var array
		 */
		$options['conditions'][] = array ( 'user_login', $userLogin );

		/**
		 * @var array
		 */
		$options['conditions'][] = array ( 'user_auth', $user_auth );

		if ( FALSE !== $this->_SetUserData( $options ) )
		{
			/**
			 * Keep existing data but get a new session id.
			 */
			session_regenerate_id( TRUE );

			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Set user details as object properties.
	 *
	 * @param array $options
	 *
	 * @return bool
	 */
	private function _SetUserData( $options = NULL )
	{
		if ( !empty( $_SESSION[__CLASS__]['Prop'] ) )
		{
			/**
			 * Set object data.
			 *
			 * @var array
			 */
			$this->mProp['list'] = array_merge( $this->mProp['list'], $_SESSION[__CLASS__]['Prop'] );

			/**
			 * Set object Id.
			 *
			 * @var string
			 */
			$this->_mId = $_SESSION[__CLASS__]['Prop'][$this->_mKey];

			return TRUE;
		}

		/**
		 * To go further we need to make sure we have options.
		 */
		if ( empty( $options ) )
		{
			return FALSE;
		}

		$data = Core::Database()->RetrieveOne( array( 'bin.user_id', 'user_login', 'user_status' ), $this->_mTable['list'], $options );

		if ( empty( $data ) )
		{
			return FALSE;
		}

		/**
		 * Set object data.
		 *
		 * @var array
		 */
		$this->mProp['list'] = array_merge( $this->mProp['list'], $data );

		/**
		 * Set object Id.
		 *
		 * @var string
		 */
		$this->_mId = $data[$this->_mKey];

		/**
		 * Set session object Id
		 */
		$_SESSION[__CLASS__]['Id'] = $this->_mId;

		/**
		 * Set logged in flag.
		 *
		 * @var bool
		 */
		$_SESSION[__CLASS__]['Logged'] = TRUE;

		/**
		 * Set persistent flag.
		 *
		 * @var bool
		 */
		$_SESSION[__CLASS__]['Persistent'] = FALSE;

		/**
		 * Set session object data.
		 *
		 * @var array
		 */
		$_SESSION[__CLASS__]['Prop'] = $this->mProp['list'];

		if ( empty( $_SESSION[__CLASS__]['StatsPrevious'] ) )
		{
			/**
			 * Set last visit stats
			 */
			$this->_SetPreviousStats();
		}

		return TRUE;
	}

	/**
	 * Set stats about the previous visit.
	 *
	 * @return bool
	 */
	private function _SetPreviousStats()
	{
		/**
		 * @var array
		 */
		$options['conditions'][] = array ( 'bin.user_id', $this->_mId );

		/**
		 * @var array
		 */
		$options['conditions'][] = array ( 'login_date_add', $_SESSION[__CLASS__]['StatsCurrent']['time'], 'lt' );

		/**
		 * @var array
		 */
		$options['order'][] = array( 'login_date_add', 'desc' );

		/**
		 * @var array
		 */
		$data = Core::Database()->RetrieveOne( array( 'login_auto', 'user_ip', 'user_agent', 'login_date_add' ), $this->_mTable['logins'], $options );

		if ( empty( $data ) )
		{
			/**
			 * @var array
			 */
			$_SESSION[__CLASS__]['StatsPrevious'] = array();

			return FALSE;
		}

		/**
		 * @var array
		 */
		$_SESSION[__CLASS__]['StatsPrevious'] = array(
			'time' => $data['login_date_add'],	
			'ip' => long2ip( $data['user_ip'] ),
			'agent' => $data['user_agent'],
			'auto' => ( 'true' == $data['login_auto'] ) ? TRUE : FALSE,
		);

		return TRUE;
	}

	/**
	 * Record login stats.
	 *
	 * If login is failed, the user_id will be 00
	 *
	 * @param string $userId
	 * @param string $userLogin
	 *
	 * @return bool
	 */
	private function _RecordLogin( $userId, $userLogin = NULL, $loginDate = NULL )
	{
		/**
		 * @var string
		 */
		$user_id = ( !empty( $userId ) ) ? $userId : '00';

		/**
		 * @var string
		 */
		$login_auto = ( empty( $userLogin ) ) ? 'true' : 'false';

		/**
		 * @var array
		 */
		$data = array(
			'bin.user_id' => $user_id,
			'user_login' => $userLogin,
			'user_ip' => ip2long( $_SESSION[__CLASS__]['StatsCurrent']['ip'] ),
			'login_auto' => $login_auto,
			'user_agent' => $_SESSION[__CLASS__]['StatsCurrent']['agent'],
		);

		if ( !empty( $loginDate ) )
		{
			/**
			 * @var array
			 */
			$data['login_date_add'] = $loginDate;
		}

		if ( FALSE !== Core::Database()->Create( $data, 'acl_logins' ) )
		{
			/**
			 * Set new login time
			 */
			$_SESSION[__CLASS__]['StatsCurrent']['time'] = ( !empty( $loginDate ) ) ? $loginDate : date( 'Y-m-d H:i:s' );

			return TRUE;
		}

		return FALSE;
	}
}
?>