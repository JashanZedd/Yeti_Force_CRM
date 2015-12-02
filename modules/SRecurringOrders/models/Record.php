<?php

/**
 * Record Class for SRecurringOrders
 * @package YetiForce.Model
 * @license licenses/License.html
 * @author Radosław Skrzypczak <r.skrzypczak@yetiforce.com>
 */
class SRecurringOrders_Record_Model extends Vtiger_Record_Model
{

	/**
	 * Function to get modal view url for the record
	 * @return <String> - Record Detail View Url
	 */
	public function getModalUrl()
	{
		return 'index.php?module=' . $this->getModuleName() . '&view=Modal&record=' . $this->getId();
	}
}
