<?php

/**
 * Address finder nominatim geocoder file.
 *
 * @see       https://nominatim.org Documentation of Nominatim API
 *
 * @copyright YetiForce Sp. z o.o
 * @license   YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Mariusz Krzaczkowski <m.krzaczkowski@yetiforce.com>
 */

namespace App\Map\Address;

/**
 * Address finder nominatim geocoder class.
 */
class NominatimGeocoder extends Base
{
	/**
	 * {@inheritdoc}
	 */
	public static function isActive()
	{
		return !empty(\Config\Components\AddressFinder::$nominatimMapUrl);
	}

	/**
	 * {@inheritdoc}
	 */
	public function find($value): array
	{
		if (empty($value) || !\App\RequestUtil::isNetConnection()) {
			return [];
		}
		$config = \App\Map\Address::getConfig();
		$params = [
			'format' => 'json',
			'addressdetails' => 1,
			'limit' => $config['global']['result_num'],
			'accept-language' => \App\Language::getLanguage() . ',' . \App\Config::main('default_language') . ',en-US',
			'q' => $value
		];
		if ($countryCode = \Config\Components\AddressFinder::$nominatimCountryCode) {
			$params['countrycodes'] = implode(',', $countryCode);
		}
		$rows = [];
		try {
			$response = (new \GuzzleHttp\Client(\App\RequestHttp::getOptions()))
				->request('GET', \Config\Components\AddressFinder::$nominatimMapUrl . '/?' . \http_build_query($params));
			if (200 !== $response->getStatusCode()) {
				throw new \App\Exceptions\AppException('Error with connection |' . $response->getStatusCode());
			}
			$json = \App\Json::decode($response->getBody());

			if ($json) {
				$mainMapping = \Config\Components\AddressFinder::nominatimRemapping();
				if (!\is_callable($mainMapping)) {
					$mainMapping = [$this, 'parseRow'];
				}
				$countryMapping = \Config\Components\AddressFinder::nominatimRemappingForCountry();
				foreach ($json as $row) {
					$mappingFunction = $mainMapping;
					if (isset($row['address']['country_code'], $countryMapping[\strtoupper($row['address']['country_code'])])) {
						$mappingFunction = $countryMapping[\strtoupper($row['address']['country_code'])];
					}
					$rows[] = [
						'label' => $row['display_name'],
						'address' => \call_user_func_array($mappingFunction, [$row])
					];
				}
			}
		} catch (\Throwable $ex) {
			\App\Log::warning('Error - ' . __CLASS__ . ' - ' . $ex->getMessage());
		}
		return $rows;
	}

	/**
	 * Main function to parse information about address.
	 *
	 * @param array $row
	 *
	 * @return array
	 */
	private function parseRow(array $row): array
	{
		return [
			'addresslevel1' => [$row['address']['country'] ?? '', strtoupper($row['address']['country_code'] ?? '')],
			'addresslevel2' => $row['address']['state'] ?? '',
			'addresslevel3' => $row['address']['state_district'] ?? '',
			'addresslevel4' => $row['address']['county'] ?? '',
			'addresslevel5' => $row['address']['city'] ?? $row['address']['town'] ?? $row['address']['village'] ?? '',
			'addresslevel6' => $row['address']['suburb'] ?? $row['address']['neighbourhood'] ?? $row['address']['city_district'] ?? '',
			'addresslevel7' => $row['address']['postcode'] ?? '',
			'addresslevel8' => $row['address']['road'] ?? '',
			'buildingnumber' => $row['address']['house_number'] ?? '',
			'localnumber' => $row['address']['local_number'] ?? '',
		];
	}
}
