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
 * @subpackage Geo
 * @version    $Revision$
 * @since      Version 5
 * @access     private
 * @filesource
 */

/**
 * GeoTest
 *
 * @author     Radu Maier <radu@webserv.co>
 * @package    WebTest
 * @subpackage Geo
 *
 */
class GeoTest extends PHPUnit_Framework_TestCase
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
        $this->_mObject = Geo::GetInstance();
    }

    /**
     * @test
     */
    public function GetInstanceReturnsObject()
    {
        $this->assertInternalType('object', Geo::GetInstance());
    }
}
?>