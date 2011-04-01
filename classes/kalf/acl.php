<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Basic ACL interface.
 *
 * @package     Kalf
 * @category    Base
 * @author      Kyle Treubig
 * @copyright   (C) 2011 Kyle Treubig
 * @license     MIT
 */
class Kalf_Acl {

	/**
	 * Perform an ACL check for a given user, checking if
	 * the desired action is allowed for the given resource.
	 *
	 * @param object The user or role to check for access
	 * @param mixed  The resource to act upon
	 * @param string The desired action
	 * @return       True if allowed, else false
	 */
	public static function allowed($user, $resource, $action)
	{
		return TRUE;
	}

}
