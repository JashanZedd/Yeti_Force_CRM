<?php
/**
 * UIType Image.
 *
 * @package UIType
 *
 * @copyright YetiForce S.A.
 * @license   YetiForce Public License 5.0 (licenses/LicenseEN.txt or yetiforce.com)
 */
/**
 * UIType Image Field Class.
 */
class Vtiger_Image_UIType extends Vtiger_MultiImage_UIType
{
	/** {@inheritdoc} */
	public const LIMIT = 1;

	/** {@inheritdoc} */
	public function getTilesDisplayValue($value, $record = false, $recordModel = false, $rawText = false)
	{
		return $this->getListViewDisplayValue($value, $record, $recordModel, $rawText);
	}
}
