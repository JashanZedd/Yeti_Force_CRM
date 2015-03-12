<?php
/*+***********************************************************************************************************************************
 * The contents of this file are subject to the YetiForce Public License Version 1.1 (the "License"); you may not use this file except
 * in compliance with the License.
 * Software distributed under the License is distributed on an "AS IS" basis, WITHOUT WARRANTY OF ANY KIND, either express or implied.
 * See the License for the specific language governing rights and limitations under the License.
 * The Original Code is YetiForce.
 * The Initial Developer of the Original Code is YetiForce. Portions created by YetiForce are Copyright (C) www.yetiforce.com. 
 * All Rights Reserved.
 *************************************************************************************************************************************/

class Calculations_DetailView_Model extends Inventory_DetailView_Model {
	public function getDetailViewLinks($linkParams) {
		$linkModelList = parent::getDetailViewLinks($linkParams);
		$currentUserModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		$recordModel = $this->getRecord();
		$moduleName = $recordModel->getmoduleName();
        foreach ($linkModelList as $kaytab => $linktab) {
			foreach ($linktab as $kay => $link) {
				if($link->linklabel == 'LBL_EXPORT_TO_PDF' || $link->linklabel == 'LBL_SEND_MAIL_PDF'){
					unset($linkModelList[$kaytab][$kay]);
				}
			}
        }
		$quotesModuleModel = Vtiger_Module_Model::getInstance('Quotes');
		if($currentUserModel->hasModuleActionPermission($quotesModuleModel->getId(), 'EditView')) {
			$basicActionLink = array(
				'linktype' => 'DETAILVIEWBASIC',
				'linklabel' => vtranslate('LBL_GENERATE_QUOTES', 'Quotes'),
				'linkurl' => "index.php?module=".$quotesModuleModel->getName()."&view=".$quotesModuleModel->getEditViewName()."&calculation_id=".$recordModel->getId(),
				'linkicon' => 'icon-repeat icon-white',
				'linkclass' => 'btn-success',
			);
			$linkModelList['DETAILVIEWBASIC'][] = Vtiger_Link_Model::getInstanceFromValues($basicActionLink);
		}	
		return $linkModelList;
	}
}