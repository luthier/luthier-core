<?php // @codeCoverageIgnoreStart
// Ignored because placeholder class
defined('SYSPATH') or die('No direct script access.');

/**
 * Basic Auth interface.
 *
 * @package     Kalf
 * @category    Base
 * @author      Kyle Treubig
 * @copyright   (C) 2011 Kyle Treubig
 * @license     MIT
 */
class Kalf_Auth {

	/**
	 * Retrieve the currently logged in user, or
	 * null if the user is not logged in.
	 *
	 * @return The current user
	 */
	public static function get_user()
	{
		return NULL;
	}

	/**
	 * Check if the current user is logged in.
	 *
	 * @return True if the user is logged in, else false
	 */
	public static function logged_in()
	{
		return TRUE;
	}

}	// @codeCoverageIgnoreEnd
