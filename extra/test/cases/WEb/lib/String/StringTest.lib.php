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
 * @subpackage String
 * @version    $Revision$
 * @since      Version 5
 * @access     private
 * @filesource
 */

/**
 * StringTest
 *
 * @author     Radu Maier <radu@webserv.co>
 * @package    WebTest
 * @subpackage String
 *
 */
class StringTest extends PHPUnit_Framework_TestCase
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
		$this->_mObject = String::GetInstance();
	}

	/**
	 * @test
	 */
	public function GetInstanceReturnsObject()
	{
		$this->assertInternalType( 'object', String::GetInstance() );
	}

	/**
	 * @test
	 */
	public function EndsWithRetrunsTrueOnSuccess()
	{
		$this->AssertTrue( $this->_mObject->EndsWith( 'Dolor', 'LoremIpsumDolor') );
	}

	/**
	 * @test
	 */
	public function EndsWithRetrunsFalseOnFailuer()
	{
		$this->AssertFalse( $this->_mObject->EndsWith( 'Dolor', 'LoremIpsum') );
	}
}
?>