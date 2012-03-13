<?php
/**
 * $Id$
 *
 * SQLite3 database library.
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
 * Database_Sqlite3
 *
 * SQLite3 database library.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @package    WEb
 * @subpackage WebDatabase
 *
 */
class Database_Sqlite3 extends Database implements Interface_Database {
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

		$db_path = $this->_mDbDatabase . '.db';

		if (!is_readable($db_path)) {
			/**
			 * @var string
			 */
			$message = __('Database library: Database file is not readable.', 'WEb');

			trigger_error($message);

			return FALSE;
		}

		/**
		 * Set database link identifier.
		 *
		 * @var resource
		 */
		try {
			$db_link = new SQLite3($db_path);
		} catch ( Exception $e ) {
			/**
			 * @var string
			 */
			$message = sprintf(__('Database library: Error opening database %s.', 'WEb'), $this->_mDbDatabase) . ' ' . $e->getMessage();

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

		return TRUE;
	}

	/**
	 * (non-PHPdoc)
	 * @see Interface_Database::Disconnect()
	 */
	public function Disconnect() {
		return $this->_mDbLink->close();
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

		return $this->_mDbLink->escapeString($string);
	}

}
?>