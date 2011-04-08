<?php defined('SYSPATH') or die('No direct script access.');

// Luthier auth route
Route::set('luthier/auth', Luthier::ROUTE_NAMESPACE.'/<action>', array(
	'action' => 'login|logout',
	))
	->defaults(array(
		'directory'  => 'luthier',
		'controller' => 'auth',
		'action'     => 'login',
	));

// Luthier media route
Route::set('luthier/media', Luthier::ROUTE_NAMESPACE.'/media/<file>', array(
	'file' => '.+',
	))
	->defaults(array(
		'directory'  => 'luthier',
		'controller' => 'media',
		'action'     => 'file',
	));

// Primary Luthier route (namespace aliasing, home controller forwarding)
Route::set('luthier', function($uri)
	{
		// Default to Luthier home page
		$defaults = array(
			'directory'  => '', // will add 'luthier' later
			'controller' => 'home',
			'action'     => 'index',
		);

		// Compile the URI regex
		$regex = Route::compile(Luthier::ROUTE_NAMESPACE.'(/<directory>(/<controller>(/<action>)))');

		// Check if URI matches the regex
		if ( ! preg_match($regex, $uri, $matches))
			return FALSE;

		// Use defaults for missing parameters
		$params = $matches + $defaults;

		// Extract relevant parameters (ignoring numeric keys, etc.)
		$directory  = $params['directory'];
		$controller = $params['controller'];
		$action     = $params['action'];

		// If controller doesn't exist, forward action to home controller
		$class = 'Controller_Luthier_'.(empty($directory) ? '' : $directory.'_').$controller;
		if ( ! class_exists($class))
		{
			// Use "controller" as the action
			$action = $controller;
			// Use the home controller
			$controller = $defaults['controller'];
		}

		// Return route parameters, adding 'luthier' to directory
		return array(
			'directory'  => 'luthier/'.$directory,
			'controller' => $controller,
			'action'     => $action,
		);
	},
	// For reverse routing
	Luthier::ROUTE_NAMESPACE.'(/<directory>)(/<controller>(/<action>))'
);

