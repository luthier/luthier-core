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

);

