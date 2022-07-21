<?php
/**
 * Vatify API file.
 *
 * @package App
 *
 * @see https://www.vatify.eu/coverage.html
 * @see https://api.vatify.eu/v1/demo/ test
 * @see https://api.vatify.eu/v1/ prod
 *
 * @copyright YetiForce S.A.
 * @license   YetiForce Public License 5.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Sławomir Rembiesa <s.rembiesa@yetiforce.com>
 */

namespace App\RecordCollectors;

/**
 * Vatify API class.
 */
class VatifyEu extends Base
{
	/** {@inheritdoc} */
	protected static $allowedModules = ['Accounts', 'Leads', 'Vendors', 'Partners', 'Competition'];

	/** {@inheritdoc} */
	public $icon = 'fas fa-globe-europe';

	/** {@inheritdoc} */
	public $label = 'LBL_VATIFY_EU';

	/** {@inheritdoc} */
	public $displayType = 'FillFields';

	/** {@inheritdoc} */
	public $description = 'LBL_VATIFY_DESC_EU';

	/** {@inheritdoc} */
	public $docUrl = 'https://www.vatify.eu/docs/api/getting-started/';

	/** {@inheritdoc} */
	protected $fields = [
		'country' => [
			'labelModule' => '_Base',
			'label' => 'Country',
			'picklistModule' => 'Other.Country',
			'typeofdata' => 'V~M',
			'uitype' => 16,
			'picklistValues' => [
				'Albania' => 'Albania',
				'Austria' => 'Austria',
				'Belarus' => 'Belarus',
				'Belgium' => 'Belgium',
				'Bosnia and Herzegovina' => 'Bosnia And Herzegovina',
				'Bulgaria' => 'Bulgaria',
				'Cyprus' => 'Cyprus',
				'Czech Republic' => 'Czech Republic',
				'Germany' => 'Germany',
				'Denmark' => 'Denmark',
				'Estonia' => 'Estonia',
				'Great Britain' => 'United Kingdom',
				'Greece' => 'Greece',
				'Spain' => 'Spain',
				'Finland' => 'Finland',
				'France' => 'France',
				'Northern Ireland' => 'Northern Ireland',
				'Georgia' => 'Georgia',
				'Croatia' => 'Croatia',
				'Hungary' => 'Hungary',
				'Iceland' => 'Iceland',
				'Ireland' => 'Ireland',
				'Israel' => 'Israel',
				'Italy' => 'Italy',
				'Kazakhstan' => 'Kazakstan',
				'Kosovo' => 'Kosovo',
				'Latvia' => 'Latvia',
				'Liechtenstein' => 'Liechtenstein',
				'Lithuania' => 'Lithuania',
				'Luxembourg' => 'Luxembourg',
				'North Macedonia' => 'Macedonia, The Former Yugoslav Republic Of',
				'Malta' => 'Malta',
				'Moldova' => 'Moldova, Republic Of',
				'Montenegro' => 'Montenegro',
				'Norway' => 'Norway',
				'The Netherlands' => 'Netherlands',
				'Poland' => 'Poland',
				'Portugal' => 'Portugal',
				'Romania' => 'Romania',
				'Russia' => 'Russian Federation',
				'Sweden' => 'Sweden',
				'Slovenia' => 'Slovenia',
				'Slovakia' => 'Slovakia',
				'Serbia' => 'Serbia',
				'Switzerland' => 'Switzerland',
				'Ukraine' => 'Ukraine',
				'South Africa' => 'South Africa',
			]
		],
		'vatNumber' => [
			'labelModule' => '_Base',
			'label' => 'Vat ID',
			'typeofdata' => 'V~M',
		]
	];

	/** {@inheritdoc} */
	protected $modulesFieldsMap = [
		'Accounts' => [
			'vatNumber' => 'vat_id',
			'country' => 'addresslevel1a'
		],
		'Leads' => [
			'vatNumber' => 'vat_id',
			'country' => 'addresslevel1a'
		],
		'Vendors' => [
			'vatNumber' => 'vat_id',
			'country' => 'addresslevel1a'
		],
		'Competition' => [
			'vatNumber' => 'vat_id',
			'country' => 'addresslevel1a'
		],
	];

	/** {@inheritdoc} */
	public $formFieldsToRecordMap = [
		'Accounts' => [
			'title' => 'accountname',
			'transliteratedTitle' => 'accountname',
			'registration_number' => 'registration_number_1',
			'identifier' => 'vat_id',
			'tax_id_number' => 'vat_id',
			'sector' => 'siccode',
			'transliteratedSector' => 'siccode',
			'address' => 'addresslevel8a',
			'transliteratedAddress' => 'addresslevel8a',
			'postal_code' => 'addresslevel7a',
			'city' => 'addresslevel5a',
			'transliteratedCity' => 'addresslevel5a',
			'community' => 'addresslevel4a',
			'region' => 'addresslevel2a',
			'transliteratedRegion' => 'addresslevel2a',
			'country' => 'addresslevel1a',
			'phone_number' => 'phone',
			'fax_number' => 'fax',
			'email_address' => 'email1',
		],
		'Leads' => [
			'title' => 'company',
			'transliteratedTitle' => 'company',
			'registration_number' => 'registration_number_1',
			'identifier' => 'vat_id',
			'tax_id_number' => 'vat_id',
			'address' => 'addresslevel8a',
			'transliteratedAddress' => 'addresslevel8a',
			'postal_code' => 'addresslevel7a',
			'city' => 'addresslevel5a',
			'transliteratedCity' => 'addresslevel5a',
			'community' => 'addresslevel4a',
			'region' => 'addresslevel2a',
			'transliteratedRegion' => 'addresslevel2a',
			'country' => 'addresslevel1a',
			'phone_number' => 'phone',
			'fax_number' => 'fax',
			'email_address' => 'email',
		],
		'Vendors' => [
			'title' => 'vendorname',
			'transliteratedTitle' => 'vendorname',
			'registration_number' => 'registration_number_1',
			'identifier' => 'vat_id',
			'tax_id_number' => 'vat_id',
			'transliteratedSector' => 'siccode',
			'address' => 'addresslevel8a',
			'transliteratedAddress' => 'addresslevel8a',
			'postal_code' => 'addresslevel7a',
			'city' => 'addresslevel5a',
			'transliteratedCity' => 'addresslevel5a',
			'community' => 'addresslevel4a',
			'region' => 'addresslevel2a',
			'transliteratedRegion' => 'addresslevel2a',
			'country' => 'addresslevel1a',
			'phone_number' => 'phone',
			'email_address' => 'email',
		],
		'Partners' => [
			'title' => 'subject',
			'transliteratedTitle' => 'subject',
			'identifier' => 'vat_id',
			'tax_id_number' => 'vat_id',
			'transliteratedSector' => 'siccode',
			'address' => 'addresslevel8a',
			'transliteratedAddress' => 'addresslevel8a',
			'postal_code' => 'addresslevel7a',
			'city' => 'addresslevel5a',
			'transliteratedCity' => 'addresslevel5a',
			'community' => 'addresslevel4a',
			'region' => 'addresslevel2a',
			'transliteratedRegion' => 'addresslevel2a',
			'country' => 'addresslevel1a',
			'email_address' => 'email',
		],
		'Competition' => [
			'title' => 'subject',
			'transliteratedTitle' => 'subject',
			'identifier' => 'vat_id',
			'tax_id_number' => 'vat_id',
			'address' => 'addresslevel8a',
			'transliteratedAddress' => 'addresslevel8a',
			'postal_code' => 'addresslevel7a',
			'city' => 'addresslevel5a',
			'transliteratedCity' => 'addresslevel5a',
			'community' => 'addresslevel4a',
			'region' => 'addresslevel2a',
			'transliteratedRegion' => 'addresslevel2a',
			'country' => 'addresslevel1a',
			'email_address' => 'email',
		],
	];

	/** {@inheritdoc} */
	private $url = 'https://api.vatify.eu/v1/';

	/** {@inheritdoc} */
	public $settingsFields = [
		'access_key' => ['required' => 1, 'purifyType' => 'Text', 'label' => 'LBL_ACCESS_KEY'],
		'client_id' => ['required' => 1, 'purifyType' => 'Text', 'label' => 'LBL_CLIENT_ID'],
	];
	/** @var string Access Key. */
	private $accessKey;
	/** @var string Client ID. */
	private $clientId;
	/** @var string Bearer Token. */
	private $bearerToken;

	/** {@inheritdoc} */
	public function isActive(): bool
	{
		return parent::isActive() && ($params = $this->getParams()) && !empty($params['access_key'] && !empty($params['client_id']));
	}

	/** {@inheritdoc} */
	public function search(): array
	{
		$country = $this->request->getByType('country', 'Text');
		$vatNumber = str_replace([' ', ',', '.', '-'], '', $this->request->getByType('vatNumber', 'Text'));
		$params = [];

		if (!$this->isActive() || empty($country) || empty($vatNumber)) {
			return [];
		}
		$this->loadCredentials();
		$this->getBearerToken();
		$params['country'] = $country;
		$params['identifier'] = $vatNumber;

		$this->getDataFromApi($params);
		$this->loadData();
		return $this->response;
	}

	/**
	 * Function fetching company data by VAT ID and Country.
	 *
	 * @param array $params
	 *
	 * @return void
	 */
	private function getDataFromApi(array $params): void
	{
		$response = [];
		$options = ['headers' => ['Authorization' => 'Bearer ' . $this->bearerToken]];
		$link = '';
		try {
			$client = \App\RequestHttp::getClient();
			$response = \App\Json::decode($client->get($this->url . 'query?' . http_build_query($params), $options)->getBody()->getContents());
		} catch (\GuzzleHttp\Exception\ClientException $e) {
			\App\Log::warning($e->getMessage(), 'RecordCollectors');
			$this->response['error'] = $e->getResponse()->getReasonPhrase();
			return;
		}
		if ('IN_PROGRESS' === $response['query']['status']) {
			$link = $response['query']['links'][0]['href'];
		} else {
			return;
		}
		sleep(5);
		try {
			$response = \App\Json::decode($client->get($link, $options)->getBody()->getContents());
		} catch (\GuzzleHttp\Exception\ClientException $e) {
			\App\Log::warning($e->getMessage(), 'RecordCollectors');
			$this->response['error'] = $e->getResponse()->getReasonPhrase();
			return;
		}
		if ('FINISHED' === $response['query']['status']) {
			$this->data = $this->parseData($response['result']['items'][0]['data']);
		} else {
			$this->data = [];
		}
	}

	/**
	 * Function parsing data to fields from API.
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	private function parseData(array $data): array
	{
		return \App\Utils::flattenKeys($data, 'ucfirst');
	}

	/**
	 * Function setup Api Key.
	 *
	 * @return void
	 */
	private function loadCredentials(): void
	{
		if (($params = $this->getParams()) && !empty($params['client_id'] && !empty($params['access_key']))) {
			$this->clientId = $params['client_id'];
			$this->accessKey = $params['access_key'];
		}
	}

	/**
	 * Function fetching Bearer Token.
	 *
	 * @return void
	 */
	private function getBearerToken(): void
	{
		try {
			$response = \App\RequestHttp::getClient()->post($this->url . 'oauth2/token', [
				'headers' => [
					'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->accessKey)
				],
				\GuzzleHttp\RequestOptions::JSON => [
					'grant_type' => 'client_credentials'
				]
			]);
			if (200 === $response->getStatusCode()) {
				$response = \App\Json::decode($response->getBody()->getContents());
			}
		} catch (\GuzzleHttp\Exception\ClientException $e) {
			\App\Log::warning($e->getMessage(), 'RecordCollectors');
			$this->response['error'] = $e->getResponse()->getReasonPhrase();
			return;
		}
		$this->bearerToken = $response['access_token'] ?? null;
		if (empty($this->bearerToken)) {
			$this->response['error'] = \App\Language::translate('LBL_VATIFY_EU_NO_AUTH', 'Other.RecordCollector');
		}
	}
}
