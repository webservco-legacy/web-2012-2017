<?php
/**
 * $Id$
 *
 * Content module.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2013, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    DefaultApp
 * @subpackage ContentModule
 * @version    $Revision$
 * @since      Version 7
 * @access     public
 * @filesource
 */

if (!defined('_WEB')) {
	exit();
}

/**
 * Module_Content
 * Model class for Content module.
 * 
 * @author Radu Maier <radu@webserv.co>
 * @package DefaultApp
 * @subpackage ContentModule
 */
class Module_Content extends ModuleApp {

	public function GetAppAssets($app_id) {

		$data = array();
		
		$this->OpenDbExtra();
		
		$q = "SELECT app_asset_content FROM app_assets WHERE app_id = '{$app_id}' AND app_asset_hpbox = '1'";
		
		$result = mysql_query($q, $this->_mDbExtra) or die(mysql_error());
		
		$total = mysql_num_rows($result);
		
		if (0 < $total) {
			
			while ($row = mysql_fetch_assoc($result)) {
				
				$data[] = $row['app_asset_content'];
			}
		}
		
		return $data;
	}

	public function GetAppIntro($app_id) {

		$data = '';
		
		$this->OpenDbExtra();
		
		if (substr(Core::I18n()->GetLocale(), 0, 2) == 'it') {
			$add_sql = '_it';
		} else {
			$add_sql = '';
		}
		
		$q = "SELECT app_intro" . $add_sql . " FROM apps WHERE app_id = '{$app_id}'";
		
		$result = mysql_query($q, $this->_mDbExtra) or die(mysql_error());
		
		$total = mysql_num_rows($result);
		
		if (0 < $total) {
			
			while ($row = mysql_fetch_assoc($result)) {
				
				$data .= str_replace("../../", "http://www.italiamultimedia.com/", $row['app_intro' . $add_sql]);
			}
		}
		
		return $data;
	}

	public function GetAppList() {

		$data = array();
		
		$host_id = Core::Config()->Get('app.id');
		
		$url_pre = NULL;
		
		if (empty($host_id)) {
			
			return FALSE;
		}
		
		$this->OpenDbExtra();
		
		if (substr(Core::I18n()->GetLocale(), 0, 2) == 'it') {
			$add_sql = '_it';
		} else {
			$add_sql = '';
		}
		
		$q = "SELECT C.app_id, A.app_name" . $add_sql . " as app_name, A.app_short_description" . $add_sql . " as app_short_description, A.package_name, A.ios_id, A.google_market, A.amazon_market, A.samsung_market
		FROM app_custom AS C
		LEFT JOIN apps AS A ON (A.app_id = C.app_id)
		WHERE C.host_id = '{$host_id}' AND A.app_id != '{$host_id}'
		AND A.app_status = 'on'
		GROUP BY C.app_id
		ORDER BY C.app_order DESC";
		
		$result = mysql_query($q, $this->_mDbExtra) or die(mysql_error());
		
		$total = mysql_num_rows($result);
		
		if (empty($total)) {
			
			$q = "SELECT A.app_id, A.app_name" . $add_sql . " as app_name, A.app_short_description" . $add_sql . " as app_short_description, A.package_name, A.ios_id, A.google_market, A.amazon_market, A.samsung_market
		FROM apps AS A
		WHERE A.app_id != '{$host_id}'
		AND A.app_status = 'on'
		GROUP BY A.app_id
		ORDER BY A.app_order_num ASC";
			
			$result = mysql_query($q, $this->_mDbExtra) or die(mysql_error());
			
			$total = mysql_num_rows($result);
		}
		
		if (0 < $total) {
			
			while ($row = mysql_fetch_assoc($result)) {
				
				$data[$row['app_id']] = $row;
				
				$data[$row['app_id']]['assets'] = $this->GetAppAssets($row['app_id']);
				
				switch ($this->_mWebAppInfo['market']) {
					
					case 1 :
					default :
						if ($row['google_market'] == 'si') {
							$data[$row['app_id']]['market_url'] = "market://details?id={$row['package_name']}";
						} else {
							$data[$row['app_id']]['market_url'] = 'http://app.italiamultimedia.com/?credits';
						}
						// $data[$row['app_id']]['market_url'] = "market://details?id={$row['package_name']}";
						break;
					case 2 :
						if ($row['samsung_market'] == 'si') {
							$data[$row['app_id']]['market_url'] = "samsungapps://ProductDetail/{$row['package_name']}";
						} else {
							$data[$row['app_id']]['market_url'] = 'http://app.italiamultimedia.com/?credits';
						}
						// $data[$row['app_id']]['market_url'] = "samsungapps://ProductDetail/{$row['package_name']}";
						break;
					case 3 :
						if ($row['amazon_market'] == 'si') {
							$data[$row['app_id']]['market_url'] = "http://www.amazon.com/gp/mas/dl/android?p={$row['package_name']}";
						} else {
							$data[$row['app_id']]['market_url'] = 'http://app.italiamultimedia.com/?credits';
						}
						// $data[$row['app_id']]['market_url'] = "http://www.amazon.com/gp/mas/dl/android?p={$row['package_name']}";
						break;
					case 4 :
						if ($row['ios_id'] != '') {
							$data[$row['app_id']]['market_url'] = "https://itunes.apple.com/app/id{$row['ios_id']}";
						} else {
							$data[$row['app_id']]['market_url'] = 'http://app.italiamultimedia.com/?credits';
						}
						// $data[$row['app_id']]['market_url'] = "https://itunes.apple.com/app/id{$row['ios_id']}";
						break;
				}
			}
		}
		
		return $data;
	}
}
?>
