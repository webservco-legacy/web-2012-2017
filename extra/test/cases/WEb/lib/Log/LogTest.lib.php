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
 * @subpackage Log
 * @version    $Revision$
 * @since      Version 5
 * @access     private
 * @filesource
 */

/**
 * LogTest
 *
 * @author     Radu Maier <radu@webserv.co>
 * @package    WebTest
 * @subpackage Log
 *
 */
class LogTest extends PHPUnit_Framework_TestCase
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
        $this->_mObject = Log::GetInstance();
    }

    /**
     * @test
     */
    public function GetInstanceReturnsObject()
    {
        $this->assertInternalType('object', Log::GetInstance());
    }

    /**
     * @test
     */
    public function SetTypeReturnsTrue()
    {
        $this->assertTrue($this->_mObject->SetType('test'));
    }

    /**
     * @test
     */
    public function SetPathReturnsTrue()
    {
        $this->assertTrue($this->_mObject->SetPath(_PU_ASSETS_PATH . 'WEb/var/log/'));
    }

    /**
     * @test
     */
    public function SetLevelReturnsFalseOnValid()
    {
        $this->assertFalse($this->_mObject->SetLevel('noexist'));
    }

    /**
     * @test
     */
    public function SetLevelReturnsOneOnValidLevlNameInfo()
    {
        $this->assertEquals(1, $this->_mObject->SetLevel('info'));
    }

    /**
     * @test
     */
    public function SetLevelReturnsZeroOnValidLevlNameDebug()
    {
        $this->assertEquals(0, $this->_mObject->SetLevel('debug'));
    }

    /**
     * @test
     */
    public function GetFileReturnsFalseIfFileExistsButIsNotWritable()
    {
        $this->assertFalse($this->_mObject->GetFile('readonly'));
    }

    /**
     * @test
     */
    public function GetFileReturnsStringOnValidTypeAndValidPath()
    {
        $this->assertInternalType('string', $this->_mObject->GetFile('test', _PU_ASSETS_PATH . 'WEb/var/log/'));
    }

    /**
     * @test
     */
    public function GetFileReturnsStringOnValidTypeAndAlreadySetPath()
    {
        $this->assertInternalType('string', $this->_mObject->GetFile('test'));
    }

    /**
     * @test
     */
    public function ResetFailsOnReadonlyFile()
    {
        $this->assertFalse($this->_mObject->Reset('readonly'));
    }

    /**
     * @test
     */
    public function ResetReturnsTrueOnSuccess()
    {
        $this->assertTrue($this->_mObject->Reset('test'));
    }

    /**
     * @test
     */
    public function WriteReturnsFalseOnInvalidLevel()
    {
        $this->assertFalse($this->_mObject->Write('unit testing', 'dummy', 'test'));
    }

    /**
     * @test
     */
    public function WriteReturnsFalseOnReadonlyFile()
    {
        $this->assertFalse($this->_mObject->Write('unit testing', 'notice', 'readonly'));
    }

    /**
     * @test
     */
    public function WriteReturnsTrueOnValidType()
    {
        $this->assertTrue($this->_mObject->Write('unit testing', NULL, 'test'));
    }

    /**
     * @test
     */
    public function WriteReturnsFalseIfWriteLevelIsLessThanLogLevel()
    {
        $this->_mObject->SetLevel('error');

        $this->assertFalse($this->_mObject->Write('unit testing', 'info', 'test'));
    }

}
?>