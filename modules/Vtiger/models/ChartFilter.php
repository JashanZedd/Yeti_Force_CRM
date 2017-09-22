<?php
/**
 * Model widget chart with a filter
 * @package YetiForce.Model
 * @copyright YetiForce Sp. z o.o.
 * @license YetiForce Public License 2.0 (licenses/License.html or yetiforce.com)
 * @author Tomasz Kur <t.kur@yetiforce.com>
 */

/**
 * Widget chart model with a filter
 */
class Vtiger_ChartFilter_Model extends Vtiger_Widget_Model
{

	/**
	 * Widget model
	 * @var \Vtiger_Widget_Model
	 */
	private $widgetModel;

	/**
	 * Extra data
	 * @var array
	 */
	private $extraData;

	/**
	 * Target module model
	 * @var \Vtiger_Module_Model
	 */
	private $targetModuleModel;

	/**
	 * Url search params
	 * @var array
	 */
	private $searchParams = [];

	/**
	 * Owners list
	 * @var array
	 */
	private $owners = [];

	/**
	 * Get instance
	 * @param int $linkId
	 * @param int $userId
	 * @return \self
	 */
	public static function getInstance($linkId = 0, $userId = 0)
	{
		return new self();
	}

	/**
	 * Get type
	 * @return string
	 */
	public function getType()
	{
		return $this->extraData['chartType'];
	}

	/**
	 * Get chart data
	 * @return array
	 */
	public function getChartData()
	{
		$charType = $this->getType();
		$charType = 'getData' . ucwords(strtolower($charType));
		if (method_exists($this, $charType)) {
			return $this->$charType();
		}
		return [];
	}

	/**
	 * Get horizontal chart data
	 * @return array
	 */
	protected function getDataHorizontal()
	{
		return $this->getDataBarchat();
	}

	/**
	 * Get line chart data
	 * @return array
	 */
	protected function getDataLine()
	{
		return $this->getDataBarchat();
	}

	/**
	 * Get bar chart data
	 * @return array
	 */
	protected function getDataBarchat()
	{
		$groupData = $this->getRows();
		uasort($groupData, function($first, $second) {
			if ($first['count'] == $second['count']) {
				return 0;
			}
			return ($first['count'] < $second['count']) ? 1 : -1;
		});
		$data = [];
		foreach ($groupData as $fieldName => $value) {
			$data[] = [$value['count'], $fieldName, $value['link']];
		}
		return $data;
	}

	/**
	 * Get funnel chart data
	 * @return array
	 */
	protected function getDataFunnel()
	{
		if (empty($this->extraData['sector'])) {
			$groupData = $this->getRows();
		} else {
			$groupData = $this->getRowsFunnel();
		}
		uasort($groupData, function($first, $second) {
			if ($first['count'] == $second['count']) {
				return 0;
			}
			return ($first['count'] < $second['count']) ? 1 : -1;
		});
		$data = [];
		foreach ($groupData as $fieldName => $value) {
			$data[] = [$fieldName, $value['count'], $value['link']];
		}

		return $data;
	}

	/**
	 * Get pie chart data
	 * @return array
	 */
	protected function getDataPie()
	{
		$data = [];
		foreach ($this->getRows() as $fieldName => $value) {
			$data[] = ['last_name' => $fieldName, 'id' => $value['count'], '2' => $value['link']];
		}
		return $data;
	}

	/**
	 * Get donut chart data
	 * @return array
	 */
	protected function getDataDonut()
	{
		$data = [];
		foreach ($this->getRows() as $fieldName => $value) {
			$data[] = ['last_name' => $fieldName, 'id' => $value['count'], '2' => $value['link']];
		}
		return $data;
	}

	/**
	 * Get axis chart data
	 * @return array
	 */
	public function getDataAxis()
	{
		$data = [];
		foreach ($this->getRows() as $fieldName => $value) {
			$data[] = ['last_name' => $fieldName, 'id' => $value['count'], '2' => $value['link']];
		}
		return $data;
	}

	/**
	 * Get area chart data
	 * @return array
	 */
	public function getDataArea()
	{
		$data = [];
		foreach ($this->getRows() as $fieldName => $value) {
			$data[] = ['last_name' => $fieldName, 'id' => $value['count'], '2' => $value['link']];
		}
		return $data;
	}

	/**
	 * Get divided bar chart data
	 * @return array
	 */
	public function getDataBardivided()
	{
		$data = [];
		foreach ($this->getRowsDivided() as $fieldName => $value) {
			$data[] = ['last_name' => $fieldName, 'id' => $value['count'], '2' => $value['link']];
		}
		return $data;
	}

	/**
	 * Get rows for all chart
	 * @return array
	 */
	protected function getRows()
	{
		$filterId = $this->widgetModel->get('filterid');
		$showOwnerFilter = !empty($this->extraData['showOwnerFilter']);
		$groupFieldModel = Vtiger_Field_Model::getInstance($this->extraData['groupField'], $this->getTargetModuleModel());
		$fieldName = $groupFieldModel->getFieldName();
		$dataReader = $this->getQuery()->createCommand()->query();
		$groupData = [];
		while ($row = $dataReader->read()) {
			if (!empty($row[$fieldName])) {
				$displayValue = $groupFieldModel->getDisplayValue($row[$fieldName], false, false, true);
				if (!isset($groupData[$displayValue]['count'])) {
					$groupData[$displayValue]['count'] = 1;
				} else {
					$groupData[$displayValue]['count'] ++;
				}
				if ($showOwnerFilter) {
					$this->owners[] = $row['assigned_user_id'];
				}
				if (!isset($groupData[$displayValue]['link'])) {
					$searchParams = array_merge($this->searchParams, [[$fieldName, 'e', $row[$fieldName]]]);
					$groupData[$displayValue]['link'] = $this->getTargetModuleModel()->getListViewUrl() . "&viewname=$filterId&search_params=" . App\Json::encode([$searchParams]);
				}
			}
		}
		return $groupData;
	}

	/**
	 * Get rows for divided field chart
	 * @return array
	 */
	protected function getRowsDivided()
	{
		$filterId = $this->widgetModel->get('filterid');
		$showOwnerFilter = !empty($this->extraData['showOwnerFilter']);
		$groupFieldModel = Vtiger_Field_Model::getInstance($this->extraData['groupField'], $this->getTargetModuleModel());
		$fieldName = $groupFieldModel->getFieldName();
		echo $this->getQuery()->createCommand()->getRawSql();
		$dataReader = $this->getQuery()->createCommand()->query();
		$groupData = [];
		while ($row = $dataReader->read()) {
			if (!empty($row[$fieldName])) {
				$displayValue = $groupFieldModel->getDisplayValue($row[$fieldName], false, false, true);
				if (!isset($groupData[$displayValue]['count'])) {
					$groupData[$displayValue]['count'] = 1;
				} else {
					$groupData[$displayValue]['count'] ++;
				}
				if ($showOwnerFilter) {
					$this->owners[] = $row['assigned_user_id'];
				}
				if (!isset($groupData[$displayValue]['link'])) {
					$searchParams = array_merge($this->searchParams, [[$fieldName, 'e', $row[$fieldName]]]);
					$groupData[$displayValue]['link'] = $this->getTargetModuleModel()->getListViewUrl() . "&viewname=$filterId&search_params=" . App\Json::encode([$searchParams]);
				}
			}
		}
		return $groupData;
	}

	/**
	 * Get chart query
	 * @return \App\Db\Query
	 */
	protected function getQuery()
	{
		$filterId = $this->widgetModel->get('filterid');
		$groupField = $this->extraData['groupField'];
		$queryGenerator = new \App\QueryGenerator($this->getTargetModule());
		$queryGenerator->initForCustomViewById($filterId);
		$queryGenerator->setField($groupField);
		$query = $queryGenerator->createQuery();
		if ($this->has('time') && !empty($this->extraData['timeRange'])) {
			$time = $this->get('time');
			$timeFieldModel = Vtiger_Field_Model::getInstance($this->extraData['timeRange'], $this->getTargetModuleModel());
			$tableAndColumnName = $timeFieldModel->getTableName() . '.' . $timeFieldModel->getColumnName();
			$query->andWhere([
				'and',
				['>=', $tableAndColumnName, Vtiger_Date_UIType::getDBInsertedValue($time['start'])],
				['<=', $tableAndColumnName, Vtiger_Date_UIType::getDBInsertedValue($time['end'])]
			]);
			$this->searchParams[] = [$timeFieldModel->getFieldName(), 'bw', $time['start'] . ',' . $time['end']];
		}
		if (!empty($this->extraData['showOwnerFilter'])) {
			$queryGenerator->setField('assigned_user_id');
		}
		return $query;
	}

	/**
	 * Get rows for funnel chart
	 * @return array
	 */
	protected function getRowsFunnel()
	{
		$filterId = $this->widgetModel->get('filterid');
		$showOwnerFilter = !empty($this->extraData['showOwnerFilter']);
		$groupFieldModel = Vtiger_Field_Model::getInstance($this->extraData['groupField'], $this->getTargetModuleModel());
		$fieldName = $groupFieldModel->getFieldName();
		$count = $groupData = [];
		$sectors = $this->extraData['sector'];
		$dataReader = $this->getQuery()->createCommand()->query();
		while ($row = $dataReader->read()) {
			$sectorId = $this->getSector($sectors, $row[$fieldName]);
			if ($showOwnerFilter) {
				$this->owners[] = $row['assigned_user_id'];
			}
			if ($sectorId !== false) {
				if (!isset($count[$sectorId])) {
					$count[$sectorId] = 1;
				} else {
					$count[$sectorId] ++;
				}
			}
		}
		foreach ($sectors as $sectorId => &$sectorValue) {
			$displayValue = $groupFieldModel->getDisplayValue($sectorValue);
			$displayValue .= ' - (' . (int) $count[$sectorId] . ')';
			$groupData[$displayValue]['count'] = (int) $sectorValue;
			$searchParams = array_merge($this->searchParams, [[$fieldName, 'm', $sectorValue]]);
			if ($sectorId != 0) {
				$searchParams[] = [$fieldName, 'g', $sectors[$sectorId - 1]];
			}
			$groupData[$displayValue]['link'] = $this->getTargetModuleModel()->getListViewUrl() . "&viewname=$filterId&search_params=" . App\Json::encode([$searchParams]);
		}
		return $groupData;
	}

	/**
	 * Get sector
	 * @param array $sectors
	 * @param int $value
	 * @return int
	 */
	protected function getSector($sectors, $value)
	{
		$sectorId = false;
		foreach ($sectors as $key => $sector) {
			if ($value <= $sector) {
				$sectorId = $key;
				break;
			}
		}
		return $sectorId;
	}

	/**
	 * Get owners list from result data
	 * @return type
	 */
	public function getRowsOwners()
	{
		$owners = [];
		foreach (array_unique($this->owners) as $ownerId) {
			$owners[$ownerId] = App\Fields\Owner::getLabel($ownerId);
		}
		return $owners;
	}

	/**
	 * Get extra data
	 * @param string $key
	 * @return mixed
	 */
	public function getExtraData($key)
	{
		return isset($this->extraData[$key]) ? $this->extraData[$key] : null;
	}

	/**
	 * Set widget model
	 * @param \Vtiger_Widget_Model $widgetModel
	 * @throws Exception
	 */
	public function setWidgetModel($widgetModel)
	{
		$this->widgetModel = $widgetModel;
		$this->extraData = $this->widgetModel->get('data');

		// Decode data if not done already.
		if (is_string($this->extraData)) {
			$this->extraData = \App\Json::decode(App\Purifier::decodeHtml($this->extraData));
		}
		if ($this->extraData === null) {
			throw new \App\Exceptions\AppException('Invalid data');
		}
	}

	/**
	 * Function to check if chart should be colored
	 * @return boolean
	 */
	public function isColor()
	{
		return $this->extraData['color'];
	}

	/**
	 * Get target module
	 * @return string
	 */
	public function getTargetModule()
	{
		return $this->extraData['module'];
	}

	/**
	 * Get target module model
	 * @return \Vtiger_Module_Model
	 */
	public function getTargetModuleModel()
	{
		if (!$this->targetModuleModel) {
			$this->targetModuleModel = Vtiger_Module_Model::getInstance($this->getTargetModule());
		}
		return $this->targetModuleModel;
	}

	/**
	 * Get title
	 * @param string $prefix
	 * @return string
	 */
	public function getTitle($prefix = '')
	{
		$title = $this->widgetModel->get('title');
		if (empty($title)) {
			$db = PearDatabase::getInstance();
			$suffix = '';
			$customviewrs = $db->pquery('SELECT viewname FROM vtiger_customview WHERE cvid=?', array($this->widgetModel->get('filterid')));
			if ($db->numRows($customviewrs)) {
				$customview = $db->fetchArray($customviewrs);
				$suffix = ' - ' . \App\Language::translate($customview['viewname'], $this->getTargetModule());
				if (!empty($this->extraData['groupField'])) {
					$groupFieldModel = Vtiger_Field_Model::getInstance($this->extraData['groupField'], $this->getTargetModuleModel());
					$suffix .= ' - ' . \App\Language::translate($groupFieldModel->getFieldLabel(), $this->getTargetModule());
				}
			}
			return $prefix . \App\Language::translate($this->getTargetModuleModel()->label, $this->getTargetModule()) . $suffix;
		}
		return $title;
	}

	/**
	 * Get total count url
	 * @return string
	 */
	public function getTotalCountURL()
	{
		return 'index.php?module=' . $this->getTargetModule() . '&action=Pagination&mode=getTotalCount&viewname=' . $this->widgetModel->get('filterid');
	}

	/**
	 * Get list view url
	 * @return string
	 */
	public function getListViewURL()
	{
		return 'index.php?module=' . $this->getTargetModule() . '&view=List&viewname=' . $this->widgetModel->get('filterid');
	}
}
