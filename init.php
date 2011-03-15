<?php defined('SYSPATH') or die('No direct script access.');

// Kalf auth route
Route::set('kalf/auth', Kalf::ROUTE_NAMESPACE.'/<action>', array(
	'action' => 'login|logout',
	))
	->defaults(array(
		'directory'  => 'kalf',
		'controller' => 'auth',
		'action'     => 'login',
	));

// Kalf media route
Route::set('kalf/media', Kalf::ROUTE_NAMESPACE.'/media/<file>', array(
	'file' => '.+',
	))
	->defaults(array(
		'directory'  => 'kalf',
		'controller' => 'media',
		'action'     => 'file',
	));

// Primary Kalf route (namespace aliasing, home controller forwarding)
Route::set('kalf', function($uri)
	{
		// Default to Kalf home page
		$defaults = array(
			'directory'  => '', // will add 'kalf' later
			'controller' => 'home',
			'action'     => 'index',
		);

		// Compile the URI regex
		$regex = Route::compile(Kalf::ROUTE_NAMESPACE.'(/<directory>(/<controller>(/<action>)))');

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
		$class = 'Controller_Kalf_'.(empty($directory) ? '' : $directory.'_').$controller;
		if ( ! class_exists($class))
		{
			// Use "controller" as the action
			$action = $controller;
			// Use the home controller
			$controller = $defaults['controller'];
		}

		// Return route parameters, adding 'kalf' to directory
		return array(
			'directory'  => 'kalf/'.$directory,
			'controller' => $controller,
			'action'     => $action,
		);
	},
	// For reverse routing
	Kalf::ROUTE_NAMESPACE.'(/<directory>)(/<controller>(/<action>))'
);

