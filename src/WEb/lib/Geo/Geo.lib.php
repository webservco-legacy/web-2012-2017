<?php
/**
 * $Id$
 *
 * Geolocation library.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2012, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    WEb
 * @subpackage WebGeo
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
 * Geo
 * 
 * Geolocation library.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @package    WEb
 * @subpackage WebGeo
 *
 */
class Geo extends Library
{
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
	 * Get IP address.
	 *
	 * Uses the $_SERVER superglobal
	 *
	 * @return string
	 */
	public function GetIp()
	{
		/**
		 * @var array
		 */
		$check = array(
			'CLIENT_IP',
			'HTTP_CLIENT_IP',

			'HTTP_X_FORWARDED',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_X_FORWARDED_FOR_IP',

			'HTTP_FORWARDED',
			'HTTP_FORWARDED_FOR',
			'HTTP_FORWARDED_FOR_IP',

			'FORWARDED',
			'FORWARDED_FOR',
			'FORWARDED_FOR_IP',

			'X_FORWARDED',
			'X_FORWARDED_FOR',

			'HTTP_COMING_FROM',
			'HTTP_X_COMING_FROM',

			'VIA',
			'HTTP_VIA',

            'HTTP_PC_REMOTE_ADDR',

            'HTTP_X_IMFORWARDS',

			'HTTP_PROXY',
            'HTTP_PROXY_CONNECTION',

			'HTTP_XPROXY',
			'HTTP_XROXY_CONNECTION',

			'HTTP_PRAGMA',
			'HTTP_XONNECTION',
			'HTTP_CACHE_INFO',

			'ZHTTP_CACHE_CONTROL',
		);

		foreach ( $check as $var )
		{
			if ( !empty( $_SERVER[$var] ) )
			{
				/**
				 * @var string
				 */
				$ip = Core::String()->GetFirstItem( $_SERVER[$var], ',' );

				if ( $this->IsValidIp( $ip ) )
				{
					return $ip;
				}
			}
		}

		return $_SERVER['REMOTE_ADDR'];
	}

	/**
	 * Chech if IP address is valid.
	 *
	 * @param string $ip
	 * @param bool $checkWan
	 *
	 * @return bool
	 */
	public function IsValidIp( $ip = NULL, $checkWan = TRUE )
	{
		if ( empty( $ip ) )
		{
			return FALSE;
		}

		$ip_num = ip2long( $ip );

		if ( ( $ip_num !== -1 ) && ( FALSE !== $ip_num ) && ( long2ip( $ip_num ) === $ip ) )
		{
			if ( FALSE === $checkWan )
			{
				return TRUE;
			}

			if (
			( $ip_num < 167772160 || $ip_num > 184549375 ) && // Not in 10.0.0.0/8
			( $ip_num < -1408237568 || $ip_num > -1407188993 ) && // Not in 172.16.0.0/12
			( $ip_num < -1062731776 || $ip_num > -1062666241 ) // Not in 192.168.0.0/16
			)
			{
				return TRUE;
			}
		}

		return FALSE;
	}
}
?>