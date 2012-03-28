<?php
/**
 * $Id$
 *
 * Controller library.
 *
 * All controllers extend this library.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2012, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    WEb
 * @subpackage WebController
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
 * Controller
 *
 * Controller library.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @package    WEb
 * @subpackage WebController
 *
 */
class Controller extends Library {
	/**
	 * Stores the module object.
	 *
	 * @var mixed
	 */
	protected $_mModuleObject;

	/**
	 * Stores the Output object.
	 *
	 * @var mixed
	 */
	protected $_mOutputObject;

	/**
	 * Stores the request array.
	 *
	 * @var array
	 */
	protected $_mRequest;

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
	 * Set the request array.
	 *
	 * @param array $request
	 *
	 * @return bool
	 */
	final public function SetRequest($request) {
		$this->_mRequest = $request;

		return TRUE;
	}

	/**
	 * Method to access the module object.
	 *
	 * @return object
	 */
	final public function Module() {
		if (NULL == $this->_mModuleObject) {
			/**
			 * @var string
			 */
			$ctrl_name = get_class($this);

			/**
			 * @var array
			 */
			$ctrl_parts = explode('_', $ctrl_name);

			/**
			 * @var string
			 */
			$module_name = 'Module_' . $ctrl_parts[1];

			/**
			 * PHP < 5.3 compatibility.
			 * $this->_mModuleObject = $module_name::GetInstance($module_name);
			 *
			 * @var object
			 */
			$this->_mModuleObject = call_user_func(array($module_name, 'GetInstance'), $module_name);

			$module_msg = sprintf(__('Controller library: Module "%s" loading.', 'WEb'), $ctrl_parts[1]);

			Core::Log()->Write($module_msg, 'debug');
		}

		return $this->_mModuleObject;
	}

	/**
	 * Method to access the Output object.
	 *
	 * @return object
	 */
	final public function Output() {
		if (NULL == $this->_mOutputObject) {
			/**
			 * @var string
			 */
			$engine = Core::Config()->Get('output.engine');

			/**
			 * @var string
			 */
			$engine = (FALSE !== $engine) ? $engine : 'WEb';

			/**
			 * @var string
			 */
			$engine_class = 'Output_' . ucfirst(strtolower($engine));

			/**
			 * PHP < 5.3 compatibility.
			 * $this->_mOutputObject = $engine_class::GetInstance($engine_class);
			 *
			 * @var object
			 */
			$this->_mOutputObject = call_user_func(array($engine_class, 'GetInstance'), $engine_class);

			$out_msg = sprintf(__('Controller library: Output library "%s" loading.', 'WEb'), $engine);

			Core::Log()->Write($out_msg, 'debug');
		}

		return $this->_mOutputObject;
	}

}
