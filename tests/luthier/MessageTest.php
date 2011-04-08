<?php defined('SYSPATH') or die('No direct script access.');

/**
 * PHPUnit tests for flash messages
 *
 * @group       luthier
 * @group       luthier.message
 *
 * @package     Luthier
 * @category    Tests
 * @author      Kyle Treubig
 * @copyright   (C) 2011 Kyle Treubig
 * @license     MIT
 */
class Luthier_MessageTest extends Unittest_TestCase {

	/**
	 * Reset the session
	 */
	public function setUp()
	{
		parent::setUp();

		// Get session
		$this->session = Session::instance(Kohana::config('luthier.messages.session.type'));

		// Get session key
		$this->key = Kohana::config('luthier.messages.session.key');

		// Remove any prior messages
		$this->session->delete($this->key);
	}

	/**
	 * Test that messages are stored as a serialized
	 * array in session
	 */
	public function test_messages_stored_as_serialized_array()
	{
		// Store message
		$msg = 'TEST SERIALIZED ARRAY';
		Luthier::message($msg);

		// Verify message serialized in session
		$message = unserialize($this->session->get($this->key));
		$this->assertTrue(is_array($message));
		$this->assertEquals(1, count($message));
		$this->assertContains($msg, Arr::flatten($message));
	}

	/**
	 * Provider for test_message_types
	 *
	 * @return array
	 */
	public function provider_message_types()
	{
		return array(
			array('TEST INFO MESSAGE', Luthier::INFO),
			array('TEST ERROR MESSAGE', Luthier::ERROR),
		);
	}

	/**
	 * Test that messages are stored with a given type
	 *
	 * @dataProvider provider_message_types
	 */
	public function test_message_types($msg, $type)
	{
		// Store message
		Luthier::message($msg, $type);

		// Verify message saved with type in session
		$message = unserialize($this->session->get($this->key));
		$this->assertEquals($msg, $message[$type][0]);
	}

	/**
	 * Test that messages are deleted after being retrieved
	 */
	public function test_messages_are_deleted()
	{
		// Store messages
		Luthier::message('TEST MESSAGE 1', Luthier::INFO);
		Luthier::message('TEST MESSAGE 2', Luthier::ERROR);

		// Retrieve messages
		Luthier::messages();

		// Verify messages deleted from session
		$session = $this->session->get($this->key, FALSE);
		$this->assertFalse($session);
	}

	/**
	 * Test that unzerialized array is returned
	 */
	public function test_unserialized_array()
	{
		$msg = 'TEST SERIALIZED ARRAY';
		$this->session->set($this->key, serialize(array('info' => array($msg))));
		Luthier::message($msg);

		$message = Luthier::messages();
		$this->assertTrue(is_array($message));
		$this->assertEquals(1, count($message));
		$this->assertContains($msg, Arr::flatten($message));
	}

	/**
	 * Test that empty array is returned if there are no
	 * messages stored in session
	 */
	public function test_empty_array_when_no_messages()
	{
		$message = Luthier::messages();
		$this->assertTrue(is_array($message));
		$this->assertEquals(0, count($message));
	}

}
