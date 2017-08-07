<?php
/* +***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * *********************************************************************************** */

/*
 * Workflow Task Type Model Class
 */
require_once 'modules/com_vtiger_workflow/VTTaskManager.php';

/**
 * Settings Workflows TaskType Model
 */
class Settings_Workflows_TaskType_Model extends \App\Base
{

	/**
	 * Return record id
	 * @return int
	 */
	public function getId()
	{
		return $this->get('id');
	}

	/**
	 * Return task name
	 * @return string
	 */
	public function getName()
	{
		return $this->get('tasktypename');
	}

	/**
	 * Return task label
	 * @return string
	 */
	public function getLabel()
	{
		return $this->get('label');
	}

	/**
	 * Return template path
	 * @return string
	 */
	public function getTemplatePath()
	{
		$templatePath = vtemplate_path('Tasks/' . $this->getName() . '.tpl', 'Settings:Workflows');
		return $templatePath;
	}

	/**
	 * Return edit view url
	 * @return string
	 */
	public function getEditViewUrl()
	{
		return '?module=Workflows&parent=Settings&view=EditTask&type=' . $this->getName();
	}

	/**
	 * Create instance from class name
	 * @param VTTask $taskClass
	 * @return $this
	 */
	public static function getInstanceFromClassName($taskClass)
	{
		$row = (new \App\Db\Query())->from('com_vtiger_workflow_tasktypes')->where(['classname' => $taskClass])->one();
		$taskTypeObject = VTTaskType::getInstance($row);
		return self::getInstanceFromTaskTypeObject($taskTypeObject);
	}

	/**
	 * Return all tasks for module
	 * @param object $moduleModel
	 * @return array
	 */
	public static function getAllForModule($moduleModel)
	{
		$taskTypes = VTTaskType::getAll($moduleModel->getName());
		$taskTypeModels = [];
		foreach ($taskTypes as $taskTypeObject) {
			$taskTypeModels[] = self::getInstanceFromTaskTypeObject($taskTypeObject);
		}
		return $taskTypeModels;
	}

	/**
	 * Return task type instance
	 * @param string $taskType
	 * @return object
	 */
	public static function getInstance($taskType)
	{
		$taskTypeObject = VTTaskType::getInstanceFromTaskType($taskType);
		return self::getInstanceFromTaskTypeObject($taskTypeObject);
	}

	/**
	 * Return instance from task type object
	 * @param object $taskTypeObject
	 * @return \self
	 */
	public static function getInstanceFromTaskTypeObject($taskTypeObject)
	{
		return new self($taskTypeObject->data);
	}

	/**
	 * Return task base module object
	 * @return object
	 */
	public function getTaskBaseModule()
	{
		$taskTypeName = $this->get('tasktypename');
		switch ($taskTypeName) {
			case 'VTCreateTodoTask' : return Vtiger_Module_Model::getInstance('Calendar');
			case 'VTCreateEventTask' : return Vtiger_Module_Model::getInstance('Events');
		}
	}
}
