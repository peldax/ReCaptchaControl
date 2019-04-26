<?php

/**
 * This file is part of the ReCaptchaControl package
 *
 * @license  MIT
 * @author   Petr Kessler (https://kesspess.cz)
 * @link     https://github.com/uestla/ReCaptchaControl
 */

declare(strict_types = 1);

namespace ReCaptchaControl\Http\Requester;

use Nette\Utils\Strings;


class CurlRequester implements IRequester
{

	/** @var array */
	private $options;


	/** @param  array $options */
	public function __construct(array $options = [])
	{
		if (!extension_loaded('curl')) {
			throw new \RuntimeException(sprintf('cURL extension is needed by the %s class.', __CLASS__));
		}

		$this->options = self::processOptions($options);
	}


	/** @inheritdoc */
	public function post(string $url, array $values = []) : ?string
	{
		$ch = curl_init();

		curl_setopt_array($ch, [
			CURLOPT_URL => $url,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $values,
			CURLOPT_RETURNTRANSFER => true,

		] + $this->options);

		$response = curl_exec($ch);
		$errno = curl_errno($ch);
		curl_close($ch);

		if ($errno === 0) {
			return $response;
		}

		return null; // throw exception?
	}


	private static function processOptions(array $options) : array
	{
		// NOTE: intentionally not using array_walk since array keys cannot be changed
		foreach ($options as $key => $val) {
			if (Strings::startsWith($key, 'CURLOPT_')) {
				unset($options[$key]);
				$options[constant($key)] = $val;
			}
		}

		return $options;
	}

}
