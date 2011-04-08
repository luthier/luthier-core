<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Core functions of luthier
 *
 * @package     Luthier
 * @category    Base
 * @author      Kyle Treubig
 * @copyright   (C) 2010 Kyle Treubig
 * @license     MIT
 */
abstract class Luthier_Core {

	/** Route namespace */
	const ROUTE_NAMESPACE = 'admin';

	/** Info user message type */
	const INFO = 'info';

	/** Error user message type */
	const ERROR = 'error';

	/**
	 * Sets a flash message in the session
	 *
	 * @param   string  The message contents
	 * @param   string  The type of message
	 */
	public static function message($msg, $type = self::INFO)
	{
		// Get session
		$session = Session::instance(Kohana::config('luthier.messages.session.type'));

		// Get session key from config
		$key = Kohana::config('luthier.messages.session.key');

		// Get current messages
		$messages = unserialize($session->get($key, FALSE));

		// Add message
		$messages[$type][] = $msg;

		// Store messages
		$session->set($key, serialize($messages));
	}

	/**
	 * Get flash messages from session, if any exist
	 *
	 * @return  An array of flash user messages
	 */
	public static function messages()
	{
		// Get session
		$session = Session::instance(Kohana::config('luthier.messages.session.type'));

		// Get session key from config
		$key = Kohana::config('luthier.messages.session.key');

		// Get messages
		$messages = unserialize($session->get($key, FALSE));

		// Delete messages
		$session->delete($key);

		// Return messages
		return is_array($messages) ? $messages : array();
	}

}
