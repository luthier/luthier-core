<?php defined('SYSPATH') or die('No direct script access.');

/**
 * PHPUnit tests for the media controller
 *
 * @group       luthier
 * @group       luthier.media
 *
 * @package     Luthier
 * @category    Tests
 * @author      Kyle Treubig
 * @copyright   (C) 2011 Kyle Treubig
 * @license     MIT
 */
class Luthier_MediaTest extends Unittest_TestCase {

	public function test_media_controller_serves_static_file()
	{
		// Setup request
		$url = Route::get('luthier/media')->uri(array('file' => 'luthier/test/static.html'));
		$request = new Request($url);

		// Don't proceed if request not setup correctly
		$this->assertEquals('luthier/test/static.html', $request->param('file'));

		// Create response and controller
		$response = new Response;
		$controller = new Controller_Luthier_Media($request, $response);

		// Execute
		$controller->action_file();

		// Verify static file served
		$this->assertEquals(200, $response->status());
		$this->assertEquals("UNITTEST STATIC FILE\n", $response->body());
	}

	public function test_media_controller_404_not_found()
	{
		// Setup request
		$url = Route::get('luthier/media')->uri(array('file' => 'luthier/test/dne.html'));
		$request = new Request($url);

		// Don't proceed if request not setup correctly
		$this->assertEquals('luthier/test/dne.html', $request->param('file'));

		// Create response and controller
		$response = new Response;
		$controller = new Controller_Luthier_Media($request, $response);

		// Execute
		$controller->action_file();

		// Verify 404 status
		$this->assertEquals(404, $response->status());
	}

}
