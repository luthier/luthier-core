<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Placeholder controller for Kalf authentication.
 * This controller should be replaced by a Kalf extension
 * implementing a specific authentication solution.
 *
 * @package     Kalf
 * @category    Controller
 * @author      Kyle Treubig
 * @copyright   (C) 2011 Kyle Treubig
 * @license     MIT
 */
class Controller_Kalf_Auth extends Controller {

	/**
	 * User login
	 *
	 * [!!] Dummy procedure! Use a specific authentication implementation.
	 */
	public function action_login()
	{
		Kohana::$log->add(Log::DEBUG, 'Executing Controller_Kalf_Auth::action_login');
		$this->response->body(Kostache::factory('kalf/auth/login')->render());
	}

	/**
	 * User logout
	 *
	 * [!!] Dummy procedure! Use a specific authentication implementation.
	 */
	public function action_logout()
	{
		Kohana::$log->add(Log::DEBUG, 'Executing Controller_Kalf_Auth::action_logout');
		$this->request->redirect( Route::get('kalf')->uri() );
	}

}
