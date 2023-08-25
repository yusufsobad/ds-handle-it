<?php

(!defined('DEFPATH'))?exit:'';

// registry page
$args = array();
$args['login'] = array(
	'home'	=> false,
	'view'	=> 'Login.login',
	'page'	=> 'login_system'
);

$args['dashboard'] = array(
	'home'	=> true,
	'view'	=> 'dashboard.dashboard',
	'page'	=> 'dashboard_system'
);

reg_hook('reg_page',$args);