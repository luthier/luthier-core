<?php // @codeCoverageIgnoreStart
// Ignored because placeholder class
defined('SYSPATH') or die('No direct script access.');

/**
 * Default admin home controller
 *
 * @package     Kalf
 * @category    Controller
 * @author      Kyle Treubig
 * @copyright   (C) 2011 Kyle Treubig
 * @license     MIT
 */
class Controller_Kalf_Home extends Controller {

	public function action_index()
	{
		$this->response->body(new View_Kalf_Home);
	}

}	// @codeCoverageIgnoreEnd
