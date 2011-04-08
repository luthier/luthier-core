<?php defined('SYSPATH') or die('No direct script access.');

/**
* Kalf media controller
*
* @package     Kalf
* @category    Controller
* @author      Kyle Treubig
* @copyright   (C) 2011 Kyle Treubig
* @license     MIT
 */
class Controller_Kalf_Media extends Controller {

	/**
	 * Serve a file from the media directory within
	 * the cascading file system
	 */
	public function action_file()
	{
		// Get the file path from the request
		$file = $this->request->param('file');

		// Find the file extension
		$ext = pathinfo($file, PATHINFO_EXTENSION);

		// Remove the extension from the filename
		$file = substr($file, 0, -(strlen($ext) + 1));

		if ($file = Kohana::find_file('media', $file, $ext))
		{
			// Check if the browser sent an "if-none-match: <etag>" header, and tell if the file hasn't changed
			$this->response->check_cache(sha1($this->request->uri()).filemtime($file), $this->request);

			// Send the file content as the response
			$this->response->body(file_get_contents($file));

			// Set the proper headers to allow caching
			$this->response->headers('content-type',  File::mime_by_ext($ext));
			$this->response->headers('last-modified', date('r', filemtime($file)));
		}
		else
		{
			// Return a 404 status
			$this->response->status(404);
		}
	}

}

