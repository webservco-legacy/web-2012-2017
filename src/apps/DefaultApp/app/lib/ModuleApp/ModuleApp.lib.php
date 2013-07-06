<?php
/**
 * $Id$
 *
 * Application module library.
 *
 * @author     Radu Maier <radu@italiamultimedia.com>
 * @copyright  Copyright (c) 2011, Italia Multimedia Srl
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    DefaultApp
 * @subpackage ModuleApp
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
	exit ();
}

/**
 * ModuleApp
 *
 * Application module library.
 *
 * @author     Radu Maier <radu@italiamultimedia.com>
 * @package    DefaultApp
 * @subpackage ModuleApp
 *
 */
class ModuleApp extends Module {

	/**
	 * Get default output data.
	 *
	 * @param array $request
	 *
	 * @return array
	 */
	public function GetDefaultData($request) {

		/**
		 * Get the default paths.
		 *
		 *  url_app
		 *  path_www
		 *  url_skin
		 *  url_scripts
		 *  url_media
		 */
		$default_data = $this->GetDefaultPaths();

		/**
		 * OS Type (android, ios, other).
		 *
		 * This does not tell us if it's mobile or PC
		*/
		$default_data['os_type'] = Core::Useragent()->GetOsType();

		/**
		 * Device Type (tablet, phone, other).
		*/
		$default_data['device_type'] = Core::Useragent()->GetDeviceType();

		/**
		 * App language.
		*/
		$default_data['lang'] = substr(Core :: I18n()->GetLocale(), 0, 2);

		/**
		 * List of available languages.
		*/
		$default_data['langs'] = Core::I18n()->GetAvailableLangs();

		/**
		 * Default page title.
		*/
		$default_data['title'] = $default_data['title_app'] = __(Core :: Config()->Get('app.title'), 'App');

		/**
		 * Current module name.
		*/
		$default_data['module'] = $this->GetCurrentModule($request);

		/**
		 * Current resource name (Module/Controller/Action)
		*/
		$default_data['resource'] = $this->GetCurrentResource($request, $default_data['module']);

		/**
		 * User functionality (disable if not used).
		*/
		Core::User()->Init();

		$default_data['user'] = $_SESSION['User'];

		/**
		 * Menu
		 */
		$default_data['menu_web'] = $this->GetMenu($default_data['url_app'], 'web');

		/**
		 * Default error messages used in most pages.
		*/
		$default_data['string_no_data'] = __('No data to display', 'App');

		return $default_data;
	}
}
?>