<?php
/**
 * $Id$
 *
 * Core controlller.
 *
 * Module: Oups
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2012, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    SandboxApp
 * @subpackage OupsModule
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
 * Controller_Oups_Core
 *
 * Core Controller for Oups module.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @package    SandboxApp
 * @subpackage OupsModule
 *
 */
class Controller_Oups_Core extends Controller
{
	public function Oups404()
	{
		header('HTTP/1.1 404 Not Found');

		echo '<!doctype html><html><head><meta http-equiv="content-type" content="text/html; charset=UTF-8"><title>' .
		__( 'Error', 'WEb' ) . '</title></head><body><h1>' .
		'404 Not Found' . '</h1><p>' .
		__( 'Please come back later.', 'WEb' ) . '</p><hr><address>' .
		sprintf( __( 'The friendly server at %s.', 'WEb' ), $_SERVER['SERVER_NAME'] ) . '</address></body></html>';
		
		return TRUE;
	}
}
?>