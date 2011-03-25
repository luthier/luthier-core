<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Kalf authentication controller
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
	 */
	public function action_login()
	{
		Kohana::$log->add(Log::DEBUG, 'Executing Controller_Kalf_Auth::action_login');

		$config = Kohana::config('kalf.auth');
		$a2 = A2::instance($config['a2']);

		// If user is already logged in, don't do anything
		if ($a2->logged_in())
		{
			Kohana::$log->add($config['log'], 'Attempt to login made by logged-in user');
			Kalf::message(Kalf::ERROR, Kohana::message('a2', 'login.already'));
			$this->request->redirect( Route::get('kalf')->uri() );
		}

		$view = Kostache::factory('kalf/auth/login')
			->bind('post', $post)
			->bind('errors', $errors);

		if ($_POST)
		{
			$post = Validation::factory($_POST)
				->rule('username', 'not_empty')
				->rule('password', 'not_empty');

			if ($post->check())
			{
				if ($a2->a1->login($post['username'], $post['password'],
					! empty($post['remember'])))
				{
					Kohana::$log->add($config['log'], 'Successful login made with username :name',
						array(':name' => $post['username']));
					Kalf::message(Kohana::message('a2', 'login.success'), Kalf::INFO);
					$this->request->redirect( Route::get('kalf')->uri() );
				}
				else
				{
					Kohana::$log->add($config['log'], 'Unsuccessful login attempt made with username :name',
						array(':name' => $post['username']));
					Kalf::message(Kohana::message('a2', 'login.failure'), Kalf::ERROR);
				}
			}

			$errors = $post->errors();
		}

		$this->response->body($view->render());
	}

	/**
	 * User logout
	 */
	public function action_logout()
	{
		Kohana::$log->add(Log::DEBUG, 'Executing Controller_Kalf_Auth::action_logout');

		$config = Kohana::config('kalf.auth');
		$a2 = A2::instance($config['a2']);
		$a2->a1->logout();

		Kohana::$log->add($config['log'], 'Successful logout made by user');
		Kalf::message(Kohana::message('a2', 'logout.success'), Kalf::INFO);
		$this->request->redirect( Route::get('kalf')->uri() );
	}

}	// End of Controller_Kalf_Auth
