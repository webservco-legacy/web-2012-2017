<?php
/**
 * $Id$
 *
 * MySQL database library.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2012, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    WEb
 * @subpackage WebDatabase
 * @version    $Revision$
 * @since      Version 1
 * @access     public
 * @filesource
 */

/**
 * Security check.
 */
if (!defined('_WEB')) {
    /**
     * Stop script execution.
     */
    exit();
}

/**
 * Database_Mysql
 *
 * MySQL database library.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @package    WEb
 * @subpackage WebDatabase
 *
 */
class Database_Mysql extends Database implements Interface_Database {
    /**
     * Setup database connection.
     *
     * @return bool
     */
    protected function _GetLink() {
        /**
         * Set configuration data.
         */
        $this->_SetData();

        /**
         * Set MySQL default port if we have no custom port in configuration settings.
         *
         * @var string
         */
        $db_port = ('' != $this->_mDbPort) ? $this->_mDbPort : '3306';

        /**
         * Set database link identifier.
         *
         * @var resource
         */
        $db_link = mysql_connect($this->_mDbHost . ':' . $db_port, $this->_mDbUser, $this->_mDbPassword);

        if (!$db_link) {
            /**
             * @var string
             */
            $message = __('Database library: Error connecting to database management server.', 'WEb') . ' ' . mysql_error();

            trigger_error($message);

            return FALSE;
        }

        if (TRUE !== mysql_select_db($this->_mDbDatabase, $db_link)) {
            /**
             * @var string
             */
            $message = sprintf(__('Database library: Error opening database %s.', 'WEb'), $this->_mDbDatabase) . ' ' . mysql_error();

            trigger_error($message);

            return FALSE;
        }

        /**
         * Set database link identifier.
         *
         * @var resource
         */
        $this->_mDbLink = $db_link;

        /**
         * @var string
         */
        $db_message = sprintf(__('Database library: Database connection opened (driver: %s).', 'WEb'), Core::Config()->Get('database.type'));

        Core::Log()->Write($db_message, 'debug');

        /**
         * UTF Compatibility.
         */
        mysql_set_charset('utf8', $this->_mDbLink);

        return TRUE;
    }

    /**
     * (non-PHPdoc)
     * @see Interface_Database::Disconnect()
     */
    public function Disconnect() {
        return mysql_close($this->_mDbLink);
    }

    /**
     * (non-PHPdoc)
     * @see Interface_Database::EscapeString()
     */
    public function EscapeString($string) {
        /**
         * Open database connection if not already opened.
         */
        $this->Connect();

        return mysql_real_escape_string($string);
    }

}
?>