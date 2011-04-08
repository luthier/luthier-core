<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Luthier base layout view
 *
 * @package     Luthier
 * @category    View
 * @author      Kyle Treubig
 * @copyright   (C) 2011 Kyle Treubig
 * @license     MIT
 */
abstract class Luthier_Layout_Core extends Kostache_Layout {

	// The following protected/private attributes are used internally

	/** The sub-layout template */
	protected $_sub_layout = "full";

	/** The layout template */
	protected $_layout = "luthier/layout";

	/** The current directory */
	protected $_directory = '';

	/** The current controller */
	protected $_controller = '';

	/** The auto-detected directories/controllers */
	private $_controllers;

	/** The section navigation */
	private $_section_navigation;

	/** Stylesheets */
	protected $_stylesheets = array();

	/** Scripts */
	protected $_scripts = array();

	// The following public attributes are used in mustache templates

	/** Whether the current user is logged in */
	public $logged_in = FALSE;

	/** The media route URL */
	public $media_url;

	/** Title for the admin area */
	public $title = "Administrative Control Panel";

	/** Header for individual pages */
	public $header;

	/** The base URL */
	public $base_url;

	/**
	 * We need to set a few things first...
	 */
	public function __construct($template = NULL, array $partials = NULL)
	{
		parent::__construct($template, $partials);

		// Add section navigation as a partial
		$this->partial('section_navigation', 'luthier/navigation');

		// URLs
		$this->base_url   = Kohana::$base_url;
		$this->media_url  = Route::url('luthier/media', array('file'=>''));

		// Get the current directory from the request/route
		$this->_directory = trim(str_replace("luthier", "", Request::current()->directory()), "/");

		// Get the current controller from the request/route
		$this->_controller = Request::current()->controller();
	}

	/**
	 * Prior to rendering, add the sub-layout
	 * as a partial
	 */
	public function render()
	{
		$this->partial('layout', 'luthier/layout/'.$this->_sub_layout);
		return parent::render();
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
				'url'   => Route::get('luthier/media')->uri(array('file' => $style)),
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
				'url' => Route::get('luthier/media')->uri(array('file' => $script)),
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
		$url  = Route::get('luthier')->uri();
		$text = __('Home');
		$main_navigation[] = array(
			'url'    => $url,
			'text'   => $text,
			'slug'   => URL::title($text),
			'active' => (($this->_directory == '') && ($this->_controller == 'home')),
		);

		// Auto-detect Luthier directories/controllers
		$directories = $this->_detect_controllers();

		// Add each detected directory of controllers as a navigation item
		foreach ($directories as $directory => $controllers)
		{
			// Add a link for the first controller in the directory (omitting home from url)
			$url  = Route::get('luthier')->uri(
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
			? Route::get('luthier/auth')->uri(array('action' => 'logout'))
			: Route::get('luthier/auth')->uri();
		$text = $this->logged_in ? __('Logout') : __('Login');
		$main_navigation[] = array(
			'url'    => $url,
			'text'   => $text,
			'slug'   => URL::title($text),
			'active' => (($this->_directory == '') && (Request::current()->controller() == 'auth')),
		);

		return $main_navigation;
	}

	/**
	 * Auto-detect the directories of Luthier controllers
	 */
	public function _detect_controllers(array $paths = NULL)
	{
		// If controllers are cached, return them
		if ( ! empty($this->_controllers))
			return $this->_controllers;

		// Initialize controllers to an array
		$this->_controllers = array();

		// Find all Luthier controllers
		$files = Kohana::list_files('classes/controller/luthier', $paths);

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

	/**
	 * Are there any section navigation items?
	 */
	public function has_section_navigation()
	{
		return (count($this->_section_navigation()) > 0);
	}

	/**
	 * Need to turn the section navigation into
	 * a non-associative array for mustache.
	 */
	public function section_navigation()
	{
		$section_navigation = array();

		foreach ($this->_section_navigation() as $section)
		{
			$section_navigation[] = $section;
		}

		return $section_navigation;
	}

	/**
	 * Create the section navigation for other
	 * controllers in the current directory.
	 */
	protected function _section_navigation()
	{
		// If section navigation has been created, return it
		if ( ! empty($this->_section_navigation))
			return $this->_section_navigation;

		// Initialize to an array
		$this->_section_navigation = array();

		// Get detected Luthier directories/controllers
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

			$url  = Route::get('luthier')->uri(
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

	/**
	 * Add a new item to the section navigation, or optionally
	 * create a new heading under the section navigation.
	 */
	public function _add_section_nav($text, $url, $header = NULL)
	{
		// Use current directory if no header specified
		if (empty($header))
		{
			$header = $this->_directory;
		}

		// Get the section navigation
		$section_navigation = $this->_section_navigation();

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

	/**
	 * Get any user messages from the session.
	 */
	public function user_messages()
	{
		$user_messages = array();

		$flash_messages = Luthier::messages();
		foreach ($flash_messages as $type => $messages)
		{
			foreach ($messages as $message)
			{
				$user_messages[] = array(
					'type'     => $type,
					'message'  => $message,
					'is_error' => ($type == Luthier::ERROR),
				);
			}
		}

		return $user_messages;
	}

}
