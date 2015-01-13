<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
<<<<<<< HEAD
=======
 * VERSION YetiForceCRM: 1.1.0 RC
>>>>>>> 2cf0f3dedd551a30446a6b736cbb8cbd7453449b
 ************************************************************************************/
$languageStrings = array(
	'ERR_FAILED_TO_LOCK_MODULE'    => 'Не удалось заблокировать модуль для импорта. Пожалуйста, повторите попытку позже',
	'ERR_IMPORT_INTERRUPTED'       => 'Текущий импорт был прерван. Пожалуйста, повторите попытку позже.',
	'ERR_UNIMPORTED_RECORDS_EXIST' => 'Есть еще некоторые не импортированные записи в очереди, которые блокируют дальнейший импорт данных. <br>Очистите их, чтобы начать импорт снова',
	'ISO-8859-1' => 'ISO-8859-1',
	'LBL_AVAILABLE_FIELDS'         => 'Доступные поля' , 
	'LBL_CANCEL_IMPORT'            => 'Отменить импорт',
	'LBL_CHARACTER_ENCODING'       => 'Кодировка'          , 
	'LBL_CLEAR_DATA'               => 'Очистить данные',
	'LBL_CRM_FIELDS'               => 'Поля CRM',
	'LBL_DEFAULT_VALUE'            => 'Значение по умолчанию',
	'LBL_DELIMITER'                => 'Разделитель:'     , 
	'LBL_DETAILS'                  => 'Детали',
	'LBL_ERROR'                    => 'Ошибка', 
	'LBL_FILE_COLUMN_HEADER'       => 'Заголовок',
	'LBL_FILE_TYPE'                => 'Тип файла'                   , 
	'LBL_FILE_UPLOAD_FAILED'       => 'Неудачная загрузка файла',
	'LBL_FINISH_BUTTON_LABEL'      => 'Финиш'                  , 
	'LBL_HAS_HEADER'               => 'Заголовок:'         , 
	'LBL_IMPORT_BUTTON_LABEL'      => 'Импорт'                , 
	'LBL_IMPORT_CHANGE_UPLOAD_SIZE' => 'Измените размер загружаемого файла',
	'LBL_IMPORT_DIRECTORY_NOT_WRITABLE' => 'Директория не для записи',
	'LBL_IMPORT_ERROR_LARGE_FILE'  => 'Слишком большой файл',
	'LBL_IMPORT_FILE_COPY_FAILED'  => 'Неудачное копирование файла импорта',
	'LBL_IMPORT_MORE'              => 'Импортировать еще', 
	'LBL_IMPORT_SCHEDULED'         => 'Импорт запланирован',
	'LBL_IMPORT_STEP_1'            => 'Шаг 1'                      , 
	'LBL_IMPORT_STEP_1_DESCRIPTION' => 'Выберите файл'                 , 
	'LBL_IMPORT_STEP_2'            => 'Шаг 2'                      , 
	'LBL_IMPORT_STEP_2_DESCRIPTION' => 'Установить формат'              , 
	'LBL_IMPORT_STEP_3'            => 'Шаг 3'                      , 
	'LBL_IMPORT_STEP_3_DESCRIPTION' => 'Обработка дублирующихся записей'   , 
	'LBL_IMPORT_STEP_3_DESCRIPTION_DETAILED' => 'Выберите эту опцию для включения и задания критериев слияния дубликатов', 
	'LBL_IMPORT_STEP_4'            => 'Шаг 4',
	'LBL_IMPORT_STEP_4_DESCRIPTION' => 'Соответствие колонок полям модуля',
	'LBL_IMPORT_SUPPORTED_FILE_TYPES' => 'Поддерживаемые типы файлов: .CSV, .VCF', 
	'LBL_INVALID_FILE'             => 'Неверный файл',
	'LBL_NEXT_BUTTON_LABEL'        => 'Далее'                  , 
	'LBL_NO_ROWS_FOUND'            => 'Строки не найдены',
	'LBL_NUMBER_OF_RECORDS_CREATED' => 'Всего записей создано',
	'LBL_NUMBER_OF_RECORDS_DELETED' => 'Всего удалено записей',
	'LBL_NUMBER_OF_RECORDS_MERGED' => 'Всего объединено записей',
	'LBL_NUMBER_OF_RECORDS_SKIPPED' => 'Всего записей пропущено',
	'LBL_NUMBER_OF_RECORDS_UPDATED' => 'Всего записей перезаписано',
	'LBL_OK_BUTTON_LABEL'          => 'ОК',
	'LBL_RESULT'                   => 'Результат',
	'LBL_ROW_1'                    => 'Строка 1',
	'LBL_RUNNING'                  => 'Запущен',
	'LBL_SAVE_AS_CUSTOM_MAPPING'   => 'Сохранить как Пользовательское Соответствие',
	'LBL_SCHEDULED_IMPORT_DETAILS' => 'Ваш импорт был запланирован, после того, как импорт будет завершен, вы получите уведомление по электронной почте. <br>Пожалуйста, убедитесь, что сервер исходящей почты и адрес электронной почты настроены на получение уведомлений',
	'LBL_SELECTED_FIELDS'          => 'Соответствующие поля', 
	'LBL_SELECT_MERGE_FIELDS'      => 'Выберите соответствующие поля для поиска дублирующихся записей',
	'LBL_SELECT_SAVED_MAPPING'     => 'Использовать сохраненное Соответствие',
	'LBL_SPECIFY_MERGE_TYPE'       => 'Выберите, как дублирующиеся записи должны быть обработаны', 
	'LBL_TOTAL_RECORDS'            => 'Всего записей',
	'LBL_TOTAL_RECORDS_FAILED'     => 'Всего не удалось импортировать записей',
	'LBL_TOTAL_RECORDS_IMPORTED'   => 'Всего импортировано записей', 
	'LBL_UNDO_LAST_IMPORT'         => 'Отменить последний импорт', 
	'LBL_UNDO_RESULT'              => 'Отменить результат импорта',
	'LBL_VIEW_LAST_IMPORTED_RECORDS' => 'Последние импортированные записи',
	'Merge' => 'Совместить',
	'Overwrite' => 'Перезаписать',
	'comma' => ', (запятая)',
	'failed'                       => 'Ошибочные записи',
	'semicolon' => '; (точка с запятой)',
	'skipped'                      => 'Пропущено записей',
	'vcf' => 'VCard',
    'Skip' => 'Пропустить',
    'UTF-8' => 'UTF-8',
    'csv' => 'CSV',
);

$jsLanguageStrings = array(
);