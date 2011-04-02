<?php // @codeCoverageIgnoreStart
// Ignored because placeholder class and can be replaced
defined('SYSPATH') or die('No direct script access.');

/**
 * Login view
 *
 * @package     Kalf
 * @category    View
 * @author      Kyle Treubig
 * @copyright   (C) 2011 Kyle Treubig
 * @license     MIT
 */
class View_Kalf_Auth_Login extends View_Kalf_Form {

	protected $_sub_layout = "narrow";

	public $submit = 'Login';

	public function fields()
	{
		return array(
			array(
				'field' => 'username',
				'label' => 'Username',
				'input' => Form::input('username', isset($this->post['username']) ? $this->post['username'] : ''),
				'error' => isset($this->errors['username']) ? $this->errors['username'][0] : '',
			),
			array(
				'field' => 'password',
				'label' => 'Password',
				'input' => Form::password('password', ''),
				'error' => isset($this->errors['password']) ? $this->errors['password'][0] : '',
			),
			array(
				'field' => 'remember',
				'label' => 'Remember Me',
				'input' => Form::checkbox('remember', 'true'),
				'error' => isset($this->errors['remember']) ? $this->errors['remember'][0] : '',
			),
		);
	}

}	// @codeCoverageIgnoreEnd
