<?php
/**
 * $Id$
 *
 * Application controller library.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2014, Radu Maier
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
 * Application controller library.
 *
 * @author Radu Maier <radu@webserv.co>
 * @package DefaultApp
 * @subpackage ControllerApp
 */
class ControllerApp extends Controller {

    /**
     * Controller initialization.
     *
     * @return bool
     */
    public function Init() {

        $this->_mUseSessions = (bool) Core::Config()->Get('options.sessions');
        
        /**
         * Initialize output.
         * This is actually not required,
         * but we need it here so we can use the
         * Output library configuration data.
         */
        $this->Output()->Init($this->_mTemplatePrefix);
        
        /**
         * Use only in multi-language apps.
         */
        $this->InitLocale();
        
        return TRUE;
    }

    public function InitLocale() {

        $opt_multilanguage = Core::Config()->Get('options.multilanguage');
        
        if (!$opt_multilanguage) {
            
            return FALSE;
        }
        
        if ($this->_mUseSessions && !empty($_SESSION['I18n']['Chosen'])) {
            
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

    /**
     * Get output.
     *
     * @param array $contentData        	
     * @param string $customTemplate        	
     * @param boolean $globalTemplate
     *        	true if output is to search the custom
     *        	template in root tpl dir instead of module tpl dir
     * @param string $mainTemplate
     *        	override default main template (layout.tpl)
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
         * The method has a redundancy check, so it's no trouble if it's already called in $this->Init();
         */
        $this->Output()->Init($this->_mTemplatePrefix);
        
        /**
         *
         * @var _string
         */
        $template_dir_main = '_main/';
        
        $templates_extra = $this->Output()->GetTemplatesExtra();
        
        foreach ($templates_extra as $template_extra) {
            
            /**
             * Set the extra tpl var to the parsed content template.
             *
             * @var array
             */
            
            $local_data["tpl_{$template_extra}"] = $this->Output()->Out($template_dir_main . $template_extra, $contentData);
        }
        
        /**
         * Get secondary template name.
         *
         * @var string
         */
        $template_name = (FALSE !== $globalTemplate) ? $template_dir_main . $this->_mTemplatePrefix . $customTemplate : $this->Output()->GetSubTemplate($this->_mRequest, $customTemplate);
        
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
        $main_template = !empty($mainTemplate) ? $this->_mTemplatePrefix . $mainTemplate : $this->Output()->GetTemplate();
        
        if (Core::Useragent()->IsDevice()) {
            
            $main_template .= '_mobile';
            
            $is_phone = Core::Useragent()->IsDevicePhone();
            
            if ($is_phone) {
                
                $main_template .= '_phone';
            }
        }
        
        return $this->Output()->Out($template_dir_main . $main_template, $data);
    }

    public function CheckAdminAccess($data) {

        if (!empty($data['user']['Logged']) && !empty($data['info']['location'])) {
            
            if (Core::Acl()->Check($data['user']['Id'], $data['info']['location'])) {
                
                return TRUE;
            } else {
                
                $data['strings']['message'] = __('Error') . ': ' . __('You don\'t have permission to access this resource.');
                
                echo $this->GetOutput($data, 'error', TRUE);
                
                return FALSE;
            }
        } else {
            
            $next = Core::String()->EncodeUrlString($data['info']['location']);
            
            Core::Router()->Redirect("Users/App/Account/next/{$next}");
            
            exit();
        }
        
        return FALSE;
    }

    public function GetDefaultOutput($title = NULL) {

        $this->Init();
        
        $data = $this->Module()->GetData($this->_mRequest);
        
        if (!empty($title)) {
            
            $data['strings']['title'] = $title;
        }
        
        echo $this->GetOutput($data);
        
        return TRUE;
    }
}
?>