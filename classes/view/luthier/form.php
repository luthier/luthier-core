<?php // @codeCoverageIgnoreStart
// Ignored because only contains abstract methods
defined('SYSPATH') or die('No direct script access.');

/**
 * Basic form view
 *
 * @package     Luthier
 * @category    View
 * @author      Kyle Treubig
 * @copyright   (C) 2011 Kyle Treubig
 * @license     MIT
 */
abstract class View_Luthier_Form extends Luthier_Layout {

	/**
	 * @var boolean Whether the form includes file fields
	 */
	public $upload = FALSE;

	/**
	 * @var string  The text for the submit button
	 */
	public $submit = "submit";

	/**
	 * Use the luthier/form template
	 */
	public function __construct($template = NULL, array $partials = NULL)
	{
		parent::__construct('luthier/form', $partials);
	}

	/**
	 * Return an array of fields, for example:
	 *
	 *     return array(
	 *         array(
	 *             'field' => 'id',
	 *             'label' => 'ID',
	 *             'input' => '<input name="id" />',
	 *         ),
	 *         array(
	 *             'error' => 'Name must not be blank',
	 *             'field' => 'name',
	 *             'label' => 'Name',
	 *             'input' => '<input name="name" />',
	 *         ),
	 *     );
	 *
	 * @return array
	 */
	abstract public function fields();

}	// @codeCoverageIgnoreEnd
