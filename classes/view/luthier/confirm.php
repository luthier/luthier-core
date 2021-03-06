<?php // @codeCoverageIgnoreStart
// Ignored because only contains abstract methods
defined('SYSPATH') or die('No direct script access.');

/**
 * Basic confirmation view
 *
 * @package     Luthier
 * @category    View
 * @author      Kyle Treubig
 * @copyright   (C) 2011 Kyle Treubig
 * @license     MIT
 */
abstract class View_Luthier_Confirm extends Luthier_Layout {

	/**
	 * @var string  The action being confirmed
	 */
	public $action = "act on";

	/**
	 * @var string  The type of object being acted upon
	 */
	public $thing = "object";

	/**
	 * @var string  An identifying name of the object
	 */
	public $name = "myObject";

	/**
	 * @var string  An optional note regarding the action,
	 *              for example, "This action cannot be undone."
	 */
	public $note = "";

	/**
	 * Use the luthier/confirm template
	 */
	public function __construct($template = NULL, array $partials = NULL)
	{
		parent::__construct('luthier/confirm', $partials);
	}

}	// @codeCoverageIgnoreEnd
