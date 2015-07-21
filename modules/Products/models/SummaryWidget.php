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
class Products_SummaryWidget_Model{
	public static function getCleanInstance(){
		$instance = new self();
		return $instance;
	}
	
	function getProductsServices(Vtiger_Request $request) {
		$fromModule = $request->get('fromModule');
		$record = $request->get('record');
		$mod = $request->get('mod');
		if(!in_array($mod, ['Products','Services']))
			die('Not supported Module');
		
		$db = PearDatabase::getInstance();

		$limit = 10;
		$params = [];
		if(!empty($request->get('limit'))){
			$limit = $request->get('limit');
		}

		$currentUser = Users_Record_Model::getCurrentUserModel();
		$instance = CRMEntity::getInstance($mod);
		$securityParameter = $instance->getUserAccessConditionsQuerySR($mod, $currentUser);
		
		if($mod == 'Products'){
			$sql = 'SELECT vtiger_products.productid, vtiger_products.pscategory, vtiger_products.productname, vtiger_crmentity.smownerid, vtiger_crmentity.shownerid '
					. 'FROM vtiger_products '
					. 'INNER JOIN vtiger_crmentity ON vtiger_products.productid = vtiger_crmentity.crmid '
					. 'INNER JOIN vtiger_seproductsrel ON vtiger_products.productid = vtiger_seproductsrel.productid '
					. 'LEFT JOIN vtiger_users ON vtiger_crmentity.smownerid = vtiger_users.id '
					. 'LEFT JOIN vtiger_groups ON vtiger_crmentity.smownerid = vtiger_groups.groupid '
					. 'WHERE vtiger_crmentity.deleted=0 AND vtiger_products.productid > 0 AND vtiger_seproductsrel.setype = ? AND vtiger_seproductsrel.crmid = ?';
			$params[] = $fromModule;
			$params[] = $record;
		}elseif($mod == 'Services'){
			$sql = 'SELECT vtiger_service.serviceid, vtiger_service.pscategory, vtiger_service.servicename, vtiger_crmentity.smownerid, vtiger_crmentity.shownerid '
					. 'FROM vtiger_service '
					. 'INNER JOIN vtiger_crmentity ON vtiger_service.serviceid = vtiger_crmentity.crmid '
					. 'INNER JOIN vtiger_crmentityrel ON (vtiger_crmentityrel.relcrmid = vtiger_crmentity.crmid OR vtiger_crmentityrel.crmid = vtiger_crmentity.crmid)'
					. 'LEFT JOIN vtiger_users ON vtiger_crmentity.smownerid = vtiger_users.id '
					. 'LEFT JOIN vtiger_groups ON vtiger_crmentity.smownerid = vtiger_groups.groupid '
					. 'WHERE vtiger_crmentity.deleted=0 AND vtiger_service.serviceid > 0 AND (vtiger_crmentityrel.crmid IN (?) OR vtiger_crmentityrel.relcrmid IN (?))';
			$params[] = $record;
			$params[] = $record;
		}
		
		if ($securityParameter != '')
			$sql.= $securityParameter;

		$sql.= ' LIMIT '.$limit;
		$result = $db->pquery($sql, $params);
		$returnData = array();
		for($i=0; $i<$db->num_rows($result); $i++) {
			$row = $db->query_result_rowdata($result, $i);
			$shownerid = [];
			$row['smownerid'] = Vtiger_Functions::getOwnerRecordLabel($row['smownerid']);
			if($row['shownerid'] != ''){
				foreach (explode(",", $row['shownerid']) as $value) {
					$shownerid[] = Vtiger_Functions::getOwnerRecordLabel($value);
				}
			}
			$row['shownerid'] = $shownerid;
			$returnData[] = $row;
		}
		$showMore = (int)$limit == count($returnData)? 1:0;
		return ['data' => $returnData, 'showMore' => $showMore];
	}
}
