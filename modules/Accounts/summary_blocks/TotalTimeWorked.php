<?php
class TotalTimeWorked{
	public $name = 'Total time worked';
	public $sequence = 6;
	public $reference = 'OSSTimeControl';
	
    public function process( $instance ) {
		$adb = PearDatabase::getInstance();
		$timecontrol ='SELECT SUM(sum_time) as sum FROM vtiger_osstimecontrol
				INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid=vtiger_osstimecontrol.osstimecontrolid
				WHERE vtiger_crmentity.deleted=0 AND  vtiger_osstimecontrol.link = ? AND osstimecontrol_status = ?';
		$result_timecontrol = $adb->pquery($timecontrol, array($instance->getId(), 'Accepted'));
		$decimalTimeFormat = Vtiger_Functions::decimalTimeFormat( $adb->query_result($result_timecontrol, 0, 'sum') );
		return $decimalTimeFormat['short'];
    }
}
