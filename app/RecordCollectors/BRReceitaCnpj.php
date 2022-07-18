<?php
/**
 * The Brazilian National Registry of Legal Entities API file.
 *
 * @package App
 *
 * @see https://developers.receitaws.com.br/#/operations/queryCNPJFree
 *
 * @copyright YetiForce S.A.
 * @license   YetiForce Public License 5.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Sławomir Rembiesa <s.rembiesa@yetiforce.com>
 */

namespace App\RecordCollectors;

/**
 * The Brazilian National Registry of Legal Entities API class.
 */
class BRReceitaCnpj extends Base
{
	/** {@inheritdoc} */
	protected static $allowedModules = ['Accounts', 'Leads', 'Vendors', 'Partners', 'Competition'];

	/** {@inheritdoc} */
	public $icon = 'fas fa-futbol';

	/** {@inheritdoc} */
	public $label = 'LBL_BR_RECITACNPJ';

	/** {@inheritdoc} */
	public $displayType = 'FillFields';

	/** {@inheritdoc} */
	public $description = 'LBL_BR_RECITACNPJ_DESC';

	/** {@inheritdoc} */
	public $docUrl = 'https://developers.receitaws.com.br/#/operations/queryCNPJFree';

	/** {@inheritdoc} */
	protected $fields = [
		'cnpj' => [
			'labelModule' => 'Other.RecordCollector',
			'label' => 'LBL_BR_RECITACNPJ_NUMBER',
			'typeofdata' => 'V~O',
		]
	];

	/** {@inheritdoc} */
	protected $modulesFieldsMap = [
		'Accounts' => [
			'cnpj' => 'registration_number_1',
		],
		'Leads' => [
			'cnpj' => 'registration_number_1',
		],
		'Vendors' => [
			'cnpj' => 'registration_number_1',
		]
	];

	/** {@inheritdoc} */
	public $formFieldsToRecordMap = [
		'Accounts' => [
			'nome' => 'accountname',
			'fantasia' => 'accountname',
			'email' => 'email1',
			'telefone' => 'phone',
			'capital_social' => 'annual_revenue',
			'atividade_principal0Code' => 'siccode',
			'cnpj' => 'registration_number_1',
			'numero' => 'buildingnumbera',
			'logradouro' => 'addresslevel8a',
			'cep' => 'addresslevel7a',
			'municipio' => 'addresslevel5a',
			'bairro' => 'addresslevel4a',
		],
		'Leads' => [
			'nome' => 'company',
			'fantasia' => 'company',
			'email' => 'email',
			'telefone' => 'phone',
			'cnpj' => 'registration_number_1',
			'numero' => 'buildingnumbera',
			'logradouro' => 'addresslevel8a',
			'cep' => 'addresslevel7a',
			'municipio' => 'addresslevel5a',
			'bairro' => 'addresslevel4a',
		],
		'Partners' => [
			'nome' => 'subject',
			'fantasia' => 'subject',
			'email' => 'email',
			'numero' => 'buildingnumbera',
			'logradouro' => 'addresslevel8a',
			'cep' => 'addresslevel7a',
			'municipio' => 'addresslevel5a',
			'bairro' => 'addresslevel4a',
		],
		'Vendors' => [
			'nome' => 'vendorname',
			'fantasia' => 'vendorname',
			'email' => 'email',
			'telefone' => 'phone',
			'cnpj' => 'registration_number_1',
			'numero' => 'buildingnumbera',
			'logradouro' => 'addresslevel8a',
			'cep' => 'addresslevel7a',
			'municipio' => 'addresslevel5a',
			'bairro' => 'addresslevel4a',
		],
		'Competition' => [
			'nome' => 'subject',
			'fantasia' => 'subject',
			'email' => 'email',
			'numero' => 'buildingnumbera',
			'logradouro' => 'addresslevel8a',
			'cep' => 'addresslevel7a',
			'municipio' => 'addresslevel5a',
			'bairro' => 'addresslevel4a',
		],
	];

	/** @var string CNJP sever address */
	private $url = 'https://receitaws.com.br/v1/cnpj/';

	/** @var mixed api key */
	private $apiKey = null;

	/** {@inheritdoc} */
	public $settingsFields = [
		'api_key' => ['required' => 0, 'purifyType' => 'Text', 'label' => 'LBL_API_KEY_OPTIONAL'],
	];

	/** {@inheritdoc} */
	public function search(): array
	{
		$cnpj = str_replace([' ', '/', '.', '-'], '', $this->request->getByType('cnpj', 'Text'));
		if (!$this->isActive() || empty($cnpj)) {
			return [];
		}
		$this->getDataFromApi($cnpj);
		if (!isset($this->response['error'])) {
			$this->loadData();
		}
		return $this->response;
	}

	/**
	 * Function fetching company data by CNPJ number.
	 *
	 * @param string $cnpj
	 *
	 * @return void
	 */
	private function getDataFromApi(string $cnpj): void
	{
		try {
			$this->setApiKey();
			if ($this->apiKey) {
				$options = [
					'headers' => [
						'Authorization' => 'Bearer ' . $this->apiKey
					]
				];
			}
			$response = \App\RequestHttp::getClient()->get($this->url . $cnpj, $options ?? []);
			$data = $this->parseData(\App\Json::decode($response->getBody()->getContents()));
			if (isset($data['status']) && 'ERROR' === $data['status']) {
				$this->response['error'] = $data['message'];
				unset($this->data['fields']);
			} else {
				$this->data = $data;
			}
		} catch (\GuzzleHttp\Exception\ClientException $e) {
			\App\Log::warning($e->getMessage(), 'RecordCollectors');
			if (429 === $e->getCode()) {
				$this->response['error'] = \App\Language::translate('LBL_BR_RECITACNPJ_ERROR', 'Other.RecordCollector');
			} elseif ($e->getCode() > 400) {
				$this->response['error'] = $e->getResponse()->getReasonPhrase();
			} else {
				$this->response['error'] = $e->getMessage();
			}
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
	private function setApiKey(): void
	{
		if (($params = $this->getParams()) && !empty($params['api_key'])) {
			$this->apiKey = $params['api_key'];
		}
	}
}
