<?php defined('SYSPATH') or die('No direct script access.');

/**
 * PHPUnit tests for the media controller
 *
 * @group       kalf
 * @group       kalf.media
 *
 * @package     Kalf
 * @category    Tests
 * @author      Kyle Treubig
 * @copyright   (C) 2011 Kyle Treubig
 * @license     MIT
 */
class Kalf_MediaTest extends Unittest_TestCase {

	public function test_media_controller_serves_static_file()
	{
		// Setup request
		$url = Route::get('kalf/media')->uri(array('file' => 'kalf/test/static.html'));
		$request = new Request($url);

		// Don't proceed if request not setup correctly
		$this->assertEquals('kalf/test/static.html', $request->param('file'));

		// Create response and controller
		$response = new Response();
		$controller = new Controller_Kalf_Media($request, $response);

		// Execute
		$controller->action_file();

		// Verify static file served
		$this->assertEquals(200, $response->status());
		$this->assertEquals("UNITTEST STATIC FILE\n", $response->body());
	}

	public function test_media_controller_404_not_found()
	{
		// Setup request
		$url = Route::get('kalf/media')->uri(array('file' => 'kalf/test/dne.html'));
		$request = new Request($url);

		// Don't proceed if request not setup correctly
		$this->assertEquals('kalf/test/dne.html', $request->param('file'));

		// Create response and controller
		$response = new Response();
		$controller = new Controller_Kalf_Media($request, $response);

		// Execute
		$controller->action_file();

		// Verify 404 status
		$this->assertEquals(404, $response->status());
	}

}
