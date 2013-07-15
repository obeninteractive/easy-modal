<?php
/*
Plugin Name: Easy Modal
Plugin URI: http://wizardinternetsolutions.com/plugins/easy-modal/
Description: Easy Modal allows you to easily add just about any shortcodes or other content into a modal window. This includes forms such as CF7.
Author: Wizard Internet Solutions
Version: 1.1.9.9
Author URI: http://wizardinternetsolutions.com
*/
add_action('admin_init', '_disable_older_versions', 1 );
function _disable_older_versions()
{
	deactivate_plugins(array(
		ABSPATH.'wp-content/plugins/easy-modal-lite/easy-modal-lite.php',
		ABSPATH.'wp-content/plugins/easy-modal-pro/easy-modal-pro.php'
	));
}	
/* Change Begin */
class Easy_Modal {
	var $Plugin = array(
		'version' => '1.1.9.9',
		'name' => 'Easy Modal',
		'slug' => 'easy-modal',
		'short'=> 'EasyModal'
	);
	var $api_url = 'http://wizardinternetsolutions.com/api/';
	public function __construct()
	{
		$this->Plugin['file'] = __FILE__;
		$this->Plugin['dir'] = PLUGINDIR.'/'. dirname( plugin_basename(__FILE__));
		$this->Plugin['url'] = trailingslashit  (get_bloginfo('wpurl') ).PLUGINDIR.'/'. dirname( plugin_basename(__FILE__) );
		
		
		// Add WPMU Support
		// Add default options on new site creation.
		add_action('wpmu_new_blog', array(&$this, '_wpmu_activation'));
		
		if (is_admin())
		{
			add_action('init', array(&$this,'_activation'),9);
			add_action('init', array(&$this,'_migrate'),10);
            register_activation_hook(__FILE__, array(&$this, '_activation'));

			add_action('admin_menu', array(&$this, '_menus') );
			
			add_filter( 'plugin_action_links', array(&$this, '_actionLinks') , 10, 2 );
            add_filter('mce_buttons_2', array(&$this, '_TinyMCEButtons'), 999);
            add_filter('tiny_mce_before_init', array(&$this, '_TinyMCEInit'));
        }
		$this->_styles_scripts();
		$license_status = get_option('EasyModal_License_Status');
		if($license_status['status']===200)
		{
			add_filter('pre_set_site_transient_update_plugins', array(&$this,'check_updates'));
			add_filter('plugins_api', array(&$this,'get_plugin_info'), 10, 3);
		}
	}
	public function _styles_scripts()
	{
		if (is_admin())
		{
			add_action("admin_head-toplevel_page_easy-modal",array(&$this,'_styles'));
			add_action("admin_head-toplevel_page_easy-modal",array(&$this,'_scripts'));
			add_action("admin_head-easy-modal_page_easy-modal-modals",array(&$this,'_styles'));
			add_action("admin_head-easy-modal_page_easy-modal-modals",array(&$this,'_scripts'));
			add_action("admin_head-easy-modal_page_easy-modal-themes",array(&$this,'_styles'));
			add_action("admin_head-easy-modal_page_easy-modal-themes",array(&$this,'_scripts'));
			add_action("admin_head-easy-modal_page_easy-modal-settings",array(&$this,'_styles'));
			add_action("admin_head-easy-modal_page_easy-modal-settings",array(&$this,'_scripts'));
        }
		else
		{
			add_action('wp_print_styles', array(&$this, '_styles') );
			add_action('wp_print_scripts', array(&$this, '_scripts') );
		}
	}
	private function _migrate_EM()
	{
		global $wp;
		$o_modal_list = get_option('EasyModal');
		if(!is_array($o_modal_list))
		{
			$o_modal_list = unserialize($o_modal_list);
		}
		foreach($o_modal_list as $id)
		{
			$Modal = get_option('EasyModal_'.$id);
			if(!is_array($Modal))
			{
				$Modal = unserialize($Modal);
			}
			$Modal['name'] = $Modal['title'];
			$this->updateModalSettings('new',$Modal);
			delete_option('EasyModal_'.$id);
		}
		delete_option('eM_version');
		delete_option('EasyModal');
	}
	private function _migrate_EM_Lite()
	{
		global $wp;
		$o_modal_list = get_option('EasyModalLite_ModalList');
		if(!is_array($o_modal_list))
		{
			$o_modal_list = unserialize($o_modal_list);
		}
		foreach($o_modal_list as $id => $title)
		{
			$Modal = get_option('EasyModalLite_Modal-'.$id);
			if(!is_array($Modal))
			{
				$Modal = unserialize($Modal);
			}
			$this->updateModalSettings('new',$Modal);
			delete_option('EasyModalLite_Modal-'.$id);
		}
		$Theme = get_option('EasyModalLite_Theme-1');
		if(!is_array($Theme))
		{
			$Theme = unserialize($Theme);
		}
		$this->updateThemeSettings(1,$Theme);
		delete_option('EasyModalLite_Theme-1');
		$o_settings = get_option('EasyModalLite_Settings');
		if(!is_array($o_settings))
		{
			$o_settings = unserialize($o_settings);
		}
		$this->updateSettings($o_settings);
		delete_option('EasyModalLite_Settings');
		delete_option('EasyModalLite_Version');
		delete_option('EasyModalLite_ModalList');
		delete_option('EasyModalLite_ThemeList');
	}
	private function _migrate_EM_Pro()
	{
		global $wp;
		$o_theme_list = get_option('EasyModalPro_ModalList');
		if(!is_array($o_theme_list))
		{
			$o_theme_list = unserialize($o_theme_list);
		}
		foreach($o_theme_list as $id => $name)
		{
			$Theme = get_option('EasyModalPro_Theme-'.$id);
			if(!is_array($Theme))
			{
				$Theme = unserialize($Theme);
			}
			$theme = $this->updateThemeSettings('new',$Theme);
			delete_option('EasyModalPro_Theme-'.$id);
			$themes[$id] = $theme['theme_id'];
		}
		delete_option('EasyModalPro_ThemeList');

		$themes = $this->getThemeList();

		$o_modal_list = get_option('EasyModalPro_ModalList');
		if(!is_array($o_modal_list))
		{
			$o_modal_list = unserialize($o_modal_list);
		}
		foreach($o_modal_list as $id => $title)
		{
			$Modal = get_option('EasyModalPro_Modal-'.$id);
			if(!is_array($Modal))
			{
				$Modal = unserialize($Modal);
			}
			$Modal['theme'] = isset($themes[$id]) ? $theme[$id] : 1;
			$this->updateModalSettings('new',$Modal);
			delete_option('EasyModalPro_Modal-'.$id);
		}
		delete_option('EasyModalPro_ModalList');

		$o_settings = get_option('EasyModalPro_Settings');
		if(!is_array($o_settings))
		{
			$o_settings = unserialize($o_settings);
		}
		$o_settings['license'] = get_option('EasyModalPro_License');
		delete_option('EasyModalPro_License');
		delete_option('EasyModalPro_Settings');
		delete_option('EasyModalPro_Version');
		$this->updateSettings($o_settings);
	}
	public function _activation()
	{
		if(!get_option('EasyModal_Version'))
		{
			$this->resetOptions();
		}
		update_option('EasyModal_Version', $this->Plugin['version']);
	}
	public function _migrate()
	{
		// detect EM Free
		if(get_option('eM_version'))
		{
			$this->_migrate_EM();
		}
		// detect EM Lite
		if(get_option('EasyModalLite_Version'))
		{
			$this->_migrate_EM_Lite();
		}
		// detect EM Lite
		if(get_option('EasyModalPro_Version'))
		{
			$this->_migrate_EM_Pro();
		}
	}
	public function _wpmu_activation($blog_id, $user_id, $domain, $path, $site_id, $meta)
	{
		// Make sure the user can perform this action and the request came from the correct page.
		switch_to_blog($blog_id);
		// Use a default value here if the field was not submitted.
		if(!get_option('EasyModal_Version'))
		{
			$this->resetOptions();
		}
		update_option('EasyModal_Version', $this->Plugin['version']);
		restore_current_blog();
	}
	public function resetOptions()
	{
		update_option('EasyModal_Settings', $this->defaultSettings());
		foreach(get_option('EasyModal_ModalList',array()) as $id => $name)
		{
			$this->deleteModal($id);
		}
		update_option('EasyModal_ModalList', array());
		foreach(get_option('EasyModal_ThemeList',array()) as $id => $name)
		{
			$this->deleteTheme($id);
		}
		update_option('EasyModal_ThemeList', array('1'=>'Black'));
		$theme = $this->defaultThemeSettings();
		$theme['name'] = 'Black';
		update_option('EasyModal_Theme-1', $theme);
	}
	
	public function _styles()
	{
		if (!is_admin())
		{
			wp_enqueue_style($this->Plugin['slug'].'-styles', $this->Plugin['url'].'/inc/css/styles.min.css');
		}
		else
		{
			wp_enqueue_style($this->Plugin['slug'].'-admin-styles', $this->Plugin['url'].'/inc/css/admin-styles.min.css');
		}

	}

	public function _scripts()
	{
		wp_enqueue_script('jquery');
		if (!is_admin())
		{
			wp_enqueue_script( 'jquery-form', $this->Plugin['url'].'/inc/js/jquery.form.js', array( 'jquery' ));
			wp_enqueue_script($this->Plugin['slug'].'-scripts', $this->Plugin['url'].'/inc/js/easy-modal.min.js', array('jquery'));
			$data = array(
				'ajaxurl' => $this->Plugin['url'].'/inc/ajax/content.php',
				'modals' => $this->enqueue_modals(),
				'themes' => $this->enqueue_themes(),
				'settings' => $this->getSettings()
			);
			$params = array(
				'l10n_print_after' => 'easymodal = ' . json_encode($data) . ';'
			);
			wp_localize_script( $this->Plugin['slug'].'-scripts', 'easymodal', $params );
		}
		else
		{
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-accordion'); 
			wp_enqueue_script('jquery-ui-slider'); 
			wp_enqueue_script('jquery-colorpicker', $this->Plugin['url'].'/inc/js/colorpicker.min.js',  array('jquery'));
			wp_enqueue_script('easy-modal-admin', $this->Plugin['url'].'/inc/js/easy-modal-admin.min.js',  array('jquery', 'jquery-ui-core', 'jquery-ui-slider', 'jquery-colorpicker'));
		}
	}
	public function _menus()
	{
		add_menu_page( 'Home', $this->Plugin['name'] , 'edit_posts', $this->Plugin['slug'], array(&$this, 'dashboard_page'),'',1000);
		add_submenu_page( $this->Plugin['slug'], 'Modals', 'Modals', 'edit_posts', $this->Plugin['slug'].'-modals', array(&$this, 'modal_page')); 
		add_submenu_page( $this->Plugin['slug'], 'Theme', 'Theme', 'edit_themes', $this->Plugin['slug'].'-themes', array(&$this, 'theme_page')); 
		add_submenu_page( $this->Plugin['slug'], 'Settings', 'Settings', 'manage_options', $this->Plugin['slug'].'-settings', array(&$this, 'settings_page')); 
	}   
	public function _actionLinks( $links, $file )
	{
		if ( $file == plugin_basename( __FILE__ ) )
		{
			$posk_links = '<a href="'.get_admin_url().'admin.php?page='.$this->Plugin['slug'].'-settings">'.__('Settings').'</a>';
			array_unshift( $links, $posk_links );
		}
		return $links;
	}
	public function _TinyMCEButtons($orig)
	{
		return array_merge($orig, array('styleselect'));
	}
	public function _TinyMCEInit($initArray)
	{
		global $wpdb;
		// Custom classes
		$modals = $this->getModalList();
		$customClasses = array();
		foreach($modals as $key => $modal)
		{
			$customClasses['eModal'.$modal] = 'eModal-'.$key;
		}        
		// Build array
		$initArray['theme_advanced_styles'] = isset($initArray['theme_advanced_styles']) ? $initArray['theme_advanced_styles'] : '';
		foreach($customClasses as $name => $css)
		{
			$initArray['theme_advanced_styles'] .= $name.'='.$css.';';
		} 
		$initArray['theme_advanced_styles'] = rtrim($initArray['theme_advanced_styles'], ';'); // Remove final semicolon from list
		return $initArray;
	}

	public function dashboard_page()
	{
		require_once(ABSPATH.PLUGINDIR.'/'.dirname(plugin_basename(__FILE__)).'/inc/dashboard.php');
	}

	public function settings_page()
	{
		require_once(ABSPATH.PLUGINDIR.'/'.dirname(plugin_basename(__FILE__)).'/inc/settings.php');
	}
	public function getSettings()
	{
		return get_option('EasyModal_Settings');
	}
	public function defaultSettings()
	{
		return array();
	}
	public function updateSettings( $post = NULL )
	{
		global $wp;
		$settings = get_option('EasyModal_Settings');
		if(!$settings)
		{
			$settings = $this->defaultSettings();
		}
		elseif(!is_array($settings))
		{
			$settings = unserialize($settings);	
		}
		if($post)
		{
			$post = stripslashes_deep($post);
			extract($post);
			if (isset($license))
			{
				$current = get_option('EasyModal_License');
				if($license != $current)
				{
					$redirect = true;
				}
				update_option('EasyModal_License', $license);
				$license_status = $this->check_license($license);
				update_option('EasyModal_License_Status', $license_status);
				if($license_status['status']===200)
				{
					update_option('EasyModal_License_LastChecked', strtotime(date("Y-m-d H:i:s")));
					delete_option('_site_transient_update_plugins');
				}
			}
			if (isset($autoOpen))
			{
				$settings['autoOpen'] = $autoOpen;

			}
			else
			{
				$settings['autoOpen'] = 'false';
			}
			if (isset($autoOpenId))
			{
				$settings['autoOpenDelay'] = $autoOpenDelay;
			}
			if (isset($autoOpenId))
			{
				$settings['autoOpenId'] = $autoOpenId;
			}
			if (isset($exitOpen)) 
			{
				$settings['exitOpen'] = $exitOpen;
			}
			if (isset($exitOpenId))
			{
				$settings['exitOpenId'] = $exitOpenId;
			}
		}
		update_option('EasyModal_Settings', $settings);
		if(!empty($redirect) && $redirect == true)
		{
			wp_redirect('admin.php?page='.$this->Plugin['slug'].'-settings',302);
			exit;
		}
		return $settings;
	}
	
	/* Modal Functions */
	public function modal_page()
	{
		require_once(ABSPATH.PLUGINDIR.'/'.dirname(plugin_basename(__FILE__)).'/inc/modals.php');
	}
	public function getModalList()
	{
		return get_option('EasyModal_ModalList',array());
	}
	public function getModalSettings($modal_id)
	{
		return get_option('EasyModal_Modal-'.$modal_id);
	}
	public function defaultModalSettings()
	{
		return array(
			'name'	=> 'change_me',
			'title' => '',
			'content' => '',
			'cf7form' => false,
			'gravityform' => false,
			'theme' => 1,
			'userMaxHeight' => 0,
			'userHeight' => 0,
			'userMaxWidth' => 0,
			'userWidth' => 0,
			'overlayClose' => 'false',
			'overlayEscClose' => 'false',
		);
	}
	public function updateModalSettings( $modal_id, $post = NULL )
	{
		global $wp;
		$modals = get_option('EasyModal_ModalList',array());
		if($modal_id == 'new')
		{
			$highest = 0;
			foreach($modals as $id => $name)
			{
				if($id > $highest) $highest = $id;
			}
			$modal_id = $highest+1;
		}
		if(array_key_exists($modal_id, $modals))
		{
			$settings = get_option( 'EasyModal_Modal-'.$modal_id);
		}
		else
		{
			$settings = $this->defaultModalSettings();
		}
		$settings['modal_id'] = $modal_id;
		if($post)
		{
			$post = stripslashes_deep($post);
			extract($post);
			if (isset($name))
			{
				$settings['name'] = $name;
			}
			if (isset($title))
			{
				$settings['title'] = apply_filters('content_save_pre', $title);
			}
			if (isset($content))
			{
				if(strstr($content,'[contact-form')!= NULL){ $settings['cf7form'] = true; }
				else { $settings['cf7form'] = false; }
				$settings['content'] = $content;
			}
			if (isset($content))
			{
				if(strstr($content,'[gravityform')!= NULL){ $settings['gravityform'] = true; }
				else { $settings['gravityform'] = false; }
				$settings['content'] = $content;
			}
			if (isset($userMaxHeight)){
				$settings['userMaxHeight'] = $userMaxHeight;
			}
			if (isset($userHeight))
			{
				$settings['userHeight'] = $userHeight;
			}
			if (isset($userMaxWidth))
			{
				$settings['userMaxWidth'] = $userMaxWidth;
			}
			if (isset($userWidth))
			{
				$settings['userWidth'] = $userWidth;
			}
			if (isset($overlayClose))
			{
				$settings['overlayClose'] = $overlayClose;
			}
			else
			{
				$settings['overlayClose'] = 'false';
			}
			if (isset($overlayEscClose))
			{
				$settings['overlayEscClose'] = $overlayEscClose;
			}
			else
			{
				$settings['overlayEscClose'] = 'false';
			}
		}
		$modals[$modal_id] = $settings['name'];
		update_option('EasyModal_ModalList', $modals);
		update_option('EasyModal_Modal-'.$modal_id, $settings);
		return $settings;
	}
	public function deleteModal( $modal_id )
	{
		$modals = get_option('EasyModal_ModalList',array());
		unset($modals[$modal_id]);
		update_option('EasyModal_ModalList', $modals);
		delete_option('EasyModal_Modal-'.$modal_id);
	}
	public function enqueue_modals()
	{
		$modals = $this->getModalList();
		$settings = array();
		$setting = $this->getModalSettings('Link');
		$settings['Link'] = array(
			"requestData" => array(
				"modalId" => 'Link'
			),
			"theme" => '1',
			"type"	=> 'Link'
		);
		foreach($modals as $key => $value)
		{
			$setting = $this->getModalSettings($key);
			$setting["requestData"] = array('modalId' => $key);
			$settings[$key] = $setting;
		}
		return $settings;
	}
	/* Theme Functions */
	public function theme_page()

	{
		require_once(ABSPATH.PLUGINDIR.'/'.dirname(plugin_basename(__FILE__)).'/inc/themes.php');
	}	
	public function getThemeList()
	{
		return get_option('EasyModal_ThemeList');
	}
	public function getThemeSettings($theme_id)
	{
		return get_option('EasyModal_Theme-'.$theme_id);
	}
	public function defaultThemeSettings()
	{
		return array(
			'name' => 'change_me',
			'overlayColor' => '#000000',
			'overlayOpacity' => '75',
			'containerBgColor' => '#000000',
			'containerPadding' => '10',
			'containerBorderColor' => '#ffffff',
			'containerBorderStyle' => 'solid',
			'containerBorderWidth' => '3',
			'containerBorderRadius' => '20',
			'closeBgColor' => '#000000',
			'closeFontColor' => '#ffffff',
			'closeFontSize' => '15',
			'closeBorderRadius' => '10',
			'closeSize' => '20',
			'closePosition' => 'topright',
			'contentTitleFontColor' => '#ffffff',
			'contentTitleFontSize' => '32',
			'contentTitleFontFamily' => 'Tahoma',
			'contentFontColor' => '#ffffff'
		);
	}
	public function updateThemeSettings( $theme_id, $post = NULL )
	{
		global $wp;
		$themes = get_option('EasyModal_ThemeList');
		if($theme_id == 'new')
		{
			$highest = 0;
			foreach($themes as $id => $name)
			{
				if($id > $highest) $highest = $id;
			}
			$theme_id = $highest+1;
		}
		if(array_key_exists($theme_id, $themes))
		{
			$settings = get_option( 'EasyModal_Theme-'.$theme_id);
		}
		else
		{
			$settings = $this->defaultThemeSettings();
		}
		if($post)
		{
			$post = stripslashes_deep($post);
			extract($post);
			$settings['theme_id'] = $theme_id;
			if (isset($name))
			{
				$settings['name'] = $name;
			}
			if (isset($overlayColor) && preg_match('/^#[a-f0-9]{6}$/i', $overlayColor))
			{
					$settings['overlayColor'] = $overlayColor;
			}
			if (isset($overlayOpacity) && $overlayOpacity >= 0 && $overlayOpacity <= 100){
				$settings['overlayOpacity'] = $overlayOpacity;
			}
			if (isset($containerBgColor) && preg_match('/^#[a-f0-9]{6}$/i', $containerBgColor))
			{
				$settings['containerBgColor'] = $containerBgColor;
			}
			if (isset($containerPadding))
			{
				$settings['containerPadding'] = $containerPadding;
			}
			if (isset($containerBorderColor) && preg_match('/^#[a-f0-9]{6}$/i', $containerBorderColor))
			{
				$settings['containerBorderColor'] = $containerBorderColor;
			}
			if (isset($containerBorderStyle))
			{
				$settings['containerBorderStyle'] = $containerBorderStyle;
			}
			if (isset($containerBorderWidth))
			{
				$settings['containerBorderWidth'] = $containerBorderWidth;
			}
			if (isset($containerBorderRadius))
			{
				$settings['containerBorderRadius'] = $containerBorderRadius;
			}
			if (isset($closeBgColor) && preg_match('/^#[a-f0-9]{6}$/i', $closeBgColor))
			{
				$settings['closeBgColor'] = $closeBgColor;
			}
			if (isset($closeFontColor) && preg_match('/^#[a-f0-9]{6}$/i', $closeFontColor))
			{
				$settings['closeFontColor'] = $closeFontColor;
			}
			if (isset($closeFontSize))
			{
				$settings['closeFontSize'] = $closeFontSize;
			}
			if (isset($closeBorderRadius))
			{
				$settings['closeBorderRadius'] = $closeBorderRadius;
			}
			if (isset($closeSize))
			{
				$settings['closeSize'] = $closeSize;
			}
			if (isset($closePosition))
			{
				$settings['closePosition'] = $closePosition;
			}
			if (isset($contentTitleFontColor) && preg_match('/^#[a-f0-9]{6}$/i', $contentTitleFontColor))
			{
				$settings['contentTitleFontColor'] = $contentTitleFontColor;
			}
			if (isset($contentTitleFontSize))
			{
				$settings['contentTitleFontSize'] = $contentTitleFontSize;
			}
			if (isset($contentTitleFontFamily))
			{
				$settings['contentTitleFontFamily'] = $contentTitleFontFamily;
			}
			if (isset($contentFontColor) && preg_match('/^#[a-f0-9]{6}$/i', $contentFontColor))
			{
				$settings['contentFontColor'] = $contentFontColor;
			}
		}
		$themes[$theme_id] = $settings['name'];
		update_option('EasyModal_ThemeList', $themes);
		update_option('EasyModal_Theme-'.$theme_id, $settings);
		return $settings;
	}
	public function deleteTheme( $theme_id )
	{
		$themes = $this->getThemeList();
		unset($themes[$theme_id]);
		update_option('EasyModal_ThemeList', $themes);
		delete_option('EasyModal_Theme-'.$theme_id);
	}
	public function enqueue_themes()
	{

		$settings = array(
			1 => $this->getThemeSettings(1)
		);
		return $settings;
	}
	
	
	public function check_license()
	{
		$args = new stdClass;
		$args->slug = $this->Plugin['slug'];
		$args->version = $this->Plugin['version'];
		$request_string = $this->uopdate_request('license_check', $args);
		$request = wp_remote_post($this->api_url, $request_string);
		if (is_wp_error($request))
		{
			$res = new WP_Error('plugins_api_failed', __('An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>'), $request->get_error_message());
		}
		else
		{
			$res = unserialize($request['body']);
			if ($res === false)
			{
				$res = new WP_Error('plugins_api_failed', __('An unknown error occurred'), $request['body']);
			}
		}
		return $res;
	}
	public function uopdate_request($action, $args)
	{
		global $wp_version;
		return array(
			'body' => array(
				'action' => $action, 
				'request' => serialize($args),
				'domain'  => get_bloginfo('url'),
				'api_key' => md5(get_bloginfo('url')),
				'license' => get_option('EasyModal_License'),
				'wp_version'=> $wp_version
			),
			'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
		);	
	}
	// Activated With Valid License
	public function check_updates($checked_data)
	{
		if (empty($checked_data->checked))
		{
			return $checked_data;
		}
		$request_args = array(
			'slug' => $this->Plugin['slug'],
			'version' => $checked_data->checked[$this->Plugin['slug'] .'/'. $this->Plugin['slug'] .'.php'],
		);
		$request_string = $this->uopdate_request('basic_check', $request_args);
		// Start checking for an update
		$raw_response = wp_remote_post($this->api_url, $request_string);
		if (!is_wp_error($raw_response) && ($raw_response['response']['code'] == 200))
		{
			$response = unserialize($raw_response['body']);
		}
		if (is_object($response) && !empty($response)) // Feed the update data into WP updater
		{
			$checked_data->response[$this->Plugin['slug'] .'/'. $this->Plugin['slug'] .'.php'] = $response;
		}
		return $checked_data;
	}
	public function get_plugin_info($def, $action, $args)
	{
		if (empty($args->slug) || $args->slug != $this->Plugin['slug'])
		{
			return false;
		}
		// Get the current version
		$plugin_info = get_site_transient('update_plugins');
		$current_version = $plugin_info->checked[$this->Plugin['slug'] .'/'. $this->Plugin['slug'] .'.php'];
		$args->version = $current_version;
		$request_string = $this->uopdate_request($action, $args);
		$request = wp_remote_post($this->api_url, $request_string);
		if (is_wp_error($request))
		{
			$res = new WP_Error('plugins_api_failed', __('An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>'), $request->get_error_message());
		}
		else
		{
			$res = unserialize($request['body']);
			if ($res === false)
			{
				$res = new WP_Error('plugins_api_failed', __('An unknown error occurred'), $request['body']);
			}
		}
		return $res;
	}
}
$license_status = get_option('EasyModal_License_Status');
if(file_exists(ABSPATH.PLUGINDIR.'/'.dirname(plugin_basename(__FILE__)).'/easy-modal-pro.php') && isset($license_status['status']) && $license_status['status']===200)
{
    include(ABSPATH.PLUGINDIR.'/'.dirname(plugin_basename(__FILE__)).'/easy-modal-pro.php');
	$EM = new Easy_Modal_Pro;
}
else
{
	$EM = new Easy_Modal;
}