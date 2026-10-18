<?php
/**
 * $Id$
 *
 * Unit Tests.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2014, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    WebTest
 * @subpackage Error
 * @version    $Revision$
 * @since      Version 5
 * @access     private
 * @filesource
 */

/**
 * ErrorTest
 *
 * @author     Radu Maier <radu@webserv.co>
 * @package    WebTest
 * @subpackage Error
 *
 */
class ErrorTest extends PHPUnit_Framework_TestCase
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
        $this->_mObject = Error::GetInstance();
    }

    /**
     * @test
     */
    public function GetInstanceReturnsObject()
    {
        $this->assertInternalType('object', Error::GetInstance());
    }

    /**
     * @test
     */
    public function LoadReturnsTrue()
    {
        $this->assertTrue($this->_mObject->Load());
    }

    /**
     * @test
     * @expectedException ErrorException
     */
    public function HandleThrowsException()
    {
        $this->_mObject->Handle(256, 'Hello World', 'foo/bar', 10);
    }

    /**
     * @test
     */
    public function UnloadReturnsTrue()
    {
        $this->assertTrue($this->_mObject->Unload());
    }
}
?>