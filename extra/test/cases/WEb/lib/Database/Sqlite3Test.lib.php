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
 * @subpackage Database
 * @version    $Revision$
 * @since      Version 5
 * @access     private
 * @filesource
 */

/**
 * Sqlite3Test
 *
 * @author     Radu Maier <radu@webserv.co>
 * @package    WebTest
 * @subpackage Database
 *
 */
class Database_Sqlite3Test extends PHPUnit_Framework_TestCase
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
        $this->_mObject = Database_Sqlite3::GetInstance('Database_Sqlite3');
    }

    /**
     * @test
     */
    public function GetInstanceReturnsObject()
    {
        $this->assertInternalType('object', Database_Sqlite3::GetInstance('Database_Sqlite3'));
    }
}
?>