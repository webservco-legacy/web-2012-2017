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
 * @subpackage I18n
 * @version    $Revision$
 * @since      Version 5
 * @access     private
 * @filesource
 */

/**
 * I18nTest
 *
 * @author     Radu Maier <radu@webserv.co>
 * @package    WebTest
 * @subpackage I18n
 *
 */
class I18nTest extends PHPUnit_Framework_TestCase
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
        $this->_mObject = I18n::GetInstance();
    }

    /**
     * @test
     */
    public function GetInstanceReturnsObject()
    {
        $this->assertInternalType('object', I18n::GetInstance());
    }

    /**
     * @test
     */
    public function InitReturnsTrueOnSuccessWithoutDefaultDomain()
    {
        $this->assertTrue($this->_mObject->Init('ro_RO') );
    }

    /**
     * @test
     */
    public function InitReturnsTrueOnSuccessWithoutLocale()
    {
        $this->assertTrue($this->_mObject->Init(NULL, 'WEb') );
    }

    /**
     * @test
     */
    public function InitReturnsTrueOnSuccessWithoutParams()
    {
        $this->assertTrue($this->_mObject->Init() );
    }

    /**
     * @test
     */
    public function InitReturnsTrueOnSuccess()
    {
        $this->assertTrue($this->_mObject->Init('ro_RO', 'WEb') );
    }

    /**
     * @test
     */
    public function AddUsesCoreOnOnInvalidDir()
    {
        $this->assertTrue($this->_mObject->Add('NoExist_WEb'));
    }

    /**
     * @test
     */
    public function AddReturnsTrueOnSuccessWithTypeSpecified()
    {
        $this->assertTrue($this->_mObject->Add('Core_WEb'));
    }

    /**
     * @test
     */
    public function AddReturnsTrueOnSuccessWithoutTypeSpecified()
    {
        $this->assertTrue($this->_mObject->Add('WEb'));
    }

    /**
     * @test
     */
    public function AddReturnsTrueOnSuccessWithoutArguments()
    {
        $this->assertTrue($this->_mObject->Add());
    }

    /**
     * @test
     */
    public function LoadReturnsTrueOnSuccess()
    {
        $this->assertTrue($this->_mObject->Load('WEb'));
    }

    /**
     * @test
     */
    public function LoadReturnsTrueOnSuccessWithoutArgument()
    {
        $this->assertTrue($this->_mObject->Load());
    }

    /**
     * @test
     */
    public function I18nSimpleTest()
    {
        $this->assertEquals('Salut Lume!', _('Hello World!'));
    }

    /**
     * @test
     */
    public function GettextWrapperTestDefaultDomain()
    {
        $this->assertEquals('Salut Lume!', __('Hello World!'));
    }

    /**
     * @test
     */
    public function GettextWrapperTestSpecifiedDomain()
    {
        $this->assertEquals('Salut Lume!', __('Hello World!', 'WEb'));
    }
}
?>