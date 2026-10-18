<?php
/**
 * $Id$
 *
 * Engine base library.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2012, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    WEb
 * @subpackage WebLibrary
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
 * Library
 *
 * Base library.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @package    WEb
 * @subpackage WebLibrary
 *
 */
abstract class Library {
    /**
     * Stores object instances.
     *
     * @var array
     */
    protected static $_msInstance = array();

    /**
     * Get object instance.
     *
     * @param string $className name of the class to instantiate
     *
     * @return object
     */
    protected static function _GetInstance($className) {
        /**
         * Check if requested instance is the base library.
         */
        if (__CLASS__ == $className) {
            /**
             * Stop.
             */
            return FALSE;
        }

        /**
         * Check that instance is not already set.
         */
        if (!isset(self::$_msInstance[$className])) {
            /**
             * Set object instance.
             */
            self::$_msInstance[$className] = new $className();

            if (function_exists('__')) {
                /**
                 * Check if loaded classis an object, not a library.
                 */
                if (property_exists(self::$_msInstance[$className], '_mId')) {
                    /**
                     * @var string
                     */
                    $inst_msg = sprintf(__('%s object: Loading.', 'WEb'), $className);
                } else {
                    /**
                     * @var string
                     */
                    $inst_msg = sprintf(__('%s library: Loading.', 'WEb'), $className);
                }

                Core::Log()->Write($inst_msg, 'debug');
            }

            /**
             * Load object configuration.
             */
            self::$_msInstance[$className]->_LoadConfig($className);
        }

        /**
         * Return object instance.
         */
        return self::$_msInstance[$className];
    }

    /**
     * Load configuration file for current library.
     *
     * @param string $className name of the class to load configuration for
     *
     * @return bool
     */
    protected function _LoadConfig($className) {
        /**
         * @var string
         */
        $config_name = strtolower(str_replace('_', '.', $className)) . '.cfg';

        /**
         * @var string
         */
        $path_app = Core::Config()->Get('path.app');

        /**
         * @var string
         */
        $config_file = $path_app . 'etc/lib/' . $config_name . '.xml';

        if (!is_readable($config_file)) {
            if (function_exists('__')) {
                /**
                 * @var string
                 */
                $config_msg = sprintf(__('%s library: Skipping configuration file "%s" (not found).', 'WEb'), $className, $config_name);

                Core::Log()->Write($config_msg, 'debug');
            }

            return FALSE;
        }

        if (function_exists('__')) {
            /**
             * @var string
             */
            $config_msg = sprintf(__('%s library: Loading configuration file "%s".', 'WEb'), $className, $config_name);

            Core::Log()->Write($config_msg, 'debug');
        }

        return Core::Config()->Set($config_file);
    }

    /**
     * Avoid normal class loading.
     */
    final private function __construct() {

    }

    /**
     * Avoid class cloning.
     */
    final private function __clone() {

    }

}
?>