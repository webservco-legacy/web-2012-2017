<?php
/**
 * $Id$
 *
 * WEb output library.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2012, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    WEb
 * @subpackage WebOutput
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
 * Output_Web
 *
 * WEb Output library.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @package    WEb
 * @subpackage WebOutput
 *
 */
class Output_Web extends Output implements Interface_Output {
    /**
     * (non-PHPdoc)
     * @see Interface_Output::Init()
     */
    public function Init() {
        /**
         * Check if initialization is already done
         */
        if (NULL != $this->_mEngineObject) {
            return FALSE;
        }

        Core::Log()->Write(__('Output library: Initializing output engine object.', 'WEb'), 'debug');

        /**
         * @var string
         */
        $this->_mSkinDir = Core::Config()->Get('output.skin');

        /**
         * @var string
         */
        $this->_mSkinPath = Core::Config()->Get('path.app') . Core::Config()->Get('dir.app.opt') . '/' . Core::Config()->Get('dir.app.tpl') . '/';

        /**
         * @var string
         */
        $this->_mTemplate = Core::Config()->Get('output.template');

        /**
         * @var extension
         */
        $this->_mExtension = Core::Config()->Get('output.extension');

        $this->_mEngineObject = TRUE;

        return TRUE;

    }

    /**
     * (non-PHPdoc)
     * @see Interface_Output::Parse()
     */
    public function Render($outTemplate = NULL, $outDisplay = FALSE) {
        Core::Log()->Write(__('Output library: Rendering template.', 'WEb'), 'debug');

        /**
         * Initialize output engine object.
         */
        $this->Init();

        if (empty($outTemplate)) {

            /**
             * @var string
             */
            $outTemplate = $this->_mTemplate;
        }

        /**
         * @var string
         */
        $tpl_path = $this->_mSkinPath . $this->_mSkinDir . '/' . $outTemplate . $this->_mExtension;

        if (!is_readable($tpl_path)) {
            trigger_error(__('Output library: Template file not found: ', 'WEb') . $tpl_path, 256);
        }

        /**
         * Turn on output buffering.
         */
        ob_start();

        /**
         * Import output variables.
         */
        extract($this->_mData);

        /**
         * Load template file.
         */
        include $tpl_path;

        /**
         * Get parsed output result from the output buffer.
         */
        $out_data = ob_get_contents();

        /**
         * Delete output buffer and turn off output buffering.
         */
        ob_end_clean();

        if (FALSE !== $outDisplay) {
            echo $out_data;

            return TRUE;
        } else {
            return $out_data;
        }
    }

}
?>