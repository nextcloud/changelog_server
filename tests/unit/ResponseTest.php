<?php
/**
 * @license MIT <http://opensource.org/licenses/MIT>
 */

namespace Tests;

use ChangelogServer\Request;
use ChangelogServer\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase {
	/** @var Request|\PHPUnit\Framework\MockObject\MockObject */
	protected $request;

	public function setUp()
	{
		parent::setUp();
		$this->request = $this->createMock(Request::class);
	}

	public function requestDataProvider() {
		$data = __DIR__ . '/../data/';
		return [
			[
				# 404 case
				12, 0, 8,
				'',
				''
			],
			[
				# requested file
				13, 0, 0,
				'',
				file_get_contents($data . '13.0.0.xml')
			],
			[
				# not modified → no content
				13, 0, 0,
				md5_file($data . '13.0.0.xml'),
				''
			],
			[
				# modified → content
				13, 0, 0,
				'asdf',
				file_get_contents($data . '13.0.0.xml')
			],
		];
	}

	/**
	 * @runInSeparateProcess
	 * @dataProvider requestDataProvider
	 */
	public function testBuildResponse($major, $minor, $point, $etag, $expectedOutput) {
		ob_start();

		$this->request->expects($this->any())
			->method('getMajorVersion')
			->willReturn($major);
		$this->request->expects($this->any())
			->method('getMinorVersion')
			->willReturn($minor);
		$this->request->expects($this->any())
			->method('getMaintenanceVersion')
			->willReturn($point);
		$this->request->expects($this->any())
			->method('getEtag')
			->willReturn($etag);

		$response = new Response($this->request, __DIR__ . '/../data/');
		$response->buildResponse();

		$out = ob_get_clean();

		$this->assertSame($out, $expectedOutput);
		// I did not find a reliable way to determine http status header, integration tests will deal with that
	}
}
