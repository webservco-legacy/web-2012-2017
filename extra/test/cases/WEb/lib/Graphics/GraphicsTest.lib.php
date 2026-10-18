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
 * @subpackage Graphics
 * @version    $Revision$
 * @since      Version 5
 * @access     private
 * @filesource
 */

/**
 * GraphicsTest
 * 
 * @author Radu Maier <radu@webserv.co>
 * @package WebTest
 * @subpackage Graphics
 */
class GraphicsTest extends PHPUnit_Framework_TestCase {

    /**
     * Stores class object.
     * 
     * @var mixed
     */
    private $_mObject;

    /**
     * Construct
     */
    public function setUp() {

        $this->_mObject = Graphics::GetInstance();
    }

    /**
     * @test
     */
    public function GetInstanceReturnsObject() {

        $this->assertInternalType('object', Graphics::GetInstance());
    }
}
?>