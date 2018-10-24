<?php

/**
 * Backup download file action class.
 *
 * @package Action
 *
 * @copyright YetiForce Sp. z o.o
 * @license YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author Arkadiusz Dudek <a.dudek@yetiforce.com>
 */
class Settings_Backup_DownloadFile_Action extends Settings_Vtiger_Index_Action
{
	/**
	 * {@inheritdoc}
	 */
	public function process(\App\Request $request)
	{
		$requestFilePath = $request->getByType('file', 'String');
		$filePath = \App\Utils\Backup::getBackupCatalogPath() . DIRECTORY_SEPARATOR . $requestFilePath;
		if (!\App\Utils\Backup::isAllowedFileDirectory($requestFilePath)) {
			throw new \App\Exceptions\NoPermittedForAdmin(\App\Language::translate('LBL_PERMISSION_DENIED'));
		}
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename=' . basename($filePath));
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Accept-Ranges: bytes');
		header('Content-Length: ' . filesize($filePath));
		readfile($filePath);
	}

	/**
	 * {@inheritdoc}
	 */
	public function validateRequest(\App\Request $request)
	{
		$request->validateReadAccess();
	}
}
