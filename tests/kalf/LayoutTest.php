<?php defined('SYSPATH') or die('No direct script access.');

/**
 * PHPUnit tests for the layout view
 *
 * @group       kalf
 * @group       kalf.layout
 *
 * @package     Kalf
 * @category    Tests
 * @author      Kyle Treubig
 * @copyright   (C) 2011 Kyle Treubig
 * @license     MIT
 */
class Kalf_LayoutTest extends Unittest_TestCase {

	/**
	 * Create an instance of the core layout view
	 */
	public function setUp()
	{
		parent::setUp();

		// Setup a dummy request
		Request::$current = new Request('');

		// Create the view
		$this->view = $this->getMockForAbstractClass('Kalf_Layout_Core', array('kalf/layout'));

		// Use test data for controller detection
		$this->view->_detect_controllers(array(MODPATH.'kalf-core/tests/data/'));
	}

	/**
	 * Provider for test_directory_extracted_from_request_url
	 *
	 * @return array
	 */
	public function provider_directory_extracted_from_request_url()
	{
		return array(
			array(Kalf::ROUTE_NAMESPACE, ''),
			array(Kalf::ROUTE_NAMESPACE.'/blog', 'blog'),
			array(Kalf::ROUTE_NAMESPACE.'/users', 'users'),
		);
	}

	/**
	 * Test the current directory is extracted from the request URL
	 *
	 * @dataProvider provider_directory_extracted_from_request_url
	 */
	public function test_directory_extracted_from_request_url($directory, $expected)
	{
		Request::current()->directory($directory);
		$view = $this->getMockForAbstractClass('Kalf_Layout_Core', array('kalf/layout'));
		$this->assertAttributeEquals($expected, '_directory', $view);
	}

	/**
	 * Test the stylesheets method of the layout view
	 */
	public function test_stylesheets()
	{
		// Add test stylesheet to view
		$this->view->set('_stylesheets', array('test/file.css' => 'testmedia'));

		// Verify stylesheets
		$stylesheets = $this->view->stylesheets();
		$this->assertEquals(1, count($stylesheets));
		$this->assertEquals(Kalf::ROUTE_NAMESPACE.'/media/test/file.css', $stylesheets[0]['url']);
		$this->assertEquals('testmedia', $stylesheets[0]['media']);
	}

	/**
	 * Test the scripts method of the layout view
	 */
	public function test_scripts()
	{
		// Add test script to view
		$this->view->set('_scripts', array('test/file.js'));

		// Verify scripts
		$scripts = $this->view->scripts();
		$this->assertEquals(1, count($scripts));
		$this->assertEquals(Kalf::ROUTE_NAMESPACE.'/media/test/file.js', $scripts[0]['url']);
	}

	/**
	 * Test that the main navigation contains a home link
	 */
	public function test_main_navigation_contains_home_link()
	{
		$items = $this->view->main_navigation();
		$this->assertEquals(__('Home'), $items[0]['text']);
	}

	/**
	 * Test that the main navigation contains detected directories
	 */
	public function test_main_navigation_contains_detected_directories()
	{
		$items = $this->view->main_navigation();
		$this->assertEquals(__('Blog'), $items[1]['text']);
		$this->assertEquals(__('Users'), $items[2]['text']);
	}

	/**
	 * Test that the main navigation contains a login link
	 */
	public function test_main_navigation_contains_login_link()
	{
		$this->view->set('logged_in', FALSE);
		$items = $this->view->main_navigation();
		$end = count($items) - 1;
		$this->assertEquals(__('Login'), $items[$end]['text']);
	}

	/**
	 * Test that the main navigation contains a logout link
	 */
	public function test_main_navigation_contains_logout_link()
	{
		$this->view->set('logged_in', TRUE);
		$items = $this->view->main_navigation();
		$end = count($items) - 1;
		$this->assertEquals(__('Logout'), $items[$end]['text']);
	}

	/**
	 * Test that the main navigation links to the first controller in a directory
	 */
	public function test_main_navigation_links_first_controller()
	{
		$items = $this->view->main_navigation();
		$this->assertEquals(Kalf::ROUTE_NAMESPACE.'/blog/articles', $items[1]['url']);
	}

	/**
	 * Test that the main navigation links to a home controller in a directory
	 */
	public function test_main_navigation_links_home_controller()
	{
		$items = $this->view->main_navigation();
		$this->assertEquals(Kalf::ROUTE_NAMESPACE.'/users', $items[2]['url']);
	}

	/**
	 * Provider for test_main_navigation_shows_current_directory_active
	 *
	 * @return array
	 */
	public function provider_main_navigation_shows_current_directory_active()
	{
		return array(
			array('', 0),
			array('blog', 1),
			array('users', 2),
		);
	}

	/**
	 * Test that the current directory is shown as active in the main navigation
	 *
	 * @dataProvider provider_main_navigation_shows_current_directory_active
	 */
	public function test_main_navigation_shows_current_directory_active($current, $index)
	{
		$this->view->set('_directory', $current);
		$items = $this->view->main_navigation();
		$this->assertTrue($items[$index]['active']);
	}

	/**
	 * Provider for test_section_navigation_shown_for_multiple_controllers
	 *
	 * @return array
	 */
	public function provider_section_navigation_shown_for_multiple_controllers()
	{
		return array(
			array('', FALSE),
			array('blog', TRUE),
			array('users', TRUE),
		);
	}

	/**
	 * Test that the section navigation is shown when directory has multiple controllers
	 *
	 * @dataProvider provider_section_navigation_shown_for_multiple_controllers
	 */
	public function test_section_navigation_shown_for_multiple_controllers($current, $has_section_nav)
	{
		$this->view->set('_directory', $current);
		$this->assertEquals($has_section_nav, $this->view->has_section_navigation());
	}

	/**
	 * Provider for test_directory_used_as_section_heading
	 *
	 * @return array
	 */
	public function provider_directory_used_as_section_heading()
	{
		return array(
			array('blog', 'Blog'),
			array('users', 'Users'),
		);
	}

	/**
	 * Test that directory used as heading in section navigation
	 *
	 * @dataProvider provider_directory_used_as_section_heading
	 */
	public function test_directory_used_as_section_heading($current, $header)
	{
		$this->view->set('_directory', $current);
		$section = $this->view->section_navigation();
		$this->assertEquals($header, $section[$current]['header']);
	}

	/**
	 * Provider for test_directory_controllers_in_section_navigation
	 *
	 * @return array
	 */
	public function provider_directory_controllers_in_section_navigation()
	{
		return array(
			array('blog', 2, 0, 'Articles', Kalf::ROUTE_NAMESPACE.'/blog/articles'),
			array('blog', 2, 1, 'Comments', Kalf::ROUTE_NAMESPACE.'/blog/comments'),
			array('users', 2, 0, 'Admins', Kalf::ROUTE_NAMESPACE.'/users/admins'),
			array('users', 2, 1, 'Groups', Kalf::ROUTE_NAMESPACE.'/users/groups'),
		);
	}

	/**
	 * Test that controllers within a directory are contained in the section navigation
	 *
	 * @dataProvider provider_directory_controllers_in_section_navigation
	 */
	public function test_directory_controllers_in_section_navigation($current, $num, $index, $text, $url)
	{
		$this->view->set('_directory', $current);
		$section = $this->view->section_navigation();
		$items = $section[$current]['links'];
		$this->assertEquals($num, count($items));
		$this->assertEquals($text, $items[$index]['text']);
		$this->assertEquals($url, $items[$index]['url']);
	}

	/**
	 * Test adding item to section navigation
	 */
	public function test_adding_item_to_section_navigation()
	{
		$this->view->set('_directory', 'users');
		$this->view->_add_section_nav('Moderators', 'admin/users/mods');
		$section = $this->view->section_navigation();
		$items = $section['users']['links'];
		$this->assertEquals(3, count($items));
		$this->assertEquals('Moderators', $items[2]['text']);
		$this->assertEquals('admin/users/mods', $items[2]['url']);
	}

	/**
	 * Test adding header to section navigation
	 */
	public function test_adding_header_to_section_navigation()
	{
		$this->view->set('_directory', 'users');
		$this->view->_add_section_nav('Create New User', 'admin/users/new', 'Quick Links');
		$section = $this->view->section_navigation();
		$this->assertEquals(2, count($section));
		$this->assertArrayHasKey('Quick Links', $section);
		$this->assertEquals('Quick Links', $section['Quick Links']['header']);
	}

	/**
	 * Test adding items to additional header in section navigation
	 */
	public function test_adding_items_to_additional_header_in_section_navigation()
	{
		$this->view->set('_directory', 'users');
		$this->view->_add_section_nav('Create New User', 'admin/users/new', 'Quick Links');
		$this->view->_add_section_nav('Edit My Info', 'admin/users/profile', 'Quick Links');
		$section = $this->view->section_navigation();
		$items = $section['Quick Links']['links'];
		$this->assertEquals(2, count($items));
		$this->assertEquals('Create New User', $items[0]['text']);
		$this->assertEquals('admin/users/new', $items[0]['url']);
		$this->assertEquals('Edit My Info', $items[1]['text']);
		$this->assertEquals('admin/users/profile', $items[1]['url']);
	}

	/**
	 * Test that flash messages from the session
	 */
	public function test_flash_messages_from_session()
	{
		Kalf::message('test_info_msg', Kalf::INFO);
		Kalf::message('test_err_msg', Kalf::ERROR);
		$notices = $this->view->user_messages();

		$this->assertEquals(2, count($notices));
		$this->assertEquals(Kalf::INFO, $notices[0]['type']);
		$this->assertEquals('test_info_msg', $notices[0]['message']);
		$this->assertEquals(Kalf::ERROR, $notices[1]['type']);
		$this->assertEquals('test_err_msg', $notices[1]['message']);
	}

	/**
	 * Test that sub-layout is added as a partial
	 *
	 * This may need to be revisited...
	 */
	public function test_sub_layout_as_partial()
	{
		$partials = array();
		$this->view->bind('_partials', $partials);
		$this->view->render();
		$this->assertEquals(1, count($partials));
		$this->assertArrayHasKey('layout', $partials);
	}

}	// End of Kalf_View_LayoutTest
