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
class Vtiger_Tree_UIType extends Vtiger_Base_UIType {
	public function isAjaxEditable() {
		return false;
	}
	/**
	 * Function to get the Template name for the current UI Type object
	 * @return <String> - Template Name
	 */
	public function getTemplateName() {
		return 'uitypes/Tree.tpl';
	}

	/**
	 * Function to get the Display Value, for the current field type with given DB Insert Value
	 * @param <Object> $value
	 * @return <Object>
	 */
	public function getDisplayValue($tree) {
		$template = $this->get('field')->getFieldParams();
		$adb = PearDatabase::getInstance();
		$result = $adb->pquery('SELECT * FROM vtiger_trees_templates_data WHERE templateid = ? AND tree = ?', array($template,$tree));
		$parentName = '';
		$module = $this->get('field')->getModuleName();
		if($adb->num_rows($result)) {
			if($adb->query_result_raw($result, 0, 'depth') > 0){
				$parenttrre = $adb->query_result_raw($result, 0, 'parenttrre');
				$cut = strlen('::'.$tree);
				$parenttrre = substr($parenttrre, 0, - $cut);
				$pieces = explode('::', $parenttrre);
				$parent = end($pieces);
				$result2 = $adb->pquery("SELECT name FROM vtiger_trees_templates_data WHERE templateid = ? AND tree = ?", array($template, $parent));
				$parentName = $adb->query_result_raw($result2, 0, 'name');
				
				$parentName = '(' . vtranslate($parentName, $module) . ') ';
			}
			return $parentName.vtranslate($adb->query_result($result, 0, 'name'), $module);
		}
		return false;
	}
	
	/**
	 * Function to get the display value in edit view
	 * @param reference record id
	 * @return link
	 */
	public function getEditViewDisplayValue($value) {
		return $this->getDisplayValue($value);
	}
	
    public function getListSearchTemplateName() {
        return 'uitypes/TreeFieldSearchView.tpl';
    }
	
	/**
	 * Function to get the all Values
	 * @param <Object> $value
	 * @return <Object>
	 */
	public function getAllValue() {
		$template = $this->get('field')->getFieldParams();
		$adb = PearDatabase::getInstance();
		$values = array();
		$result = $adb->pquery('SELECT * FROM vtiger_trees_templates_data WHERE templateid = ?', array($template));
		for($i = 0; $i < $adb->num_rows($result); $i++){
			$tree = $adb->query_result_raw($result, $i, 'tree');
			$parent = '';
			$parentName = '';
			if($adb->query_result_raw($result, $i, 'depth') > 0){
				$parenttrre = $adb->query_result_raw($result, $i, 'parenttrre');
				$cut = strlen('::'.$tree);
				$parenttrre = substr($parenttrre, 0, - $cut);
				$pieces = explode('::', $parenttrre);
				$parent = end($pieces);
				$result3 = $adb->pquery("SELECT name FROM vtiger_trees_templates_data WHERE templateid = ? AND tree = ?", array($template, $parent));
				$parentName = $adb->query_result_raw($result3, 0, 'name');
				$parentName = '(' . vtranslate($parentName, $module) . ') ';
			}
			$values[$adb->query_result_raw($result, $i, 'tree')] = array($parentName.vtranslate($adb->query_result_raw($result, $i, 'name'), $this->get('field')->getModuleName()),$parent);
		}
		return $values;
	}
}