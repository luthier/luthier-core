<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Kalf base layout view
 *
 * @package     Kalf
 * @category    View
 * @author      Kyle Treubig
 * @copyright   (C) 2011 Kyle Treubig
 * @license     MIT
 */
abstract class Kalf_Layout_Core extends Kostache_Layout {

	/** The sub-layout template */
	protected $_sub_layout = "full";

	/** The layout template */
	protected $_layout = "kalf/layout";

	/** The current directory */
	protected $_directory = '';

	/** The auto-detected directories/controllers */
	private $_controllers;

	/** The section navigation */
	private $_section_navigation;

	/** Whether the current user is logged in */
	public $logged_in = FALSE;

	/** Stylesheets */
	protected $_stylesheets = array();

	/** Scripts */
	protected $_scripts = array();

	/**
	 * Set the current controller based on the current request.
	 * This is done in the constructor so the property can be
	 * overriden after the view is instantiated.
	 */
	public function __construct($template, array $partials = NULL)
	{
		parent::__construct($template, $partials);

		$this->_directory = trim(str_replace(Kalf::ROUTE_NAMESPACE, "", Request::current()->directory()), "/");
	}

	/**
	 * Prior to rendering, add the sub-layout
	 * as a partial
	 */
	public function render()
	{
		$this->partial('layout', 'kalf/layout/'.$this->_sub_layout);
		return $this->_stash($this->_template, $this, $this->_partials)->render();
	}

	/**
	 * Get the stylesheets
	 */
	public function stylesheets()
	{
		$stylesheets = array();

		foreach ($this->_stylesheets as $style => $media)
		{
			$stylesheets[] = array(
				'url'   => Route::get('kalf/media')->uri(array('file' => $style)),
				'media' => $media,
			);
		}

		return $stylesheets;
	}

	/**
	 * Get the scripts
	 */
	public function scripts()
	{
		$scripts = array();

		foreach ($this->_scripts as $script)
		{
			$scripts[] = array(
				'url' => Route::get('kalf/media')->uri(array('file' => $script)),
			);
		}

		return $scripts;
	}

	/**
	 * Generate the main navigation links
	 */
	public function main_navigation()
	{
		$main_navigation = array();

		// Add link to home/index/dashboard
		$url  = Route::get('kalf')->uri();
		$text = __('Home');
		$main_navigation[] = array(
			'url'    => $url,
			'text'   => $text,
			'slug'   => URL::title($text),
			'active' => ($this->_directory == ''),
		);

		// Auto-detect Kalf directories/controllers
		$directories = $this->_detect_controllers();

		// Add each detected directory of controllers as a navigation item
		foreach ($directories as $directory => $controllers)
		{
			// Add a link for the first controller in the directory (omitting home from url)
			$url  = Route::get('kalf')->uri(
				array(
					'directory'  => $directory,
					'controller' => ($controllers[0] == "home" ? "" : $controllers[0]),
				)
			);
			$text = __(ucfirst($directory));
			$main_navigation[] = array(
				'url'    => $url,
				'text'   => $text,
				'slug'   => URL::title($text),
				'active' => ($this->_directory == $directory),
			);
		}

		// Add login/logout link
		$url  = $this->logged_in
			? Route::get('kalf/auth')->uri(array('action' => 'logout'))
			: Route::get('kalf/auth')->uri();
		$text = $this->logged_in ? __('Logout') : __('Login');
		$main_navigation[] = array(
			'url'    => $url,
			'text'   => $text,
			'slug'   => URL::title($text),
			'active' => ($this->_directory == 'auth'),
		);

		return $main_navigation;
	}

	/**
	 * Auto-detect the directories of Kalf controllers
	 */
	public function _detect_controllers(array $paths = NULL)
	{
		// If controllers are cached, return them
		if ( ! empty($this->_controllers))
			return $this->_controllers;

		// Initialize controllers to an array
		$this->_controllers = array();

		// Find all Kalf controllers
		$files = Kohana::list_files('classes/controller/kalf', $paths);

		// Iterate through each directory
		foreach ($files as $directory => $controllers)
		{
			// Only detect controllers within directories
			if (is_array($controllers))
			{
				// Normalize the directory name
				$file = explode(DIRECTORY_SEPARATOR, $directory);
				$directory = end($file);

				Kohana::$log->add(Log::DEBUG, "Found directory :dir",
					array(':dir' => $directory));

				// Create directory array if one doesn't exist
				if ( ! isset($this->_controllers[$directory]))
				{
					$this->_controllers[$directory] = array();
				}

				// Iterate through each controller within directory
				foreach ($controllers as $controller => $path)
				{
					// Normalize the controller name
					$file = explode(DIRECTORY_SEPARATOR, $controller);
					$controller = end($file);
					$controller = substr($controller, 0, -(strlen(EXT)));

					Kohana::$log->add(Log::DEBUG, "Found controller :controller",
						array(':controller' => $controller));

					// If controller's name is "home"
					if ($controller == 'home')
					{
						// Add controller as first in directory list
						array_unshift($this->_controllers[$directory], $controller);
					}
					else
					{
						// Add controller to end of directory list
						array_push($this->_controllers[$directory], $controller);
					}
				}
			}
		}

		return $this->_controllers;
	}

	public function has_section_navigation()
	{
		return (count($this->section_navigation()) > 0);
	}

	public function section_navigation()
	{
		// If section navigation has been created, return it
		if ( ! empty($this->_section_navigation))
			return $this->_section_navigation;

		// Initialize to an array
		$this->_section_navigation = array();

		// Get detected Kalf directories/controllers
		$directories = $this->_detect_controllers();

		// If current directory doesn't exist or doesn't have multiple controllers
		if (( ! isset($directories[$this->_directory]))
		    OR (count($directories[$this->_directory]) < 2))
			// Don't process further (empty array)
			return $this->_section_navigation;

		// Add current directory as header
		$section = array();
		$section['header'] = __(ucfirst($this->_directory));
		$section['links']  = array();

		// Add each controller to the section navigation
		foreach ($directories[$this->_directory] as $controller)
		{
			// Skip the home controller
			if ($controller == 'home')
				continue;

			$url  = Route::get('kalf')->uri(
				array(
					'directory'  => $this->_directory,
					'controller' => $controller,
				)
			);

			$section['links'][] = array(
				'url'  => $url,
				'text' => __(ucfirst($controller)),
			);
		}

		$this->_section_navigation[$this->_directory] = $section;
		return $this->_section_navigation;
	}

	public function _add_section_nav($text, $url, $header = NULL)
	{
		// Use current directory if no header specified
		if (empty($header))
		{
			$header = $this->_directory;
		}

		// Get the section navigation
		$section_navigation = $this->section_navigation();

		// Create new header if it doesn't exist
		if ( ! isset($section_navigation[$header]))
		{
			$section_navigation[$header] = array(
				'header' => $header,
				'links'  => array(),
			);
		}

		// Add the item to the section navigation
		$section_navigation[$header]['links'][] = array(
			'url'  => $url,
			'text' => $text,
		);

		// Sort the section navigation items
		ksort($section_navigation[$header]['links']);

		// Save the section navigation
		$this->_section_navigation = $section_navigation;
	}

	public function user_messages()
	{
		$user_messages = array();

		$flash_messages = Kalf::messages();
		foreach ($flash_messages as $type => $messages)
		{
			foreach ($messages as $message)
			{
				$user_messages[] = array(
					'type'    => $type,
					'message' => $message,
				);
			}
		}

		return $user_messages;
	}

}	// End of Kalf_Layout_Core
