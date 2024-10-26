<?php
/**
 * SPDX-FileCopyrightText: 2018 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: MIT
 */

namespace ChangelogServer;

use ChangelogServer\Exceptions\InvalidVersion;

class Request {
	/** @var int */
	private $majorVersion;
	/** @var int */
	private $minorVersion;
	/** @var int */
	private $maintenanceVersion;
	/** @var string */
	private $etag;

	/**
	 * Request constructor.
	 * @param string $versionString
	 * @param string $etag
	 * @throws InvalidVersion
	 */
	public function __construct(string $versionString, string $etag) {
		$this->readVersion($versionString);
		$this->etag = $etag;
	}

	/**
	 * @param string $versionString
	 * @throws InvalidVersion
	 */
	protected function readVersion(string $versionString) {
		$version = explode('.', $versionString);
		if(count($version) !== 3) {
			throw new InvalidVersion();
		}
		$this->majorVersion = (int)$version['0'];
		$this->minorVersion = (int)$version['1'];
		$this->maintenanceVersion = (int)$version['2'];
	}

	public function getMajorVersion():int {
		return $this->majorVersion;
	}

	public function getMinorVersion():int {
		return $this->minorVersion;
	}

	public function getMaintenanceVersion():int {
		return $this->maintenanceVersion;
	}

	public function getEtag(): string {
		return $this->etag;
	}
}
