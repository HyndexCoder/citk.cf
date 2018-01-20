<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - MY Controller
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2017, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

require_once APPPATH . 'third_party/community_auth/core/Auth_Controller.php';

class MY_Controller extends Auth_Controller
{
	
	protected $main_method = 'view';

	/**
	 * Class constructor
	 */
	public function __construct()
	{
		parent::__construct();

		//Loading auth variables
		$this->is_logged_in();

		$branch = $this->input->get('branch');

		//Website name
		define('CC_SITENAME', 'citk.cf');

		//Website theme color
		define('CC_COLOR', 'blue');

		//Base path for teachers' avatars
		define('CC_AVATAR_BASE', 'assets/images/teachers/');

		//Default avatar for teachers
		define('CC_DEFAULT_AVATAR', 'teacher-avatar.png');

		//Placeholder for own website root, used to make urls relevent
		define('CC_ROOT_PLACEHOLDER', '{{cc_mywebsiteroot}}');

		define('CC_BRANCH', $branch == null? 'CSE' : $branch);
		define('CC_CLASS', $this->input->get('class') );

		if (!defined('URI_STRING'))
			define('URI_STRING', $this->uri->uri_string());
	}

	//Plan to remap requests in certain controllers
	//Is used by `_remap` method
	protected function __remap($method, $args){
		if(method_exists($this, $method)){
			//Only public methods are served to the client
			$reflection = new ReflectionMethod($this, $method);
			if ($reflection->isPublic()) {
				return call_user_func_array(array($this, $method), $args);
			}
        }
		//Else the main_method will be used if it can serve anyway
		array_unshift($args, $method);
		return call_user_func_array(array($this, $this->main_method), $args );
	}
}
