<?php defined('SYSPATH') or die('No direct script access.');

/*
 * The following classes need to exist so that class_exists()
 * returns true in certain cases. If they already exist, then
 * those classes will suffice.
 */
if ( ! class_exists('Controller_Kalf_Home'))
{
	class Controller_Kalf_Home { }
}

if ( ! class_exists('Controller_Kalf_Blog_Home'))
{
	class Controller_Kalf_Blog_Home { }
}

if ( ! class_exists('Controller_Kalf_Blog_Articles'))
{
	class Controller_Kalf_Blog_Articles { }
}

if ( ! class_exists('Controller_Kalf_Users_Home'))
{
	class Controller_Kalf_Users_Home { }
}

if ( ! class_exists('Controller_Kalf_Users_Admins'))
{
	class Controller_Kalf_Users_Admins { }
}

/**
 * PHPUnit tests for the Kalf routes
 *
 * @group       kalf
 * @group       kalf.routes
 *
 * @package     Kalf
 * @category    Tests
 * @author      Kyle Treubig
 * @copyright   (C) 2011 Kyle Treubig
 * @license     MIT
 */
class Kalf_RouteTest extends Unittest_TestCase {

	/**
	 * Include route definitions and setup namespace
	 */
	public function setUp()
	{
		parent::setUp();

		require_once(MODPATH.'/kalf-core/init.php');
	}

	/**
	 * Provider for test_media_route_reverse_routing
	 *
	 * @return array
	 */
	public function provider_media_route_reverse_routing()
	{
		return array(
			array(Kalf::ROUTE_NAMESPACE.'/media/kalf/css/theme.css', 'kalf/css/theme.css'),
			array(Kalf::ROUTE_NAMESPACE.'/media/kalf/js/action.js',  'kalf/js/action.js'),
			array(Kalf::ROUTE_NAMESPACE.'/media/kalf/img/photo.jpg', 'kalf/img/photo.jpg'),
		);
	}

	/**
	 * Test the URLs returned by the media route via reverse routing
	 *
	 * @dataProvider provider_media_route_reverse_routing
	 */
	public function test_media_route_reverse_routing($expected, $file)
	{
		$route = Route::get('kalf/media');
		$url = $route->uri(array('file' => $file));
		$this->assertEquals($expected, $url);
	}

	/**
	 * Provider for test_media_route_matches
	 *
	 * @return array
	 */
	public function provider_media_route_matches()
	{
		return array(
			array(Kalf::ROUTE_NAMESPACE.'/media/kalf/css/theme.css', TRUE, array('directory'=>'kalf', 'controller'=>'media', 'action'=>'file', 'file'=>'kalf/css/theme.css')),
			array(Kalf::ROUTE_NAMESPACE.'/media/kalf/img/photo.jpg', TRUE, array('directory'=>'kalf', 'controller'=>'media', 'action'=>'file', 'file'=>'kalf/img/photo.jpg')),
			array(Kalf::ROUTE_NAMESPACE.'/media/kalf/js/action.js',  TRUE, array('directory'=>'kalf', 'controller'=>'media', 'action'=>'file', 'file'=>'kalf/js/action.jpg')),
			array(Kalf::ROUTE_NAMESPACE.'/notmedia', FALSE, array()),
		);
	}

	/**
	 * Test the URLs matched by the media route
	 *
	 * @dataProvider provider_media_route_matches
	 */
	public function test_media_route_matches($uri, $match, $params)
	{
		$route = Route::get('kalf/media');
		$matches = $route->matches($uri);

		$this->assertEquals($match, is_array($matches));

		if ($match)
		{
			$this->assertEquals($params['directory'],  $matches['directory']);
			$this->assertEquals($params['controller'], $matches['controller']);
			$this->assertEquals($params['action'],     $matches['action']);
		}
	}

	/**
	 * Provider for test_auth_route_reverse_routing
	 *
	 * @return array
	 */
	public function provider_auth_route_reverse_routing()
	{
		return array(
			array(Kalf::ROUTE_NAMESPACE.'/login',  array('action'=>'login')),
			array(Kalf::ROUTE_NAMESPACE.'/logout', array('action'=>'logout')),
			array(Kalf::ROUTE_NAMESPACE.'/login',  array()),
		);
	}

	/**
	 * Test the URLs returned by the auth route via reverse routing
	 *
	 * @dataProvider provider_auth_route_reverse_routing
	 */
	public function test_auth_route_reverse_routing($expected, $params)
	{
		$route = Route::get('kalf/auth');
		$url = $route->uri($params);
		$this->assertEquals($expected, $url);
	}

	/**
	 * Provider for test_auth_route_matches
	 *
	 * @return array
	 */
	public function provider_auth_route_matches()
	{
		return array(
			array(Kalf::ROUTE_NAMESPACE.'/login',  TRUE, array('directory'=>'kalf', 'controller'=>'auth', 'action'=>'login')),
			array(Kalf::ROUTE_NAMESPACE.'/logout', TRUE, array('directory'=>'kalf', 'controller'=>'auth', 'action'=>'logout')),
			array(Kalf::ROUTE_NAMESPACE.'/notauth', FALSE, array()),
		);
	}

	/**
	 * Test the URLs matched by the auth route
	 *
	 * @dataProvider provider_auth_route_matches
	 */
	public function test_auth_route_matches($uri, $match, $params)
	{
		$route = Route::get('kalf/auth');
		$matches = $route->matches($uri);

		$this->assertEquals($match, is_array($matches));

		if ($match)
		{
			$this->assertEquals($params['directory'],  $matches['directory']);
			$this->assertEquals($params['controller'], $matches['controller']);
			$this->assertEquals($params['action'],     $matches['action']);
		}
	}

	/**
	 * Provider for test_primary_route_reverse_routing
	 *
	 * @return array
	 */
	public function provider_primary_route_reverse_routing()
	{
		return array(
			array(Kalf::ROUTE_NAMESPACE, array()),
			array(Kalf::ROUTE_NAMESPACE.'/blog', array('directory'=>'blog')),
			array(Kalf::ROUTE_NAMESPACE.'/blog/articles', array('directory'=>'blog', 'controller'=>'articles')),
			array(Kalf::ROUTE_NAMESPACE.'/blog/articles/new', array('directory'=>'blog', 'controller'=>'articles', 'action'=>'new')),
			array(Kalf::ROUTE_NAMESPACE.'/users', array('controller'=>'users')),
			array(Kalf::ROUTE_NAMESPACE.'/users/new', array('controller'=>'users', 'action'=>'new')),
			array(Kalf::ROUTE_NAMESPACE.'/users/new', array('directory'=>'users', 'controller'=>'new')),
		);
	}

	/**
	 * Test the URLs returned by the primary route via reverse routing
	 *
	 * @dataProvider provider_primary_route_reverse_routing
	 */
	public function test_primary_route_reverse_routing($expected, $params)
	{
		$route = Route::get('kalf');
		$url = $route->uri($params);
		$this->assertEquals($expected, $url);
	}

	/**
	 * Provider for test_primary_route_matches
	 *
	 * @return array
	 */
	public function provider_primary_route_matches()
	{
		return array(
			array(Kalf::ROUTE_NAMESPACE,                      TRUE, array('directory'=>'kalf',      'controller'=>'home',     'action'=>'index')),
			array(Kalf::ROUTE_NAMESPACE.'/blog',              TRUE, array('directory'=>'kalf/blog', 'controller'=>'home',     'action'=>'index')),
			array(Kalf::ROUTE_NAMESPACE.'/blog/articles',     TRUE, array('directory'=>'kalf/blog', 'controller'=>'articles', 'action'=>'index')),
			array(Kalf::ROUTE_NAMESPACE.'/blog/articles/new', TRUE, array('directory'=>'kalf/blog', 'controller'=>'articles', 'action'=>'new')),
			array(Kalf::ROUTE_NAMESPACE.'/users',             TRUE, array('directory'=>'kalf/users', 'controller'=>'home',    'action'=>'index')),
			array(Kalf::ROUTE_NAMESPACE.'/users/new',         TRUE, array('directory'=>'kalf/users', 'controller'=>'home',    'action'=>'new')),
			array(Kalf::ROUTE_NAMESPACE.'/users/admins',      TRUE, array('directory'=>'kalf/users', 'controller'=>'admins',  'action'=>'index')),
			array(Kalf::ROUTE_NAMESPACE.'/users/home/admins', TRUE, array('directory'=>'kalf/users', 'controller'=>'home',    'action'=>'admins')),
		);
	}

	/**
	 * Test the URLs matched by the primary route
	 *
	 * @dataProvider provider_primary_route_matches
	 */
	public function test_primary_route_matches($uri, $match, $params)
	{
		$route = Route::get('kalf');
		$matches = $route->matches($uri);

		$this->assertEquals($match, is_array($matches));

		if ($match)
		{
			$this->assertEquals($params['directory'],  trim($matches['directory'], "/"));
			$this->assertEquals($params['controller'], $matches['controller']);
			$this->assertEquals($params['action'],     $matches['action']);
		}
	}

}	// End of Kalf_RouteTest
