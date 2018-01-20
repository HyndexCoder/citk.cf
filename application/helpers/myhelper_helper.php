<?php
defined('BASEPATH') or exit('No direct script access allowed');

//Humanize the dates
function format_date($date){
	$date_str = strtotime($date);

	if ($date_str){
		$date_str = date('F j, Y', $date_str);
	}else{
		$date_str = '$date';
	}

	return $date_str;
}

/**
 * Converts text to probable url of teacher's avatar.
 * Also replaces the hostname if it is ROOT PLACEHOLDE
 * to the current hostname
 */
function teacher_avatar($avatar = null){
	$avatar = empty($avatar)? CC_DEFAULT_AVATAR: $avatar;

	if (filter_var($avatar, FILTER_VALIDATE_URL)){
		return $avatar;
	}
	else{
		/** 
		 * If  the root placeholder is found in the string
		 * then replace it with the actual webiste root
		 * defined in the config.php file.
		*/
		$avatar = relevent_to_real_url($avatar);

		if (filter_var($avatar, FILTER_VALIDATE_URL)){
			return $avatar;
		}
		else return base_url(CC_AVATAR_BASE.$avatar);
	}
}

/**
 * Checks if the hostname is of current server
 * if yes then replaces it with the ROOT PLACEHOLDER
 * so that these links remain relevent even when
 * the hostname of this website changes.
 * 
 * Simply returns the same input if any error occurs.
 * Because we want to avoid errors.
 */
function make_my_url_relevent($url){
	$hostname = parse_url(base_url(), PHP_URL_HOST);

	if (empty($hostname)) return $url;

	$given_url = parse_url($url);

	if (!isset($given_url['host'])) return $url;

	if (strtolower($hostname) == strtolower($given_url['host'])){
		$given_url['host'] = CC_ROOT_PLACEHOLDER;

		return unparse_url($given_url);
	}
	else return $url;
}

/**
 * Converts relevent urls and other urls back to real urls
 */
function relevent_to_real_url($relevent_url){
	$hostname = parse_url(base_url(), PHP_URL_HOST);

	if (empty($hostname)) return $relevent_url;

	$given_url = parse_url($relevent_url);

	if (!isset($given_url['host'])) return $relevent_url;

	if (CC_ROOT_PLACEHOLDER == $given_url['host']){
		$given_url['host'] = $hostname;

		return unparse_url($given_url);
	}

	return $relevent_url;
}

/** Converts class code such as 131 to human readeable
 * text like "Degree 3rd year 5th semester"
 * 1st digit, module: 1:Deg, 2: Dip
 * Second digit, year
 * Third digit, semster, 1st or second
 * Returns false on failure
 */
function classcode_to_name($code = '111', $html = 'sup'){
	$code = (string)$code;

	if (strlen($code) < 3) return 'false';
	else $code = substr($code, 0, 3);

	$module = (int)$code[0];
	$year = (int)$code[1];
	$sem = (int)$code[2];

	if($year < 1) $year = 1;
	else if($year > 4) $year = 4;

	$sem = ($year-1)*2+($sem);

	if($sem < 1) $sem = 1;
	else if($sem > 8) $sem = 8;

	if ($html !== false){
		if (is_string($html)){
			$opening_tag = '<'.$html.'>';
			$closing_tag = '</'.$html.'>';
		}
		else{
			$opening_tag = '<sup>';
			$closing_tag = '</sup>';
		}
	}
	else{
		$opening_tag = '';
		$closing_tag = '';
	}

	return ($module==1?'Degree':'Diploma').' '.$year.$opening_tag.numposition_suffix($year).$closing_tag.' year '.$sem.$opening_tag.numposition_suffix($sem).$closing_tag.' semester';
}

/**
 * Converts 1 to st, 2 to nd, 3 to rd and rest to th
 * Also convers 21 to st and 33 to rd
 */
function numposition_suffix($n = 1){
	$suffix = 'th';

	//Because 11 isn't 11st. Its 11th
	$n = $n%100;
	if($n <11 || $n >20){
		$n = $n%10;

		switch($n){
			case 1: $suffix = 'st'; break;
			case 2: $suffix = 'nd'; break;
			case 3: $suffix = 'rd'; break;
		}
	}


	return $suffix;
}

//Provides filter parameters to site_url() function
function site_url_filter($link = '', $protocol = 'http', $branch = null, $class = null, $subject = null){
	$url = site_url($link, $protocol);

	$query = array();

	if ($branch == null){
		if (CC_BRANCH != null)
			$query['branch'] = CC_BRANCH;
	}
	else{
		$query['branch'] = $branch;
	}

	if ($class == null){
		if (CC_CLASS != null)
			$query['class'] = CC_CLASS;
	}
	else{
		$query['class'] = $class;
	}

	if ($subject != null){
		$query['subject'] = $subject;
	}

	return add_url_query($url, $query);
}

//Adds query/GET parameters to urls, replaces if already exists
function add_url_query($url, $query = array()){
	$query = is_array($query)? $query: array($query);

	$parsed_url = parse_url($url);
	parse_str(isset( $parsed_url['query'] )? $parsed_url['query']: '', $_query);

	foreach ($query as $param=>$val){
		if ($val != null)
			$_query[$param] = $val;
	}

	$parsed_url['query'] = http_build_query($_query);

	return unparse_url($parsed_url);
}

function unparse_url($parsed_url) { 
	$scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : ''; 
	$host     = isset($parsed_url['host']) ? $parsed_url['host'] : ''; 
	$port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : ''; 
	$user     = isset($parsed_url['user']) ? $parsed_url['user'] : ''; 
	$pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : ''; 
	$pass     = ($user || $pass) ? "$pass@" : ''; 
	$path     = isset($parsed_url['path']) ? $parsed_url['path'] : ''; 
	$query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : ''; 
	$fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : ''; 
	return "$scheme$user$pass$host$port$path$query$fragment"; 
}

//Manages the paging, returns offset, next page and prev page
function offset_manager($page, $num){
	$page = $page < 1? 1: $page;
	return $num * ($page -1);
}

//Functions to easily include javascripts
function script_tag($link = '', $echo = false){
	$link = 'assets/js/'.$link;
	$html = '<script type="text/javascript" src="'.base_url($link).'"></script>'.PHP_EOL;

	if ($echo){
		echo $html;
	}else{
		return $html;
	}
	return '';
}

function jquery($echo = false){
	return script_tag('jquery.min.js', $echo);
}
function helper_js($echo = false){
	return script_tag('helper.js', $echo);
}

//Functions to easily include css
function css_tag($link = '', $echo = false){
	$link = 'assets/css/'.$link;
	$html = '<link type="text/css" rel="stylesheet" href="'.base_url($link).'">'.PHP_EOL;

	if ($echo){
		echo $html;
	}else{
		return $html;
	}
	return '';
}
function css_import($link = '', $echo = false){
	$link = 'assets/css/'.$link;
	$html = '<style>@import url(\''.base_url($link).'\');</style>';

	if ($echo){
		echo $html;
	}else{
		return $html;
	}
	return '';
}

/**
 * Function helps db insert and update functions
 * to do logging easily
 */
function easy_log($type, $id, $areas = '', $res = false, $CI = null){
	if ($res){
		//Logging

		//Getting CI instance
		if (is_null($CI)){
			$CI =& get_instance();
		}

		$CI->load->model('log_model');

		$user_id = config_item('auth_user_id');
		$user_id = (is_null($user_id) || $user_id === false)? 'NULL':$user_id;

		$username = config_item('auth_username');
		$username = (is_null($username) || $username === false)? 'NULL':$username;

		$log_res = $CI->log_model->log($type, $type.'-'.$id, sprintf('{{time}} by %s(%s) - %s', $username, $user_id, $areas));
	}
}

/**
 * Sets an flash message that will appear on the next page
 */
function set_flashmsg($next, $message = '', $type = 'general'){
	//Determining color
	$type = strtolower($type);
	switch($type){
		case 'success':$color = 'green'; break;
		case 'error':$color = 'red'; break;
		case 'warning':$color = 'orange'; break;
		default:$color = 'white';
	}

	//Setting message
	$_SESSION['flash_message'] = $message;
	$_SESSION['flash_color'] = $color;

	redirect($next);
	exit;
}

/**
 * Functions returns flash msg data if set
 * or returns null if not set
 */
function get_flashmsg(){
	//Check if message exists
	if (!isset($_SESSION['flash_message'])) return null;

	$message = $_SESSION['flash_message'];

	if (! isset($_SESSION['flash_color'])) $color = 'white';
	else $color = $_SESSION['flash_color'];

	//Removing message
	unset($_SESSION['flash_message'], $_SESSION['flash_color']);

	return array(
		'message' => $message,
		'color' => htmlspecialchars($color)
	);
}