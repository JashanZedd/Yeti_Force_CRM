<?php
/**
 * Multi reference value cron
 * @package YetiForce.Cron
 * @license licenses/License.html
 * @author Mariusz Krzaczkowski <m.krzaczkowski@yetiforce.com>
 */
$adb = PearDatabase::getInstance();
$limit = AppConfig::performance('CRON_MAX_NUMERS_RECORD_PRIVILEGES_UPDATER');

$query = (new \App\db\Query())->select('crmid, setype')->from('vtiger_crmentity')->where(['users' => null])->limit($limit);
$dataReader = $query->createCommand()->query();
while ($row = $dataReader->read()) {
	\App\PrivilegeUpdater::update($row['crmid'], $row['setype']);
	$limit--;
	if (0 === $limit) {
		return;
	}
}
$result = $adb->query(sprintf('SELECT * FROM u_yf_crmentity_search_label WHERE `userid` = \'\' LIMIT %s', $limit));
while ($row = $adb->getRow($result)) {
	\App\PrivilegeUpdater::updateSearch($row['crmid'], $row['setype']);
	$limit--;
	if (0 === $limit) {
		return;
	}
}
$resultUpdater = $adb->query(sprintf('SELECT * FROM s_yf_privileges_updater ORDER BY `priority` DESC LIMIT %s', $limit));
while ($row = $adb->getRow($resultUpdater)) {
	$crmid = $row['crmid'];
	if (0 === $row['type']) {
		\App\PrivilegeUpdater::update($crmid, $row['module']);
		$limit--;
		if (0 === $limit) {
			return;
		}
	} else {
		$resultCrm = $adb->pquery(sprintf('SELECT crmid FROM vtiger_crmentity WHERE deleted=0 and setype =? && crmid > ? LIMIT %s', $limit), [$row['module'], $crmid]);
		while ($rowCrm = $adb->getRow($resultCrm)) {
			\App\PrivilegeUpdater::update($rowCrm['crmid'], $row['module']);
			$affected = $adb->update('s_yf_privileges_updater', ['crmid' => $rowCrm['crmid']], 'module =? && type =? && crmid =?', [$row['module'], 1, $crmid]);
			$crmid = $rowCrm['crmid'];
			$limit--;
			if (0 === $limit || $affected == 0) {
				return;
			}
		}
	}
	$adb->delete('s_yf_privileges_updater', 'module =? && type =? && crmid =?', [$row['module'], $row['type'], $crmid]);
}

