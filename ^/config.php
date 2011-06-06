<?php
/*****************************************************************************
 * Project     : Graphite
 *                Simple MVC web-application framework
 * Created By  : LoneFry
 *                dev@lonefry.com
 * License     : CC BY-NC-SA
 *                Creative Commons Attribution-NonCommercial-ShareAlike
 *                http://creativecommons.org/licenses/by-nc-sa/3.0/
 *
 * File        : /config.php
 *                website core configuration file
 ****************************************************************************/

//CORE should be defined as evidence we are not requested directly
if(!defined('CORE')){header("Location: /");exit;}

if(!isset(G::$G))G::$G=array('startTime'=>NOW);

/*****************************************************************************
 * General settings
 ****************************************************************************/
G::$G['timezone']='America/New_York';
G::$G['siteEmail']='apache@localhost';
G::$G['MODE']='prd';
//includePath relative to SITE
//each class will append it's own sub directory to each path
G::$G['includePath']=CORE.'Tree;'.CORE;
/*****************************************************************************
 * /General settings
 ****************************************************************************/


/*****************************************************************************
 * Database settings
 ****************************************************************************/
G::$G['db']=array(
	'host'=>'',
	'user'=>'',
	'pass'=>'',
	'name'=>'',
	'tabl'=>'',
	'log'=>false
);
G::$G['db']['ro']=array(
	'host'=>'',
	'user'=>'',
	'pass'=>'',
	'name'=>''
);
/*****************************************************************************
 * /Database settings
 ****************************************************************************/


/*****************************************************************************
 * Settings for the Controller 
 ****************************************************************************/
//Paths
G::$G['CON']['URL']='/';

//Defaults
G::$G['CON']['actor']='Home';
G::$G['CON']['actor404']='404';

//Passed Values
    if(isset($_GET['actor']))        G::$G['CON']['actor']=$_GET['actor'];
    if(isset($_GET['action']))       G::$G['CON']['action']=$_GET['action'];
    if(isset($_GET['params']))       G::$G['CON']['params']=$_GET['params'];
    if(isset($_SERVER['PATH_INFO'])) G::$G['CON']['path']=$_SERVER['PATH_INFO'];
elseif(isset($_POST['path']))        G::$G['CON']['path']=$_POST['path'];
elseif(isset($_GET['path']))         G::$G['CON']['path']=$_GET['path'];
/*****************************************************************************
 * /Settings for the Controller 
 ****************************************************************************/


/*****************************************************************************
 * Settings for the View 
 ****************************************************************************/
//Paths
G::$G['VIEW']['header']='header.php';
G::$G['VIEW']['footer']='footer.php';
G::$G['VIEW']['template']='404.php';

//display vars
G::$G['VIEW']['_siteName']='Graphite Site';
G::$G['VIEW']['_siteURL']='http://'.$_SERVER['SERVER_NAME'];
G::$G['VIEW']['_loginURL']=G::$G['CON']['URL'].'Account/login';
G::$G['VIEW']['_logoutURL']=G::$G['CON']['URL'].'Account/Logout';
G::$G['VIEW']['_meta']=array(
	"description"=>"Graphite MVC framework",
	"keywords"=>"Graphite,MVC,framework",
	"copyright"=>"Creative Commons (CC BY-NC-SA) LoneFry."
);
G::$G['VIEW']['_script']=array(
	CORE.'/js/sha1.js'//sha1 is used by the login forms to salt passwords
);
G::$G['VIEW']['_link']=array(
	array('rel'=>'shortcut icon','type'=>'image/vnd.microsoft.icon','href'=>'/favicon.ico'),
	array('rel'=>'stylesheet','type'=>'text/css','href'=>CORE.'/css/default.css')
);
//login redirection vars
G::$G['VIEW']['_URI']=isset($_POST['_URI'])?$_POST['_URI']:$_SERVER['REQUEST_URI'];
G::$G['VIEW']['_Lbl']=isset($_POST['_Lbl'])?$_POST['_Lbl']:'to the page you requested';

/*****************************************************************************
 * /Settings for the View 
 ****************************************************************************/


/*****************************************************************************
 * Per-Domain Settings for multi-domain sites
 *  If you are not hosting a site on multiple domains, you can safely
 *  use this file as your only configuration file
 ****************************************************************************/
if(file_exists(dirname(SITE).'/siteConfigs/config.'.$_SERVER['SERVER_NAME'].'.php')){
	include dirname(SITE).'/siteConfigs/config.'.$_SERVER['SERVER_NAME'].'.php';
}elseif(file_exists(SITE.'/config.'.$_SERVER['SERVER_NAME'].'.php')){
	include SITE.'/config.'.$_SERVER['SERVER_NAME'].'.php';
}
/*****************************************************************************
 * /Per-Domain Settings for multi-domain sites
 ****************************************************************************/

