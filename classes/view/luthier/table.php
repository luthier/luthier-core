<?php // @codeCoverageIgnoreStart
// Ignored because only contains abstract methods
defined('SYSPATH') or die('No direct script access.');

/**
 * Basic table view
 *
 * @package     Luthier
 * @category    View
 * @author      Kyle Treubig
 * @copyright   (C) 2011 Kyle Treubig
 * @license     MIT
 */
abstract class View_Luthier_Table extends Luthier_Layout {

	/**
	 * Use the luthier/table template
	 */
	public function __construct($template = NULL, array $partials = NULL)
	{
		parent::__construct('luthier/table', $partials);
	}

	/**
	 * Return an array of column titles, for example:
	 *
	 *     return array(
	 *         array('title' => 'ID'),
	 *         array('title' => 'Name', 'col_class' => 'class'),
	 *     );
	 *
	 * @return array
	 */
	abstract public function columns();

	/**
	 * Return a 2-dimensional array of rows and cell
	 * contents, for example:
	 *
	 *     return array(
	 *         array(
	 *             'cells' => array(
	 *                 array('contents' => '1'),
	 *                 array('contents' => 'Alice'),
	 *             ),
	 *         ),
	 *         array(
	 *             'row_class' => 'class',
	 *             'cells'     => array(
	 *                 array('contents' => '1', 'cell_class' => 'class'),
	 *                 array('contents' => 'Bob'),
	 *             ),
	 *         ),
	 *     );
	 *
	 * @return array
	 */
	abstract public function rows();

}	// @codeCoverageIgnoreEnd
