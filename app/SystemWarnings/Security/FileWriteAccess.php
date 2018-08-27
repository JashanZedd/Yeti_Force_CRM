<?php

namespace App\SystemWarnings\Security;

/**
 * Files write access system warnings class.
 *
 * @copyright YetiForce Sp. z o.o
 * @license   YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Sławomir Kłos <s.klos@yetiforce.com>
 */
class FileWriteAccess extends \App\SystemWarnings\Template
{
	protected $title = 'LBL_FILE_WRITE_ACCESS';
	protected $priority = 7;

	/**
	 * Checking whether there is a https connection.
	 */
	public function process()
	{
		$errors = \App\Utils\ConfReport::getAllErrors()['writableFilesAndFolders'] ?? [];
		if (empty($errors)) {
			$this->status = 1;
		} else {
			$this->status = 0;
		}
		if ($this->status === 0) {
			$errorsText = '<br><pre>';
			foreach ($errors as $key => $value) {
				$errorsText .= "\n{$key}";
			}
			$errorsText .= '</pre>';
			$this->link = 'https://yetiforce.com/en/implementer/installation-updates/103-web-server-requirements.html';
			$this->linkTitle = \App\Language::translate('BTN_CONFIGURE_WRITE_ACCESS', 'Settings:SystemWarnings');
			$this->description = \App\Language::translateArgs('LBL_MISSING_WRITE_ACCESS', 'Settings:SystemWarnings', '<a target="_blank" rel="noreferrer" href="' . \App\Language::translate('LBL_CONFIG_DOC_URL', 'Settings:SystemWarnings') . '"><u>' . \App\Language::translate('LBL_CONFIG_DOC_URL_LABEL', 'Settings:SystemWarnings') . '</u></a>', $errorsText);
		}
	}
}
