<?php
/*
Return Description
------------------------
Info type: error, info, success
Info title: optional
Info text: mandatory
Type: 0 - notify
Type: 1 - show quick create mondal
*/
Class DataAccess_unique_value{
    var $config = true;
	
    public function process( $ModuleName,$ID,$record_form,$config ) {
		$db = PearDatabase::getInstance();
		$ModuleNameID = Vtiger_Functions::getModuleId($ModuleName);
		$sql_ext = '';
		$save_record1 = true;
		$save_record2 = true;
		$save_record = true;
		$type = 'info';
		$info = false;
		if($ID != 0 && $ID != '' && !array_key_exists($config['what1'],$record_form)){
			$Record_Model = Vtiger_Record_Model::getInstanceById($ID, $ModuleName);
			$value1 = $Record_Model->get($config['what1']);
		}else{
			if( array_key_exists($config['what1'],$record_form ) )
				$value1 = $record_form[$config['what1']];
		}
		
		if($ID != 0 && $ID != '' && !array_key_exists($config['what2'],$record_form)){
			$Record_Model = Vtiger_Record_Model::getInstanceById($ID, $ModuleName);
			$value2 = $Record_Model->get($config['what2']);
		}else{
			if( array_key_exists($config['what2'],$record_form ) )
				$value2 = $record_form[$config['what2']];
		}
		
		if(!is_array($config['where1']))
			$wheres1[] = $config['where1'];
		else
			$wheres1 = $config['where1'];
		if(!is_array($config['where2']))
			$wheres2[] = $config['where2'];
		else
			$wheres2 = $config['where2'];
		if($value1 != ''){
			foreach($wheres1 as $where){
				$where = explode('=', $where);
				$DestModuleName = Vtiger_Functions::getModuleName($where[2]);
				$ModuleInstance = CRMEntity::getInstance($DestModuleName);
				$tab_name_index = $ModuleInstance->tab_name_index;
				$index = $tab_name_index[$where[0]];
				$sql_param = array( $value1 );
				$sql_ext = '';
				$spacialCondition = '';
				$sqlSpecial = '';
				if($ModuleNameID == $where[2] && $ID != 0 && $ID != ''){
					$sql_param[] = $ID;
					$sql_ext = 'AND '.$index.' <> ?';
				}
				if($DestModuleName == 'Leads'){
					$spacialCondition = ' AND `converted` = 0';
					if('vtiger_crmentity' == $where[0]){
						$sqlSpecial = 'INNER JOIN vtiger_leaddetails ON vtiger_crmentity.crmid = vtiger_leaddetails.leadid ';
					}
				}
				$result = $db->pquery( "SELECT $index FROM {$where[0]} $sqlSpecial WHERE {$where[1]} = ? $sql_ext $spacialCondition;", $sql_param, true );
				for($i = 0; $i < $db->num_rows($result); $i++){
					$id = $db->query_result_raw($result, $i, $index);
					if(Vtiger_Functions::getCRMRecordType($id) == $DestModuleName){
						$save_record1 = false;
						$result = $db->pquery( "SELECT smownerid FROM vtiger_crmentity WHERE crmid = ?", array( $id ) ,true);
						$fieldlabel .= '<a target="_blank" href="index.php?module='.$DestModuleName.'&view=Detail&record='.$id.'">&bull; '.Vtiger_Functions::getCRMRecordLabel($id).'</a> ('.Vtiger_Functions::getOwnerRecordLabel( $db->query_result($result, 0, 'smownerid') ).'),';
					}
					
				}
			}
		}
		if($value2 != ''){
			foreach($wheres2 as $where){
				$where = explode('=', $where);
				$DestModuleName = Vtiger_Functions::getModuleName($where[2]);
				$ModuleInstance = CRMEntity::getInstance($DestModuleName);
				$tab_name_index = $ModuleInstance->tab_name_index;
				$index = $tab_name_index[$where[0]];
				$sql_param = array( $value1 );
				$sql_ext = '';
				$spacialCondition = '';
				$sqlSpecial = '';
				if($ModuleNameID == $where[2] && $ID != 0 && $ID != ''){
					$sql_param[] = $ID;
					$sql_ext = 'AND '.$index.' <> ?';
				}
				if($DestModuleName == 'Leads'){
					$spacialCondition = ' AND `converted` = 0';
					if('vtiger_crmentity' == $where[0]){
						$sqlSpecial = 'INNER JOIN vtiger_leaddetails ON vtiger_crmentity.crmid = vtiger_leaddetails.leadid ';
					}
				}
				$result = $db->pquery( "SELECT $index FROM {$where[0]} WHERE {$where[1]} = ? $sql_ext;", $sql_param, true );
				for($i = 0; $i < $db->num_rows($result); $i++){
					$id = $db->query_result_raw($result, $i, $index);
					if(Vtiger_Functions::getCRMRecordType($id) == $DestModuleName){
						$save_record2 = false;
						$result = $db->pquery( "SELECT smownerid FROM vtiger_crmentity WHERE crmid = ?", array( $id ) ,true);
						$fieldlabel .= '<a target="_blank" href="index.php?module='.$DestModuleName.'&view=Detail&record='.$id.'">&bull; '.Vtiger_Functions::getCRMRecordLabel($id).'</a> ('.Vtiger_Functions::getOwnerRecordLabel( $db->query_result($result, 0, 'smownerid') ).'),';
					}
				}
			}
		}
		if( $config['locksave'] == 0 && $ID == ''){
			$info = $config['info0'];
		}elseif( !$save_record1 && !$save_record2){
			$type = 'error';
			$save_record = false;
			$info = $config['info2'];
		}elseif( !$save_record1 || !$save_record2 ){
			$type = 'error';
			$save_record = false;
			$info = $config['info1'];
		}

		if(!$save_record || $info)
			return Array(
				'save_record'=>$save_record,
				'type'=>0,
				'info'=>Array(
					'title'=> vtranslate($info, 'DataAccess').' <br/ >'.trim($fieldlabel,','),
					'type'=> $type,
					'hide'=> false
				)
			);
		else
			return Array('save_record'=> true );
    }

    public function getConfig( $id,$module,$baseModule ) {
		$db = PearDatabase::getInstance();
		$result = $db->pquery( "SELECT * FROM vtiger_field LEFT JOIN vtiger_tab ON vtiger_tab.tabid = vtiger_field.tabid  WHERE vtiger_field.presence <> '1' AND vtiger_field.displaytype IN ('1','10') ORDER BY name", array() ,true);
		$fields = array();
		$ModuleFields = array();
		foreach($result->GetArray() as $row){
			array_push($fields, array( $row['fieldlabel'],$row['tablename'],$row['columnname'],$row['name'],$row['tabid'],$row['fieldname']) );
			if($row['name'] == $baseModule){
				array_push($ModuleFields, array($row['name'],$row['fieldname'],$row['fieldlabel']) );
			}
		}
		return Array('fields'=>$fields,'fields_mod'=>$ModuleFields );
    }
}