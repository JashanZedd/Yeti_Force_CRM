<?php

/**
 * YetiForce product Modal
 *
 * @package   Settings
 *
 * @copyright YetiForce Sp. z o.o
 * @license   YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Tomasz Poradzewski <t.poradzewski@yetiforce.com>
 */

/**
 * Offline registration modal view class.
 */
class Settings_YetiForce_ProductModal_View extends \App\Controller\ModalSettings
{
	/**
	 * @inheritDoc
	 */
	public $successBtn = 'LBL_BUY';
	/**
	 * @inheritDoc
	 */
	public $modalSize = 'modal-full';

	/**
	 * Set modal title.
	 *
	 * @param \App\Request $request
	 */
	public function preProcessAjax(\App\Request $request)
	{
		$qualifiedModuleName = $request->getModule(false);
		$this->modalIcon = 'userIcon-Products';
		$this->pageTitle = \App\Language::translate('LBL_PRODUCT_PREVIEW', $qualifiedModuleName);
		parent::preProcessAjax($request);
	}

	/**
	 * Process user request.
	 *
	 * @param \App\Request $request
	 */
	public function process(\App\Request $request)
	{
		$qualifiedModuleName = $request->getModule(false);
		$productName = $request->getByType('product');
		$department = $request->isEmpty('department') ? '' : $request->getByType('department');
		$viewer = $this->getViewer($request);
		$viewer->assign('MODULE', $qualifiedModuleName);
		$viewer->assign('PRODUCT', \App\YetiForce\Shop::getProduct($productName, $department, \App\YetiForce\Shop::getConfig()));
		$viewer->view('ProductModal.tpl', $qualifiedModuleName);
	}
}
