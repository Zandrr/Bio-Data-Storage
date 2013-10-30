<?php
if(($enable_admin_authentication === 1 and $admin_check === 1) or $enable_authentication === 1){

	// get full URL - for redirections
	$_SERVER['FULL_URL'] = 'http';
	if(isset($_SERVER['HTTPS']) and $_SERVER['HTTPS']=='on'){$_SERVER['FULL_URL'] .=  's';}
		$_SERVER['FULL_URL'] .=  '://';
		if(isset($_SERVER['SERVER_PORT']) and $_SERVER['SERVER_PORT']!='80'){
			$_SERVER['FULL_URL'] .=  $_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'].$_SERVER['SCRIPT_NAME'];
		}
		else{
			$_SERVER['FULL_URL'] .=  $_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
		}
	if(isset($_SERVER['QUERY_STRING']) and $_SERVER['QUERY_STRING']>' '){
	$_SERVER['FULL_URL'] .=  '?'.$_SERVER['QUERY_STRING'];
	}

	if ( !empty($_SESSION['logged_user_infos_ar']) ){ 

		// pass, in session...
		
		$current_user = $_SESSION['logged_user_infos_ar']['username_user'];
		if ($_SESSION['logged_user_infos_ar']['user_type_user'] === $users_table_user_type_administrator_value){
			$current_user_is_administrator = 1;
		}
		else{
			$current_user_is_administrator = 0;
		}

    	// ... but not if not admin for admin page
    	// the $business_logic_included and table_exists - needed for install.php during first install and possibly when Int cr is backend
		if (($enable_admin_authentication === 1 and $admin_check === 1) and $current_user_is_administrator === 0){
			if($business_logic_included === 1 and table_exists($users_table_name)){
				header ('Location: '.$site_url.$dadabik_login_file.'?function=admin&login_message='.rawurlencode($login_messages_ar['incorrect_admin_login']).'&go_to=('.rawurlencode($_SERVER['FULL_URL']).')');
				exit;
			}
			elseif(!isset($business_logic_included)){
				header ('Location: '.$site_url.$dadabik_login_file.'?function=admin&login_message='.rawurlencode($login_messages_ar['incorrect_admin_login']).'&go_to=('.rawurlencode($_SERVER['FULL_URL']).')');
				exit;
			}
		}

	}
	else{ 

		// fail as not in session

		if($enable_admin_authentication === 1 and $admin_check === 1){
			$function = 'admin';
		}
		else{
			$function = 'regular';
		}

		// do nothing if admin page and admin auth is off
		// the $business_logic_included and table_exists - needed for install.php during first install and possibly when Int cr is backend
		if(($enable_authentication === 1 and $admin_check !== 1) or ($enable_admin_authentication === 1 and $admin_check === 1)){
			if($business_logic_included === 1 and table_exists($users_table_name)){
				header ('Location: '.$site_url.$dadabik_login_file.'?function='.$function.'&go_to=('.rawurlencode($_SERVER['FULL_URL']).')');
				exit;
			}
			elseif(!isset($business_logic_included)){
				header ('Location: '.$site_url.$dadabik_login_file.'?function='.$function.'&go_to=('.rawurlencode($_SERVER['FULL_URL']).')');
				exit;
			}
		}
	}
}
else{
	
	// no auth needed, but still use session values if available
	
	if (!empty($_SESSION['logged_user_infos_ar'])){
		$current_user = $_SESSION['logged_user_infos_ar']['username_user'];
		if ($_SESSION['logged_user_infos_ar']['user_type_user'] === $users_table_user_type_administrator_value){
			$current_user_is_administrator = 1;
		}
		else{
			$current_user_is_administrator = 0;
		}
	}
	else{
		$current_user = 'nobody';
		$current_user_is_administrator = 0;
	}
}
?>