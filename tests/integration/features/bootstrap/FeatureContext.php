<?php
/**
 * @license MIT <http://opensource.org/licenses/MIT>
 */

use Behat\Behat\Context\SnippetAcceptingContext;

class FeatureContext implements SnippetAcceptingContext {
	/** @var string */
	protected $versionString;
	/** @var string */
	protected $knownEtag = '';

	/** @var string */
	protected $responseBody = '';
	/** @var int */
	protected $responseCode;
	/** @var string */
	protected $responseEtag;

	/**
	 * @When /^the version of interest is "([^"]*)"$/
	 */
	public function theVersionOfInterestIs(string $versionString)
	{
		$this->versionString = $versionString;
	}

	/**
	 * @When /^the request is sent$/
	 */
	public function theRequestIsSent()
	{
		$header = [];
		if($this->knownEtag !== '') {
			$header[] = 'If-None-Match: ' .  $this->knownEtag;
		}

		$ch = curl_init();
		$optArray = [
			CURLOPT_URL => 'http://localhost:8888/?version=' . urlencode($this->versionString),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => $header,
			CURLOPT_HEADERFUNCTION => [$this, 'extractEtag'],
		];
		curl_setopt_array($ch, $optArray);
		$this->responseBody = curl_exec($ch);
		$this->responseCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
		curl_close($ch);
	}

	/**
	 * @Then /^the response is empty$/
	 * @throws Exception
	 */
	public function theResponseIsEmpty()
	{
		if((string)$this->responseBody !== '') {
			throw new \Exception('Response is not empty');
		}
	}

	/**
	 * @Given /^the return code is "([^"]*)"$/
	 * @throws Exception
	 */
	public function theReturnCodeIs($statusCode)
	{
		if((int)$statusCode !== $this->responseCode) {
			throw new \Exception(
				'Response was expected to be ' . $statusCode
				. ', but actually is ' . $this->responseCode
			);
		}
	}

	/**
	 * @Given /^the response contains$/
	 * @throws Exception
	 */
	public function theResponseContains(\Behat\Gherkin\Node\PyStringNode $expectedResponse)
	{
		if(strpos(trim($this->responseBody),trim($expectedResponse)) === false) {

			throw new \Exception(
				'The response body was expected to contain ' . $expectedResponse
				. ', but actually is ' . $this->responseBody
			);
		}
	}

	public function extractEtag($ch, $headerString) {
		$etagPrefix = 'Etag: ';
		if(strpos($headerString, $etagPrefix) === 0) {
			$this->responseEtag = trim(substr($headerString, strlen($etagPrefix)));
		}
		return strlen($headerString);
	}

	/**
	 * @Given /^the known Etag is "([^"]*)"$/
	 */
	public function theKnownEtagIs($etag)
	{
		$this->knownEtag = $etag;
	}

	/**
	 * @Given /^remembering the received Etag$/
	 */
	public function rememberingTheReceivedEtag()
	{
		$this->knownEtag = $this->responseEtag;
	}
}
