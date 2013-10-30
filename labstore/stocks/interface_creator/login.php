<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Pragma: no-cache");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-control: private");
header('Content-Type: text/html; charset=utf-8');
// coming in from check_login.php
include ("../config.php");
include ("functions.php");
include ("common_start.php");
include ("check_installation.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Language" content="en-us" />
    <title>
<?php
if($mainsite_name != '')
{echo ($mainsite_name.' - ');}
if($parentsite_name != '')
{echo ($parentsite_name.' - ');}
echo $site_name; 
?>  - Log in</title>
<link rel="stylesheet" href="../style.css" type="text/css" />
<meta name="Description" content="<?php echo ($meta_description); ?>" />
<meta name="Keywords" content="<?php echo ($meta_keywords); ?>" />
<meta name="Generator" content="<?php echo ($meta_generator); ?>" />
<script language="JavaScript" type="text/javascript">
//<![CDATA[
<!--
function fill_cap(city_field){

   temp = 'document.contacts_form.'+city_field+'.value';

   city = eval(temp);
	cap=open("fill_cap.php?city="+escape(city),"schermo","toolbar=no,directories=no,menubar=no,width=170,height=250,resizable=yes");
}

function checkCR(evt) {
var evt  = (evt) ? evt : ((event) ? event : null);
var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
if ((evt.keyCode == 13) && (node.type=="text")) {return false;}
}
document.onkeypress = checkCR;

//opens a js popup with customizable options. Popup will close and open
//again upon call from pwd-generator link
var mywindow;
function generic_js_popup(url,name,w,h){
	if (mywindow!=null && !mywindow.closed){
	mywindow.close();
	}
var options;
options = "resizable=yes,toolbar=0,status=1,menubar=0,scrollbars=1, width=" + w + ",height=" + h + ",left="+(screen.width-w)/2+",top="+(screen.height-h)/6; 
mywindow = window.open(url,name,options);
mywindow.focus();
}

//opens js popup
function popitup2(url)
{
window.name='popupfirst';
newwindow=window.open(url,'poppedsecond','height=500,width=500,scrollbars=yes,resizable=yes');
if (window.focus) {newwindow.focus()}
return false;
}
function confipop(url)
{
var agree = confirm ("Are you sure?");
if (agree)
 {
newwindow=window.open(url,'poppedfirst','<?php echo $popup_parameters; ?>');
 if (window.focus) {newwindow.focus()}
 }
return false;
}
// -->
//]]>
</script>
</head>
<body>
<?php
// where from - GET values first
if (!empty($_GET['go_to'])){
$go_to = $_GET['go_to'];
}
elseif (!empty($_POST['go_to'])){
$go_to = $_POST['go_to'];
}
else{
$go_to = '('.rawurlencode($dadabik_main_file).')'; // added brackets
}
// redirect location after logout
$location_after_logout = $site_url.$dadabik_main_file;
if ($go_to == 'parent_front'){ // when Interface Creator is a backend
$location_after_logout = $parentsite_url;
}
// what type of check - admin or regular login check, or logout. GET first
if (!empty($_GET['function'])){
$function = $_GET['function'];
}
elseif(!empty($_POST['function'])){
$function = $_POST['function'];
}
else{
$function = 'regular';
}

if (!empty($_GET['login_message'])){
$login_message = $_GET['login_message'];
}

/////// for logout /////// 

if ($function == 'logout'){
	unset($_SESSION['logged_user_infos_ar']);
	if (isset($_COOKIE['interface_creator_username']) or isset($_COOKIE['interface_creator_md5_password'])){
		setcookie('interface_creator_username'); // reset cookie
		setcookie('interface_creator_md5_password'); // reset cookie
	}
	header ('Location: '.$location_after_logout);
	die();
}

/////// end for logout /////// 

/////// for login /////// 

// if no values to check
if (
( empty($_POST['username_user']) or empty($_POST['password_user']) ) and 
( empty($_COOKIE['interface_creator_username']) or empty($_COOKIE['interface_creator_md5_password']) )
   ) 
{
	$login_message = $login_messages_ar['username_password_are_required'];
	include 'login_form.php';
	echo '</body></html>';
	die();
}

// if values to check, which values
if (!empty($_COOKIE['interface_creator_username']) and !empty($_COOKIE['interface_creator_md5_password'])){
	$_SESSION['logged_user_infos_ar'] = get_user_infos_ar_from_username_password($_COOKIE['interface_creator_username'], $_COOKIE['interface_creator_md5_password'], 'non-md5');
}
else{
	$_SESSION['logged_user_infos_ar'] = get_user_infos_ar_from_username_password($_POST['username_user'], $_POST['password_user'], 'md5');
}

// check the values
if ( !empty($_SESSION['logged_user_infos_ar']))
{
	if ($function == 'regular') // regular check
	{	
		if (isset($_POST['remember_me'])){
			setcookie( 'interface_creator_username', $_SESSION['logged_user_infos_ar']['username_user'], time()+1000000); //~6d
			setcookie('interface_creator_md5_password', $_SESSION['logged_user_infos_ar']['password_user'], time()+1000000); //~6d
		}
		$go_to = substr($go_to, 1, -1); // remove the brackets
		header ('Location: '.$go_to);
		die();
	}
	elseif ($function == 'admin') // admin check
	{
		if ( $_SESSION['logged_user_infos_ar']['user_type_user'] === $users_table_user_type_administrator_value){
			if (isset($_POST['remember_me'])){
				setcookie( 'interface_creator_username', $_SESSION['logged_user_infos_ar']['username_user'], time()+1000000); //~6d
				setcookie('interface_creator_md5_password', $_SESSION['logged_user_infos_ar']['password_user'], time()+1000000); //~6d
			}
			$go_to = substr($go_to, 1, -1); // remove the brackets
			header ('Location: '.$go_to);
			die();
		}
		else{
			$login_message = $login_messages_ar['incorrect_admin_login'];
			include 'login_form.php';
			echo '</body></html>';
			die();
		}
	}
	else // anything else
	{
		$login_message = $login_messages_ar['username_password_are_required'];
		include 'login_form.php';
		echo '</body></html>';
		die();
	}
}
else
{
	if (isset($_POST['login_submit']))
	{
		$login_message = ($function == 'regular') ? $login_messages_ar['username_password_are_required'] : $login_messages_ar['incorrect_admin_login'];
	}
	else
	{
		$login_message = ($function == 'regular') ? '' : $login_messages_ar['incorrect_admin_login'];
	}
	include 'login_form.php';
	echo '</body></html>';
	die();
}
////// end for login //////
?>