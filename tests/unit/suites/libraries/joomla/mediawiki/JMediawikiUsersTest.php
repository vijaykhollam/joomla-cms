<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Mediawiki
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once JPATH_PLATFORM . '/joomla/mediawiki/mediawiki.php';
require_once JPATH_PLATFORM . '/joomla/mediawiki/http.php';
require_once JPATH_PLATFORM . '/joomla/mediawiki/users.php';

/**
 * Test class for JMediawikiUsers.
 *
 * @package     Joomla.UnitTest
 * @subpackage  Mediawiki
 */
class JMediawikiUsersTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var    JRegistry  Options for the Mediawiki object.
	 */
	protected $options;

	/**
	 * @var    JMediawikiHttp  Mock client object.
	 */
	protected $client;

	/**
	 * @var    JMediawikiUsers  Object under test.
	 */
	protected $object;

	/**
	 * @var    string  Sample xml string.
	 */
	protected $sampleString = '<a><b></b><c></c></a>';

	/**
	 * @var    string  Sample xml error message.
	 */
	protected $errorString = '<message>Generic Error</message>';

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		$this->options = new JRegistry;
		$this->client = $this->getMock('JMediawikiHttp', array('get', 'post', 'delete', 'patch', 'put'));

		$this->object = new JMediawikiUsers($this->options, $this->client);
	}

	/**
	 * Tests the getUserInfo method
	 */
	public function testGetUserInfo()
	{
		$returnData = $this->getReturnData();

		$this->client
			->expects($this->once())
			->method('get')
			->with('/api.php?action=query&list=users&ususers=Joomla&format=xml')
			->willReturn($returnData);

		$this->assertEquals(
			simplexml_load_string($this->sampleString),
			$this->object->getUserInfo(array('Joomla'))
		);
	}

	/**
	 * Tests the getCurrentUserInfo method
	 */
	public function testGetCurrentUserInfo()
	{
		$returnData = $this->getReturnData();

		$this->client->expects($this->once())
			->method('get')
			->with('/api.php?action=query&meta=userinfo&format=xml')
			->willReturn($returnData);

		$this->assertEquals(
			simplexml_load_string($this->sampleString),
			$this->object->getCurrentUserInfo()
		);
	}

	/**
	 * Tests the getUserContribs method
	 */
	public function testGetUserContribs()
	{
		$returnData = $this->getReturnData();

		$this->client->expects($this->once())
			->method('get')
			->with('/api.php?action=query&list=usercontribs&ucuser=Joomla&format=xml')
			->willReturn($returnData);

		$this->assertEquals(
			simplexml_load_string($this->sampleString),
			$this->object->getUserContribs('Joomla')
		);
	}

	/**
	 * @return stdClass
	 */
	private function getReturnData()
	{
		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		return $returnData;
	}
}
