<?php
/**
 * $Id$
 *
 * SMTP library.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2012, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    WEb
 * @subpackage WebSmtp
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
 * Smtp
 * 
 * SMTP library.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @package    WEb
 * @subpackage WebSmtp
 *
 */
class Smtp extends Library
{
	/**
	 * Should we use SSL?
	 *
	 * @var bool
	 */
	protected $_mSsl;

	/**
	 * Stores server name.
	 *
	 * @var string
	 */
	protected $_mServer;

	/**
	 * Stores server port.
	 *
	 * @var int
	 */
	protected $_mPort;

	/**
	 * Stores connection timeout.
	 *
	 * @var int
	 */
	protected $_mTimeout;

	/**
	 * Stores username.
	 *
	 * @var string
	 */
	protected $_mUser;

	/**
	 * Stores password.
	 *
	 * @var string
	 */
	protected $_mPassword;

	/**
	 * Stores application hostname.
	 *
	 * @var string
	 */
	protected $_mHost;

	/**
	 * Stores connection resource.
	 *
	 * @var mixed
	 */
	protected $_mConnection;

	/**
	 * Stores end of line setting.
	 *
	 * @var string
	 */
	protected $_mEol;

	/**
	 * Stores SMTP error codes.
	 *
	 * @var array
	 */
	protected $_mErrorCodes;

	/**
	 * Implement GetInstance() method.
	 *
	 * This is required for PHP < 5.3 compatibility ( no late static binding )
	 *
	 * @param string $className class name to instantiate
	 *
	 * @return object
	 */
	public static function GetInstance( $className = __CLASS__ )
	{
		/**
		 * Call parent method with current class name as parameter.
		 */
		return parent::_GetInstance( $className );
	}

	/**
	 * Set default data.
	 *
	 * @return bool
	 */
	public function SetDefaults()
	{
		/**
		 * @var array
		 */
		$config = Core::Config()->Get( 'smtp' );

		/**
		 * @var bool
		 */
		$this->_mSsl = ( 1 == $config['ssl'] ) ? TRUE: FALSE;

		/**
		 * @var string
		 */
		$this->_mServer = ( !empty( $config['server'] ) ) ? $config['server'] : 'localhost';

		/**
		 * @var int
		 */
		$this->_mPort  = ( !empty( $config['port'] ) ) ? $config['port'] : 25;

		/**
		 * @var int
		 */
		$this->_mTimeout = ( !empty( $config['timeout'] ) ) ? $config['timeout'] : 45;

		/**
		 * @var string
		 */
		$this->_mUser = ( !empty( $config['user'] ) ) ? $config['user'] : NULL;

		/**
		 * @var string
		 */
		$this->_mPassword = ( !empty( $config['password'] ) ) ? $config['password'] : NULL;

		/**
		 * @var string
		 */
		$this->_mHost = ( !empty( $config['host'] ) ) ? $config['host'] : 'localhost';

		/**
		 * @var string
		 */
		$this->_mEol = "\r\n";

		/**
		 * @var array
		 */
		$this->_mErrorCodes = array( 421, 450, 451, 452, 500, 501, 502, 503, 504, 521, 530, 550, 551, 552, 553, 554, 555 );

		return TRUE;
	}

	/**
	 * Send mail.
	 *
	 * @param array $msgTo
	 * @param string $msgFrom
	 * @param string $msgSubject
	 * @param string $msgBody
	 * @param array $msgHeaders
	 *
	 * @return bool
	 */
	public function Send( $msgTo, $msgFrom, $msgSubject, $msgBody, $msgHeaders )
	{
		/**
		 * Check if default data has been set.
		 */
		if ( empty( $this->_mEol ) )
		{
			$this->SetDefaults();
		}

		/**
		 * Check if connection is already open.
		 *
		 * We don't need to check again if it was opened successfully.
		 */
		if ( empty( $this->_mConnection ) )
		{
			$this->_Connect();
		}

		/**
		 * Try to say hi to the server.
		 */
		if ( TRUE !== $this->_Talk( "EHLO {$this->_mHost}" ) )
		{
			return FALSE;
		}

		/**
		 * Check if we should use authentication.
		 */
		if ( !empty( $this->_mUser ) && !empty( $this->_mPassword ) )
		{
			if ( TRUE !== $this->_Talk( 'AUTH LOGIN' ) )
			{
				return FALSE;
			}

			if ( TRUE !== $this->_Talk( base64_encode( $this->_mUser ), __( 'username', 'WEb' ) ) )
			{
				return FALSE;
			}

			if ( TRUE !== $this->_Talk( base64_encode( $this->_mPassword ), __( 'password', 'WEb' ) ) )
			{
				return FALSE;
			}
		}

		/**
		 * Send sender data.
		 */
		if ( TRUE !== $this->_Talk( "MAIL FROM: <{$msgFrom}>" ) )
		{
			return FALSE;
		}

		/**
		 * Check if we have only one recipient.
		 */
		if ( !is_array( $msgTo ) )
		{
			/**
			 * Send recipient data.
			 */
			if ( TRUE !== $this->_Talk( "RCPT TO: <{$msgTo}>" ) )
			{
				return FALSE;
			}
		}
		else
		{
			foreach ( $msgTo as $rcpt_address => $rcpt_name )
			{
				/**
				 * Send recipient data.
				 */
				if ( TRUE !== $this->_Talk( "RCPT TO: <{$rcpt_address}>" ) )
				{
					return FALSE;
				}
			}
		}

		/**
		 * @var string
		 */
		$msg_data = $this->_GetMessageData( $msgSubject, $msgBody, $msgHeaders );

		/**
		 * Send data start signal.
		 */
		if ( TRUE !== $this->_Talk( 'DATA' ) )
		{
			return FALSE;
		}

		/**
		 * Send actual message data.
		 */
		if ( TRUE !== $this->_Talk( $msg_data ) )
		{
			return FALSE;
		}

		/**
		 * Send reset command.
		 */
		if ( TRUE !== $this->_Talk( 'RSET' ) )
		{
			return FALSE;
		}

		/**
		 * Close server connection.
		 */
		$this->_Disconnect();

		return TRUE;
	}

	/**
	 * Open connection to SMTP server.
	 *
	 * @return bool
	 */
	private function _Connect()
	{
		if ( !empty( $this->_mConnection ) )
		{
			return FALSE;
		}

		/**
		 * Check if default data has been set.
		 */
		if ( empty( $this->_mEol ) )
		{
			$this->SetDefaults();
		}

		if ( empty( $this->_mServer ) )
		{
			Core::Log()->Write( __( 'SMTP Library: Can not connect to server. Empty settings.', 'WEb' ), 'error' );

			return FALSE;
		}

		/**
		 * @var string
		 */
		$conn_prefix = ( FALSE !== $this->_mSsl ) ? 'ssl://' : NULL;

		Core::Log()->Write( sprintf( __( 'SMTP Library: Connecting to server "%s".', 'WEb' ), $this->_mServer ), 'debug' );

		/**
		 * @var int
		 */
		$err_no = 0;

		/**
		 * @var string
		 */
		$err_str = NULL;

		$this->_mConnection = fsockopen( $conn_prefix . $this->_mServer, $this->_mPort, $err_no, $err_str, $this->_mTimeout );

		if ( empty( $this->_mConnection ) )
		{
			/**
			 * @var string
			 */
			$err_msg = sprintf( __( 'SMTP Library: Error connecting to server "%s".', 'WEb' ), $this->_mServer );

			Core::Log()->Write( $err_msg, 'error' );

			/**
			 * @var string
			 */
			$dbg_msg = sprintf( __( 'SMTP Library: Error returned: %s %s.', 'WEb' ), $err_no, $err_str );

			Core::Log()->Write( $dbg_msg, 'debug' );
		}

		/**
		 * @var int
		 */
		$code = $this->_GetResponse();

		if ( in_array( $code, $this->_mErrorCodes ) )
		{
			/**
			 * @var string
			 */
			$err_msg = sprintf( __( 'SMTP Library: Error connecting to server "%s".', 'WEb' ), $this->_mServer );

			Core::Log()->Write( $err_msg, 'error' );

			return FALSE;
		}

		/**
		 * Set session connection timeout.
		 */
		stream_set_timeout( $this->_mConnection, $this->_mTimeout );

		return TRUE;
	}

	/**
	 * Close connection to SMTP server.
	 *
	 * @return bool
	 */
	private function _Disconnect()
	{
		if ( empty( $this->_mConnection ) )
		{
			return FALSE;
		}

		$this->_Talk( 'QUIT' );

		fclose( $this->_mConnection );

		Core::Log()->Write( __( 'SMTP Library: Closed server connection.', 'WEb' ), 'debug' );

		return TRUE;
	}

	/**
	 * Communicate with SMTP server.
	 *
	 * If log message is empty, the command will be listed in the log
	 *
	 * @param string $command
	 * @param string $logMessage
	 *
	 * @return bool
	 */
	private function _Talk( $command, $logMessage = NULL )
	{
		if ( empty( $this->_mConnection ) )
		{
			Core::Log()->Write( __( 'SMTP Library: Error communicating to server. No active connection.', 'WEb' ), 'error' );

			return FALSE;
		}

		/**
		 * @var string
		 */
		$log_message = ( !empty( $logMessage ) ) ? $logMessage : $command;

		/**
		 * Check if we have a multi-line command.
		 */
		if ( is_array( $command ) )
		{
			foreach ( $command as $command_line )
			{
				Core::Log()->Write( sprintf( __( 'SMTP Library: Sending "%s".', 'WEb' ), $command_line ), 'debug' );

				fwrite( $this->_mConnection, $command_line . $this->_mEol );
			}
		}
		else
		{
			Core::Log()->Write( sprintf( __( 'SMTP Library: Sending "%s".', 'WEb' ), $log_message ), 'debug' );

			fwrite( $this->_mConnection, $command . $this->_mEol );
		}

		/**
		 * @var int
		 */
		$code = $this->_GetResponse();

		if ( in_array( $code, $this->_mErrorCodes ) )
		{
			Core::Log()->Write( __( 'SMTP Library: Got error from server. Aborting transaction.', 'WEb' ), 'error' );

			/**
			 * Close server connection.
			 */
			$this->_Disconnect();

			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Get server response.
	 */
	private function _GetResponse()
	{
		if ( empty( $this->_mConnection ) )
		{
			Core::Log()->Write( __( 'SMTP Library: Error getting server response. No active connection.', 'WEb' ), 'error' );

			return FALSE;
		}

		/**
		 * @var int
		 */
		$code = 0;

		while ( FALSE !== ( $buffer = fgets( $this->_mConnection, 4096 ) ) )
		{
			Core::Log()->Write( rtrim( $buffer, $this->_mEol ), 'debug' );

			/**
			 * Check if we are at the last (or only) line.
			 */
			if( substr( $buffer, 3, 1 ) == " ")
			{
				/**
				 * @var int
				 */
				$code = substr( $buffer, 0, 3 );

				break;
			}
		}

		return $code;
	}

	/**
	 * Format message data for SMTP.
	 *
	 * @param string $msgSubject
	 * @param string $msgBody
	 * @param array $msgHeaders
	 *
	 * @return string
	 */
	private function _GetMessageData( $msgSubject, $msgBody, $msgHeaders )
	{
		/**
		 * Check if default data has been set.
		 */
		if ( empty( $this->_mEol ) )
		{
			$this->SetDefaults();
		}

		/**
		 * Remove the Bcc value from header.
		 *
		 * This should not appear in the message.
		 */
		unset( $msgHeaders['Bcc'] );

		/**
		 * @var array
		 */
		$msg_data = array();

		/**
		 * @var array
		 */
		$msg_data[] = "To: {$msgHeaders['To']}";

		/**
		 * @var array
		 */
		$msg_data[] = "From: {$msgHeaders['From']}";

		if ( !empty( $msgHeaders['Cc'] ) )
		{
			/**
			 * @var array
			 */
			$msg_data[] = "Cc: {$msgHeaders['Cc']}";
		}

		/**
		 * @var array
		 */
		$msg_data[] = "Subject: {$msgSubject}";

		foreach ( $msgHeaders as $header_name => $header_value )
		{
			/**
			 * @var array
			 */
			$msg_data[] = "{$header_name}: {$header_value}";
		}

		/**
		 * @var array
		 */
		$msg_data[] = '';

		/**
		 * @var string
		 */
		$msg_body = str_replace( "\r\n", "\n", $msgBody );

		/**
		 * @var string
		 */
		$msg_body = str_replace( "\r", "\n", $msg_body );

		/**
		 * @var array
		 */
		$msg_body_lines = explode( "\n", $msg_body );

		foreach ( $msg_body_lines as $line )
		{
			/**
			 * @var array
			 */
			$msg_data[] = $line;
		}

		/**
		 * @var array
		 */
		$msg_data[] = ".";

		return $msg_data;
	}
}
?>