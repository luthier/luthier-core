<?php defined('SYSPATH') OR die('No direct script access.');

return array(
	'auth' => array(
		'instance' => 'auth',
		'log'      => Log::DEBUG,
	),

	'messages' => array(
		'session' => array(
			'type' => 'native',
			'key'  => 'admin_flash_msg',
		),
	),

	'userguide' => array(
		/*
		'blog' => array(
			'enabled'     => TRUE,
			'name'        => 'Blog',
			'description' => 'Blogging extension for article and comment management',
			'file'        => 'blog',
		),
		 */
	),

);

