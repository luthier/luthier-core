<?php defined('SYSPATH') or die('No direct script access.');

/*
 * The following classes need to exist so that class_exists()
 * returns true in certain cases. If they already exist, then
 * those classes will suffice.
 */
if ( ! class_exists('Controller_Luthier_Home'))
{
	class Controller_Luthier_Home { }
}

if ( ! class_exists('Controller_Luthier_Blog_Home'))
{
	class Controller_Luthier_Blog_Home { }
}

if ( ! class_exists('Controller_Luthier_Blog_Articles'))
{
	class Controller_Luthier_Blog_Articles { }
}

if ( ! class_exists('Controller_Luthier_Users_Home'))
{
	class Controller_Luthier_Users_Home { }
}

if ( ! class_exists('Controller_Luthier_Users_Admins'))
{
	class Controller_Luthier_Users_Admins { }
}

/**
 * PHPUnit tests for the Luthier routes
 *
 * @group       luthier
 * @group       luthier.routes
 *
 * @package     Luthier
 * @category    Tests
 * @author      Kyle Treubig
 * @copyright   (C) 2011 Kyle Treubig
 * @license     MIT
 */
class Luthier_RouteTest extends Unittest_TestCase {

	/**
	 * Include route definitions and setup namespace
	 */
	public function setUp()
	{
		parent::setUp();

		require_once(MODPATH.'/luthier-core/init.php');
	}

	/**
	 * Provider for test_media_route_reverse_routing
	 *
	 * @return array
	 */
	public function provider_media_route_reverse_routing()
	{
		return array(
			array(Luthier::ROUTE_NAMESPACE.'/media/luthier/css/theme.css', 'luthier/css/theme.css'),
			array(Luthier::ROUTE_NAMESPACE.'/media/luthier/js/action.js',  'luthier/js/action.js'),
			array(Luthier::ROUTE_NAMESPACE.'/media/luthier/img/photo.jpg', 'luthier/img/photo.jpg'),
		);
	}

	/**
	 * Test the URLs returned by the media route via reverse routing
	 *
	 * @dataProvider provider_media_route_reverse_routing
	 */
	public function test_media_route_reverse_routing($expected, $file)
	{
		$route = Route::get('luthier/media');
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
			array(Luthier::ROUTE_NAMESPACE.'/media/luthier/css/theme.css', TRUE, array('directory'=>'luthier', 'controller'=>'media', 'action'=>'file', 'file'=>'luthier/css/theme.css')),
			array(Luthier::ROUTE_NAMESPACE.'/media/luthier/img/photo.jpg', TRUE, array('directory'=>'luthier', 'controller'=>'media', 'action'=>'file', 'file'=>'luthier/img/photo.jpg')),
			array(Luthier::ROUTE_NAMESPACE.'/media/luthier/js/action.js',  TRUE, array('directory'=>'luthier', 'controller'=>'media', 'action'=>'file', 'file'=>'luthier/js/action.jpg')),
			array(Luthier::ROUTE_NAMESPACE.'/notmedia', FALSE, array()),
		);
	}

	/**
	 * Test the URLs matched by the media route
	 *
	 * @dataProvider provider_media_route_matches
	 */
	public function test_media_route_matches($uri, $match, $params)
	{
		$route = Route::get('luthier/media');
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
			array(Luthier::ROUTE_NAMESPACE.'/login',  array('action'=>'login')),
			array(Luthier::ROUTE_NAMESPACE.'/logout', array('action'=>'logout')),
			array(Luthier::ROUTE_NAMESPACE.'/login',  array()),
		);
	}

	/**
	 * Test the URLs returned by the auth route via reverse routing
	 *
	 * @dataProvider provider_auth_route_reverse_routing
	 */
	public function test_auth_route_reverse_routing($expected, $params)
	{
		$route = Route::get('luthier/auth');
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
			array(Luthier::ROUTE_NAMESPACE.'/login',  TRUE, array('directory'=>'luthier', 'controller'=>'auth', 'action'=>'login')),
			array(Luthier::ROUTE_NAMESPACE.'/logout', TRUE, array('directory'=>'luthier', 'controller'=>'auth', 'action'=>'logout')),
			array(Luthier::ROUTE_NAMESPACE.'/notauth', FALSE, array()),
		);
	}

	/**
	 * Test the URLs matched by the auth route
	 *
	 * @dataProvider provider_auth_route_matches
	 */
	public function test_auth_route_matches($uri, $match, $params)
	{
		$route = Route::get('luthier/auth');
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
			array(Luthier::ROUTE_NAMESPACE, array()),
			array(Luthier::ROUTE_NAMESPACE.'/blog', array('directory'=>'blog')),
			array(Luthier::ROUTE_NAMESPACE.'/blog/articles', array('directory'=>'blog', 'controller'=>'articles')),
			array(Luthier::ROUTE_NAMESPACE.'/blog/articles/new', array('directory'=>'blog', 'controller'=>'articles', 'action'=>'new')),
			array(Luthier::ROUTE_NAMESPACE.'/users', array('controller'=>'users')),
			array(Luthier::ROUTE_NAMESPACE.'/users/new', array('controller'=>'users', 'action'=>'new')),
			array(Luthier::ROUTE_NAMESPACE.'/users/new', array('directory'=>'users', 'controller'=>'new')),
		);
	}

	/**
	 * Test the URLs returned by the primary route via reverse routing
	 *
	 * @dataProvider provider_primary_route_reverse_routing
	 */
	public function test_primary_route_reverse_routing($expected, $params)
	{
		$route = Route::get('luthier');
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
			array(Luthier::ROUTE_NAMESPACE,                      TRUE, array('directory'=>'luthier',      'controller'=>'home',     'action'=>'index')),
			array(Luthier::ROUTE_NAMESPACE.'/blog',              TRUE, array('directory'=>'luthier/blog', 'controller'=>'home',     'action'=>'index')),
			array(Luthier::ROUTE_NAMESPACE.'/blog/articles',     TRUE, array('directory'=>'luthier/blog', 'controller'=>'articles', 'action'=>'index')),
			array(Luthier::ROUTE_NAMESPACE.'/blog/articles/new', TRUE, array('directory'=>'luthier/blog', 'controller'=>'articles', 'action'=>'new')),
			array(Luthier::ROUTE_NAMESPACE.'/users',             TRUE, array('directory'=>'luthier/users', 'controller'=>'home',    'action'=>'index')),
			array(Luthier::ROUTE_NAMESPACE.'/users/new',         TRUE, array('directory'=>'luthier/users', 'controller'=>'home',    'action'=>'new')),
			array(Luthier::ROUTE_NAMESPACE.'/users/admins',      TRUE, array('directory'=>'luthier/users', 'controller'=>'admins',  'action'=>'index')),
			array(Luthier::ROUTE_NAMESPACE.'/users/home/admins', TRUE, array('directory'=>'luthier/users', 'controller'=>'home',    'action'=>'admins')),
		);
	}

	/**
	 * Test the URLs matched by the primary route
	 *
	 * @dataProvider provider_primary_route_matches
	 */
	public function test_primary_route_matches($uri, $match, $params)
	{
		$route = Route::get('luthier');
		$matches = $route->matches($uri);

		$this->assertEquals($match, is_array($matches));

		if ($match)
		{
			$this->assertEquals($params['directory'],  trim($matches['directory'], "/"));
			$this->assertEquals($params['controller'], $matches['controller']);
			$this->assertEquals($params['action'],     $matches['action']);
		}
	}

}
