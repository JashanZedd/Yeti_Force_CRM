<?php

/**
 * YetiForce product Modal.
 *
 * @package   View
 *
 * @copyright YetiForce Sp. z o.o
 * @license   YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Tomasz Poradzewski <t.poradzewski@yetiforce.com>
 * @author    Mariusz Krzaczkowski <m.krzaczkowski@yetiforce.com>
 */

/**
 * Offline registration modal view class.
 */
class Settings_YetiForce_BuyModal_View extends \App\Controller\ModalSettings
{
	/**
	 * {@inheritdoc}
	 */
	public $successBtn = 'LBL_SHOP_PROCEED_TO_CHECKOUT';
	/**
	 * {@inheritdoc}
	 */
	public $successBtnIcon = 'far fa-credit-card';
	/**
	 * {@inheritdoc}
	 */
	public $footerClass = 'px-md-5';
	/**
	 * Modal size.
	 *
	 * @var string
	 */
	public $headerClass = 'modal-header-xl';
	/**
	 * {@inheritdoc}
	 */
	public function preProcessAjax(App\Request $request)
	{
		$qualifiedModuleName = $request->getModule(false);
		$this->modalIcon = 'yfi-marketplace';
		$this->pageTitle = \App\Language::translate('LBL_BUY', $qualifiedModuleName);
		parent::preProcessAjax($request);
	}

	/**
	 * {@inheritdoc}
	 */
	public function process(App\Request $request)
	{
		$viewer = $this->getViewer($request);
		$qualifiedModuleName = $request->getModule(false);
		$department = $request->isEmpty('department') ? '' : $request->getByType('department');
		$product = \App\YetiForce\Shop::getProduct($request->getByType('product'), $department);
		$companies = [];
		foreach (\App\Company::getAll() as $key => $row) {
			if (1 === (int) $row['type']) {
				$companies = $row;
			}
		}
		$recordModel = $formFields = [];
		if ($companies) {
			$recordModel = Settings_Companies_Record_Model::getInstance($companies['id'])->set('source', $qualifiedModuleName);
			$formFields = array_filter(Settings_Companies_Module_Model::getFormFields(), function ($key) {
				return isset($key['paymentData']);
			});
		} else {
			$this->successBtn = '';
		}
		$viewer->assign('MODULE', $qualifiedModuleName);
		$viewer->assign('PRODUCT', $product);
		$viewer->assign('VARIABLE_PAYMENTS', \App\YetiForce\Shop::getVariablePayments());
		$viewer->assign('VARIABLE_PRODUCT', $product->getVariable());
		$viewer->assign('PAYPAL_URL', \App\YetiForce\Shop::getPaypalUrl());
		$viewer->assign('COMPANY_DATA', $companies);
		$viewer->assign('RECORD', $recordModel);
		$viewer->assign('FORM_FIELDS', $formFields);
		$viewer->view('BuyModal.tpl', $qualifiedModuleName);
	}
}
