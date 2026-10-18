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
 * @subpackage Output
 * @version    $Revision$
 * @since      Version 5
 * @access     private
 * @filesource
 */

/**
 * Output_SmartyTest
 *
 * @author     Radu Maier <radu@webserv.co>
 * @package    WebTest
 * @subpackage Output
 *
 */
class Output_SmartyTest extends PHPUnit_Framework_TestCase
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
        $this->_mObject = Output_Smarty::GetInstance('Output_Smarty');
    }

    /**
     * @test
     */
    public function GetInstanceReturnsObject()
    {
        $this->assertInternalType('object', Output_Smarty::GetInstance('Output_Smarty'));
    }
}
?>