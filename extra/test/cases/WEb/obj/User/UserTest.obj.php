<?php
/**
 * $Id$
 *
 * Unit Tests.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2012, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    WebTest
 * @subpackage ObjectUser
 * @version    $Revision$
 * @since      Version 5
 * @access     private
 * @filesource
 */

/**
 * UserTest
 *
 * @author     Radu Maier <radu@webserv.co>
 * @package    WebTest
 * @subpackage ObjectUser
 *
 */
class UserTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Stores class object.
	 *
	 * @var mixed
	 */
	private $_mObject;

	/**
	 * Construct
	 */
	public function setUp()
	{
		$this->_mObject = Core::Get('Object_User');
	}

	/**
	 * @test
	 */
	public function GetReturnsObject()
	{
		$this->assertInternalType('object', Core::Get('Object_User'));
	}
}
?>