<?php
/**
 * @license MIT <http://opensource.org/licenses/MIT>
 */

namespace ChangelogServer;

class Response {
	/** @var Request */
	private $request;
	/** @var string */
	private $etag;
	/** @var string */
	private $dataDir;

	public function __construct(Request $request, $dataDir) {
		$this->request = $request;
		$this->dataDir = $dataDir;
	}

	public function buildResponse() {
		$path = $this->dataDir . '/'
			. $this->request->getMajorVersion() . '.'
			. $this->request->getMinorVersion() . '.'
			. $this->request->getMaintenanceVersion() . '.'
			. 'xml';

		if(!file_exists($path)) {
			header("Etag: " . md5(''));
			return;
		}

		if($this->isNotModified($path)) {
			header('HTTP/1.1 304 Not Modified');
			return;
		}

		header("Etag: " . $this->etag);
		readfile($path);
	}

	protected function isNotModified($path):bool {
		$this->computeEtag($path);
		return $this->request->getEtag() !== ''
			&& $this->request->getEtag() === $this->etag;
	}

	protected function computeEtag($path) {
		$this->etag = md5_file($path);
	}
}
