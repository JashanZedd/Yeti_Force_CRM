<?php
/**
 * Base mail scanner action file.
 *
 * @package App
 *
 * @copyright YetiForce S.A.
 * @license   YetiForce Public License 5.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Mariusz Krzaczkowski <m.krzaczkowski@yetiforce.com>
 * @author    Radosław Skrzypczak <r.skrzypczak@yetiforce.com>
 */

namespace App\Mail\ScannerAction;

use App\Mail\RecordFinder;

/**
 * Base mail scanner action class.
 */
class CreatedHelpDesk extends Base
{
	/** {@inheritdoc} */
	public static $priority = 5;

	/** {@inheritdoc} */
	public function process(): void
	{
		if ($this->checkExceptions('CreatedHelpDesk')) {
			return;
		}
		if (($prefix = RecordFinder::getRecordNumberFromString($this->message->get('subject'), 'HelpDesk')) && \App\Record::getIdByRecordNumber($prefix, 'HelpDesk')) {
			return;
		}
		$fromEmail = [$this->message->get('from_email')];
		$contactId = current(\App\Utils::flatten(RecordFinder::findByEmail($fromEmail, $this->message->getEmailsFields('Contacts'))));
		$parentId = current(\App\Utils::flatten(RecordFinder::findByEmail($fromEmail, $this->message->getEmailsFields('Accounts'))));
		if (!$parentId) {
			$parentId = current(\App\Utils::flatten(RecordFinder::findByEmail($fromEmail, $this->message->getEmailsFields('Vendors'))));
		}
		if (!$parentId && $contactId) {
			$parentId = \App\Record::getParentRecord($contactId, 'Contacts');
		}
		$recordModel = \Vtiger_Record_Model::getCleanInstance('HelpDesk');
		$this->loadServiceContracts($recordModel, $parentId);
		$recordModel->set('assigned_user_id', $this->message->getUserId());
		$recordModel->set('created_user_id', $this->message->getUserId());
		$recordModel->set('createdtime', $this->message->get('date'));
		$recordModel->setFromUserValue('ticket_title', \App\TextUtils::textTruncate($this->message->get('subject'), $recordModel->getField('ticket_title')->getMaxValue(), false));
		$recordModel->set('description', \App\TextUtils::htmlTruncate($this->message->get('body'), $recordModel->getField('description')->getMaxValue()));
		$recordModel->set('ticketstatus', \Config\Modules\OSSMailScanner::$helpdeskCreateDefaultStatus);
		if ($contactId) {
			$recordModel->ext['relations'][] = [
				'relatedModule' => 'Contacts',
				'relatedRecords' => [$contactId],
			];
		}
		if ($mailId = $this->message->getMailCrmId()) {
			$recordModel->ext['relations'][] = [
				'reverse' => true,
				'relatedModule' => 'OSSMailView',
				'relatedRecords' => [$mailId],
				'params' => $this->message->get('date'),
			];
		}
		$recordModel->save();
		$id = $recordModel->getId();
		$this->message->processData['CreatedHelpDesk'] = $id;
		if ($mailId) {
			$dbCommand = \App\Db::getInstance()->createCommand();
			$query = (new \App\Db\Query())->select(['documentsid'])->from('vtiger_ossmailview_files')->where(['ossmailviewid' => $mailId]);
			$dataReader = $query->createCommand()->query();
			while ($documentId = $dataReader->readColumn(0)) {
				$dbCommand->insert('vtiger_senotesrel', ['crmid' => $id, 'notesid' => $documentId])->execute();
			}
			$dataReader->close();
			unset($dataReader,$query, $dbCommand, $recordModel);
		}
	}

	/**
	 * Find service contracts and init data.
	 *
	 * @param Vtiger_Record_Model $recordModel
	 * @param int|bool            $parentId
	 *
	 * @return void
	 */
	private function loadServiceContracts(\Vtiger_Record_Model $recordModel, $parentId)
	{
		if (!$parentId) {
			return;
		}
		$recordModel->set('parent_id', $parentId);
		$queryGenerator = new \App\QueryGenerator('ServiceContracts');
		$queryGenerator->setFields(['id', 'contract_priority']);
		$queryGenerator->addNativeCondition(['vtiger_servicecontracts.sc_related_to' => $parentId]);
		$queryGenerator->permissions = false;
		$queryGenerator->addCondition('contract_status', 'In Progress', 'e');
		$dataReader = $queryGenerator->createQuery()->createCommand()->query();
		if (1 === $dataReader->count()) {
			$serviceContracts = $dataReader->read();
			$recordModel->set('servicecontractsid', $serviceContracts['id']);
			if (\App\Fields\Picklist::isExists('ticketpriorities', $serviceContracts['contract_priority'])) {
				$recordModel->set('ticketpriorities', $serviceContracts['contract_priority']);
			}
		}
		$dataReader->close();
		unset($dataReader, $queryGenerator);
	}
}
