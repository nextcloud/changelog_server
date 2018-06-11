<?php
/**
 * @license MIT <http://opensource.org/licenses/MIT>
 */

namespace Tests;

use ChangelogServer\Exceptions\InvalidVersion;
use ChangelogServer\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase {

	public function requestDataProvider():array {
		return [
			[
				'14.0.0',
				14,
				0,
				0,
				'e18067f1343b68cf3bfdc80c7613441723fe24be783ed2a2cf5ad6a5b92b6f05'
			],
			[
				'13.0.3',
				13,
				0,
				3,
				'e18067f1343b68cf3bfdc80c7613441723fe24be783ed2a2cf5ad6a5b92b6f05'
			],
			[
				'013.00.03',
				13,
				0,
				3,
				''
			],
			[
				'14x0x0',
				null, null, null, ''
			],
			[
				'',
				null, null, null, ''
			],
		];
	}

	/**
	 * @dataProvider requestDataProvider
	 * @throws InvalidVersion
	 */
	public function testRequest(string $input, $major, $minor, $point, $etag) {
		if($major === null) {
			$this->expectException(InvalidVersion::class);
		}
		$request = new Request($input, $etag);
		$this->assertSame($major, $request->getMajorVersion());
		$this->assertSame($minor, $request->getMinorVersion());
		$this->assertSame($point, $request->getMaintenanceVersion());
		$this->assertSame($etag, $request->getEtag());
	}
}
