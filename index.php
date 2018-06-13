<?php
/**
 * @license MIT <http://opensource.org/licenses/MIT>
 */

require_once __DIR__ . '/vendor/autoload.php';

// Set the default timezone to Europe/Berlin
date_default_timezone_set('Europe/Berlin');

// Set Content-Type to XML
header('Content-Type: application/xml');
// Enforce browser based XSS filters
header('X-XSS-Protection: 1; mode=block');
// Disable sniffing the content type for IE
header('X-Content-Type-Options: nosniff');
// Disallow iFraming from other domains
header('X-Frame-Options: Sameorigin');
// https://developers.google.com/webmasters/control-crawl-index/docs/robots_meta_tag
header('X-Robots-Tag: none');

// Return empty response if no version is supplied
if(!isset($_GET['version']) || !is_string($_GET['version'])) {
	exit();
}

// Parse the request
try {
	$etag = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? $_SERVER['HTTP_IF_NONE_MATCH'] : '';
	$request = new \ChangelogServer\Request($_GET['version'], $etag);
} catch (\ChangelogServer\Exceptions\InvalidVersion $e) {
	header('HTTP/1.1 400 Bad Request');
	exit();
}

$dataDir = __DIR__ . '/data/';

// Return a response (writes to buffer)
$response = new \ChangelogServer\Response($request, $dataDir);
$response->buildResponse();
