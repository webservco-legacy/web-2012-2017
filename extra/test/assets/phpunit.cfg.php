<?php
/**
 * $Id$
 *
 * Unit Tests.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2014, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    WebTest
 * @subpackage Config
 * @version    $Revision$
 * @since      Version 5
 * @access     private
 * @filesource
 */

/**
 * Security token.
 */
define('_WEB', TRUE);

/**
 * Set timezone.
 */
date_default_timezone_set('Europe/Budapest');

/**
 * Load all required files
 */
require_once 'WEb/lib/Core/Core.lib.php';
require_once 'WEb/lib/Core/CoreUtils.lib.php';

require_once 'WEb/lib/Library/Library.lib.php';

require_once 'WEb/lib/Config/Config.lib.php';

require_once 'WEb/lib/Error/Error.lib.php';

require_once 'WEb/lib/I18n/I18n.lib.php';
require_once 'WEb/lib/I18n/I18nUtils.lib.php';

require_once 'WEb/lib/Log/Log.lib.php';

require_once 'WEb/lib/App/App.lib.php';

require_once 'WEb/int/Database/Database.int.php';

require_once 'WEb/lib/Database/Database.lib.php';
require_once 'WEb/lib/Database/Mysql.lib.php';
require_once 'WEb/lib/Database/Mysqli.lib.php';
require_once 'WEb/lib/Database/Pdo.lib.php';
require_once 'WEb/lib/Database/Postgresql.lib.php';
require_once 'WEb/lib/Database/Sqlite3.lib.php';

require_once 'WEb/lib/String/String.lib.php';

require_once 'WEb/lib/Security/Security.lib.php';

require_once 'WEb/lib/Router/Router.lib.php';

require_once 'WEb/lib/Module/Module.lib.php';

require_once 'WEb/lib/Object/Object.lib.php';

require_once 'WEb/lib/Controller/Controller.lib.php';

require_once 'WEb/int/Output/Output.int.php';

require_once 'WEb/lib/Output/Output.lib.php';
require_once 'WEb/lib/Output/Web.lib.php';
require_once 'WEb/lib/Output/Smarty.lib.php';
require_once 'WEb/lib/Output/Phptal.lib.php';

require_once 'WEb/lib/Session/Session.lib.php';

require_once 'WEb/lib/Acl/Acl.lib.php';

require_once 'WEb/lib/Date/Date.lib.php';

require_once 'WEb/lib/Geo/Geo.lib.php';

require_once 'WEb/lib/Graphics/Graphics.lib.php';

require_once 'WEb/lib/Form/Form.lib.php';

require_once 'WEb/lib/Captcha/Captcha.lib.php';

require_once 'WEb/lib/Mail/Mail.lib.php';

require_once 'WEb/lib/Browser/Browser.lib.php';

require_once 'WEb/lib/Iceget/Iceget.lib.php';

require_once 'WEb/lib/Smtp/Smtp.lib.php';

require_once 'WEb/lib/Useragent/Useragent.lib.php';

require_once 'WEb/lib/FacebookApi/FacebookApi.lib.php';

require_once 'WEb/obj/User/User.obj.php';

/**
 * Assets path.
 */
define ('_PU_ASSETS_PATH', '/home/radu/Documents/Projects/Eclipse/ws_WEb/extra/test/assets/');

?>
