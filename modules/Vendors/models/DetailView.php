<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Vendors_DetailView_Model extends Vtiger_DetailView_Model {

	/**
	 * Function to get the detail view links (links and widgets)
	 * @param <array> $linkParams - parameters which will be used to calicaulate the params
	 * @return <array> - array of link models in the format as below
	 *                   array('linktype'=>list of link models);
	 */
	public function getDetailViewLinks($linkParams) {
		$currentUserModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		$linkModelList = parent::getDetailViewLinks($linkParams);
		$recordModel = $this->getRecord();
		$moduleModel = $this->getModule();
		$moduleName = $moduleModel->getName();
		$recordId = $recordModel->getId();
		
		$emailModuleModel = Vtiger_Module_Model::getInstance('OSSMail');
		if($currentUserModel->hasModulePermission($emailModuleModel->getId())) {
			$config = $emailModuleModel->getComposeParameters();
			$basicActionLink = array(
				'linktype' => 'DETAILVIEWBASIC',
				'linklabel' => '',
				'linkurl' => $emailModuleModel->getComposeUrl($moduleName, $recordId, 'Detail', $config['popup']),
				'linkicon' => 'glyphicon glyphicon-envelope',
				'linktarget' => $config['target'],
				'linkPopup' => $config['popup'],
				'title' => vtranslate('LBL_SEND_EMAIL')
			);
			$linkModelList['DETAILVIEWBASIC'][] = Vtiger_Link_Model::getInstanceFromValues($basicActionLink);
		}
		
		$purchaseOrderModuleModel = Vtiger_Module_Model::getInstance('PurchaseOrder');
		if($currentUserModel->hasModuleActionPermission($purchaseOrderModuleModel->getId(), 'EditView')) {
			$basicActionLink = array(
				'linktype' => 'DETAILVIEW',
				'linklabel' => '',
				'linkurl' => $recordModel->getCreatePurchaseOrderUrl(),
				'linkicon' => 'glyphicon glyphicon-list-alt',
				'title' => vtranslate('LBL_CREATE').' '.vtranslate($purchaseOrderModuleModel->getSingularLabelKey(), 'PurchaseOrder'),
			);
			$linkModelList['DETAILVIEW'][] = Vtiger_Link_Model::getInstanceFromValues($basicActionLink);
		}

		return $linkModelList;
	}
		
}
