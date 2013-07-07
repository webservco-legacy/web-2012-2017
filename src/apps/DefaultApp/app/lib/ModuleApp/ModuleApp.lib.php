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
	 * Get default messages.
	 *
	 * Must stay here and not in Module.lib in order to be picked up by Poedit.
	 *
	 * @return array
	 */
	public function GetDefaultStrings() {

		$data = array();

		$data['title'] = $data['title_app'] = __(Core :: Config()->Get('app.title'), 'App');

		$data['no_data'] = __('No data to display', 'App');

		return $data;
	}

	/**
	 * Get custom data.
	 *
	 * Add here any custom initializations.
	 *
	 * If necessary, you can also override any default data.
	 *
	 * Important: Data marked with "[OPTIONAL]" is only available if the corresponding setting is set to "1" in app configuration.
	 *
	 * Default data:
	 * (already available here, can be overwritten)
	 *
	 * **************************************************
	 * $data['query']
	 *
	 * Request query.
	 *
	 * Values:
	 * array of key/data
	 *
	 * Usage:
	 * Controller: $data['query'][STUFF]
	 * Template: $query[STUFF]
	 * **************************************************
	 * $data['info']
	 *
	 * General information
	 *
	 * Values:
	 * $data['info']['module'] - current module name
	 * $data['info']['location'] - current location (Module/App/Controller)
	 * $data['info]['path'] - current location path (Item/1/page/3)
	 * $data['info]['path_pagination'] - current location path without page number (Item/1/)
	 * $data['info']['url_next'] - next url
	 *
	 * Usage:
	 * Controller: $data['info']['module']
	 * Template: $info['module']
	 * **************************************************
	 * $data['url']
	 *
	 * Default URLs
	 *
	 * Values:
	 * $data['url']['www'] (http://www.example.com)
	 * $data['url']['skin'] (http://www.example.com/myskin/)
	 * $data['url']['scripts'] (http://www.example.com/js/)
	 * $data['url']['media'] (http://www.example.com/media/)
	 *
	 * Usage:
	 * Controller: $data['url']['www']
	 * Template: $url['www']
	 * **************************************************
	 * $data['path']
	 *
	 * Default paths.
	 *
	 * Values:
	 * $data['path']['app'] (/full/server/path/to/app/dir/)
	 * $data['path']['www'] (/full/server/path/to/www/dir/)
	 *
	 * Usage:
	 * Controller: $data['path']['www']
	 * Template: $path['www']
	 * **************************************************
	 * $data['i18n']
	 *
	 * Internationalization.
	 *
	 * Values:
	 * $data['i18n']['lang'] - all language (en, it, etc)
	 * $data['i18n']['langs'] - array of all available languages - language / locale
	 *
	 * Usage:
	 * Controller: $data['i18n']['lang']
	 * Template: $i18n['lang']
	 * **************************************************
	 * $data['strings']
	 *
	 * Default messages used in most pages.
	 *
	 * Values:
	 * $data['strings']['title']
	 * $data['strings']['app_title']
	 * $data['strings']['no_data']
	 *
	 * Usage:
	 * Controller: $data['strings']['title']
	 * Template: $strings['title']
	 * **************************************************
	 * $data['menu'] [OPTIONAL]
	 *
	 * Custom menu functionality.
	 *
	 * Values:
	 * $data['menu'][MENU_NODE_FROM _CONFIG]['title']
	 * $data['menu'][MENU_NODE_FROM_CONFIG]['href']
	 * $data['menu'][MENU_NODE_FROM_CONFIG]['target']
	 * $data['menu'][MENU_NODE_FROM_CONFIG]['submenu'] (another array withmenu data)
	 * $data['menu'][MENU_NODE_FROM_CONFIG]['resource'] $data['menu'][MENU_NODE_FROM_CONFIG]['submenu'] (another array withmenu data)
	 *
	 * Usage:
	 * Controller: $data['menu']['MENU_NODE_FROM_CONFIG']
	 * Template: $menu['MENU_NODE_FROM_CONFIG']
	 * **************************************************
	 * $data['timezone'] [OPTIONAL]
	 *
	 * Custom timezone functionality.
	 *
	 * A custom timezone can be set by adding:
	 * /timezone/ENCODEDTIMEZONEVALUE
	 * to the URL
	 *
	 * ENCODEDTIMEZONEVALUE is the timezone value encoded with Core::String()->EncodeUrlString
	 * Example: "RXVyb3BlL1JvbWU." (Europe/Rome)
	 *
	 * If session functionality is enabled, the custom timezone value is also stored in the variable:
	 * $_SESSION['Date']['TimeZone']
	 *
	 * Value:
	 * string representation of custom timezone (Europe/Rome)
	 *
	 * Usage:
	 * Controller: $data['timezone']
	 * Template: $timezone
	 * **************************************************
	 * $data['webapp'] [OPTIONAL]
	 *
	 * Web app info.
	 *
	 * Values:
	 * $data['webapp']['os_type'] - os type (android, ios, other)
	 * $data['webapp']['device_type'] - mobile device type (tablet, phone, other)
	 * $data['webapp']['version'] - custom app version set by_mobile devices
	 * $data['webapp']['market'] - app market id (0 - none, 1 - google, 2 - samsung, 3 - amazon, 4 - itunes)
	 * $data['webapp']['type'] - app type (free, pro, etc)
	 *
	 * Usage:
	 * Controller: $data['webapp']['os_type']
	 * Template: $webapp['os_type']
	 * **************************************************
	 * $data['user'] [OPTIONAL]
	 *
	 * User functionality.
	 *
	 * Values (not logged in):
	 * $data['user']['StatsCurrent']['time']
	 * $data['user']['StatsCurrent']['ip']
	 * $data['user']['StatsCurrent']['agent']
	 * $data['user']['StatsCurrent']['auto']
	 * $data['user']['InitialVisit']
	 *
	 * Values (logged in):
	 * $data['user']['Id']
	 * $data['user']['Logged']
	 * $data['user']['Persistent']
	 * $data['user']['Prop']['user_id']
	 * $data['user']['Prop']['user_email']
	 * $data['user']['Prop']['user_nickname']
	 * $data['user']['Prop']['user_status']
	 * + custom user properties
	 * $data['user']['StatsCurrent']['time']
	 * $data['user']['StatsCurrent']['ip']
	 * $data['user']['StatsCurrent']['agent']
	 * $data['user']['StatsCurrent']['auto']
	 * $data['user']['StatsPrevious']['time']
	 * $data['user']['StatsPrevious']['ip']
	 * $data['user']['StatsPrevious']['agent']
	 * $data['user']['StatsPrevious']['auto']
	 * $data['user']['InitialVisit']
	 *
	 * Usage:
	 * Controller: $data['user']['Id']
	 * Template: $user['Id']
	 * **************************************************
	 *
	 * @param array $request
	 * @return array
	 */
	public function GetDataCustom($request, $data) {

		/**
		 * Custom app data.
		 */

		/**
		 * Example: performm additional initializations for logged in users.
		 */
		if (!empty($data['user']['Logged'])) {
			/**
			 * Do stuff.
			 */
		}

		/**
		 * Example (use as $data['test'] (Controller) or $test (Output template).
		 */
		$data['test'] = 'test data';

		$data['url']['next_string'] = !empty($data['query']['next']) ? "/next/{$data['query']['next']}" : NULL;

		$data['url']['items_admin'] = !empty($data['info']['url_next']) ? "{$data['url']['www']}{$data['info']['url_next']}" : "{$data['url']['www']}Items/Admin/Items";

		return $data;
	}
}
?>