<?php
/**
 * $Id$
 *
 * Application controller library.
 *
 * @author     Radu Maier <radu@italiamultimedia.com>
 * @copyright  Copyright (c) 2011, Italia Multimedia Srl
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    DefaultApp
 * @subpackage ControllerApp
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
 * ControllerApp
 *
 * Application controller library.
 *
 * @author     Radu Maier <radu@italiamultimedia.com>
 * @package    DefaultApp
 * @subpackage ControllerApp
 *
 */
class ControllerApp extends Controller {

	/**
	 * Controller initialization.
	 *
	 * @return bool
	 */
	public function InitController() {

		/**
		 * Initialize output.
		 *
		 * This is actually not required,
		 * but we need it here so we can use the
		 * Output library configuration data.
		 */
		$this->Output()->Init();

		/**
		 * Use only in multi-language apps.
		 *
		 * $this->InitLocale();
		*/

		return TRUE;
	}

	public function InitLocale() {

		if (!empty($_SESSION['I18n']['Chosen'])) {

			return FALSE;
		}

		$app_langs = Core::I18n()->GetAvailableLangs();

		$user_lang = !empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : NULL;

		if (!empty($user_lang) && array_key_exists($user_lang, $app_langs)) {
			Core::I18n()->SetLocale($app_langs[$user_lang]);

			return TRUE;
		}

		return FALSE;
	}

	public function ChangeLocale($newLang, $redirect = NULL) {

		$app_langs = Core::I18n()->GetAvailableLangs();

		if (!empty($newLang) && array_key_exists($newLang, $app_langs)) {

			Core::I18n()->SetLocale($app_langs[$newLang]);

			if (!empty($_SESSION['I18n'])) {

				$_SESSION['I18n']['Chosen'] = TRUE;
			}

			if (!empty($redirect)) {

				Core::Router()->Redirect($redirect);

				exit();
			}

			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Get output.
	 *
	 * @param array $contentData
	 * @param string $customTemplate
	 * @param boolean $globalTemplate true if output is to search the custom
	 * template in root tpl dir instead of module tpl dir
	 * @param string $mainTemplate override default main template (layout.tpl)
	 *
	 * @return string
	 */
	public function GetOutput($contentData = array(), $customTemplate = NULL, $globalTemplate = FALSE, $mainTemplate = NULL) {

		/**
		 * Local data.
		 *
		 * @var array
		*/
		$local_data = array();
		
		/**
		 * Set the header tpl var to the parsed content template.
		 *
		 * @var array
		 */
		$local_data['tpl_header'] = $this->Output()->Out('header', $contentData);
		
		/**
		 * Set the scripts tpl var to the parsed content template.
		 *
		 * @var array
		 */
		$local_data['tpl_footer'] = $this->Output()->Out('footer', $contentData);

		/**
		 * Set the scripts tpl var to the parsed content template.
		 *
		 * @var array
		*/
		$local_data['tpl_scripts'] = $this->Output()->Out('scripts', $contentData);
		
		/**
		 * Get secondary template name.
		 *
		 * @var string
		 */
		$template_name = (FALSE !== $globalTemplate) ? $customTemplate : $this->Output()->GetSubTemplate($this->_mRequest, $customTemplate);
		
		/**
		 * Set the content tpl var to the parsed content template.
		 *
		 * @var array
		 */
		$local_data['tpl_content'] = $this->Output()->Out($template_name, $contentData);

		/**
		 * Get all data.
		 *
		 * @var array
		*/
		$data = array_merge($local_data, $contentData);

		/**
		 * Set charset
		*/
		header("Content-type: text/html; charset=utf-8");

		/**
		 * Get main template name.
		 */
		$main_template = !empty($mainTemplate) ? $mainTemplate : $this->Output()->GetTemplate();

		if (Core::Useragent()->IsDevice()) {

			$main_template .= '_mobile';

			$is_phone = Core::Useragent()->IsDevicePhone();

			if ($is_phone) {

				$main_template .= '_phone';
			}
		}

		return $this->Output()->Out($main_template, $data);
	}
}
?>
