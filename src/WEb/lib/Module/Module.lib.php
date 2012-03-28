<?php
/**
 * $Id$
 *
 * Module library.
 *
 * All modules extend this library.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2012, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    WEb
 * @subpackage WebModule
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
 * Module
 *
 * Module library.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @package    WEb
 * @subpackage WebModule
 *
 */
class Module extends Library {
	/**
	 * Implement GetInstance() method.
	 *
	 * This is required for PHP < 5.3 compatibility (no late static binding)
	 *
	 * @param string $className class name to instantiate
	 *
	 * @return object
	 */
	public static function GetInstance($className = __CLASS__) {
		/**
		 * Call parent method with current class name as parameter.
		 */
		return parent::_GetInstance($className);
	}

	/**
	 * Load configuration file for current library.
	 *
	 * Needs to override the Library method.
	 *
	 * @param string $className name of the class to load configuration for
	 *
	 * @return bool
	 */
	protected function _LoadConfig($className) {
		/**
		 * Add module i18n.
		 */
		Core::I18n()->Add($className);

		/**
		 * Load module i18n.
		 */
		Core::I18n()->Load($className);

		/**
		 * @var array
		 */
		$parts = explode('_', $className);

		/**
		 * @var string
		 */
		$module_name = array_key_exists(1, $parts) ? strtolower($parts[1]) : $parts[0];

		/**
		 * @var string
		 */
		$path_app = Core::Config()->Get('path.app');

		/**
		 * @var string
		 */
		$cfg_file = $path_app . 'etc/' . Core::Config()->Get('dir.app.mod') . '/' . ucfirst($module_name) . '/' . $module_name . '.cfg.xml';

		if (!is_readable($cfg_file)) {
			if (function_exists('__')) {
				/**
				 * @var string
				 */
				$config_msg = sprintf(__('%s module: Skipping configuration file "%s" (not found).', 'WEb'), $className, $module_name);

				Core::Log()->Write($config_msg, 'debug');
			}

			return FALSE;
		}

		if (function_exists('__')) {
			/**
			 * @var string
			 */
			$config_msg = sprintf(__('%s module: Loading configuration file "%s".', 'WEb'), $className, $module_name);

			Core::Log()->Write($config_msg, 'debug');
		}

		return Core::Config()->Set($cfg_file);
	}

}
?>