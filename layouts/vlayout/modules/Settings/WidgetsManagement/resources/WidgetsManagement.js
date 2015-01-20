/*+***********************************************************************************************************************************
 * The contents of this file are subject to the YetiForce Public License Version 1.1 (the "License"); you may not use this file except
 * in compliance with the License.
 * Software distributed under the License is distributed on an "AS IS" basis, WITHOUT WARRANTY OF ANY KIND, either express or implied.
 * See the License for the specific language governing rights and limitations under the License.
 * The Original Code is YetiForce.
 * The Initial Developer of the Original Code is YetiForce. Portions created by YetiForce are Copyright (C) www.yetiforce.com. 
 * All Rights Reserved.
 *************************************************************************************************************************************/
 
jQuery.Class('Settings_WidgetsManagement_Js', {
}, {
	/**
	* Function to create the array of block roles list
	*/
	getAuthorization : function() {
		var thisInstance = this;
		var authorization = new Array();
		continer = jQuery('#moduleBlocks');
		continer.find('.editFieldsTable').each(function(){
			authorization.push( jQuery(this).data('code') );
		});
		return authorization;
	},

	getAllFieldsInBlock : function(continer) {
		var thisInstance = this;
		var fields = new Array();
		continer.find('.blockFieldsList .editFields').each(function(){
			fields.push( jQuery(this).data('linkid').toString() );
		});
		return fields;
	},
	/**
	 * Function to register click event for add custom block button
	 */
	registerAddBlockDashBoard : function() {
		var thisInstance = this;
		var contents = jQuery('#layoutDashBoards');
		contents.find('.addBlockDashBoard').click(function(e) {
			var addBlockContainer = contents.find('.addBlockDashBoardModal').clone(true, true);
			var inUseAuthorization = thisInstance.getAuthorization();
			addBlockContainer.find('select.authorized option').each(function(){
				if(jQuery.inArray(jQuery(this).val(),inUseAuthorization) != -1)
					jQuery(this).remove();
			});
			
			var callBackFunction = function(data) {
				data.find('.addBlockDashBoardModal').removeClass('hide');
				//register all select2 Elements
				app.showSelect2ElementView(data.find('select'));
				
				var form = data.find('.addBlockDashBoardForm');
				var params = app.validationEngineOptions;

				params.onValidationComplete = function(form, valid){

					if(valid) {
						paramsForm = form.serializeFormData();
						paramsForm['action'] = 'addBlock';
						var field = form.find('[name="authorized"]');
						var paramsBlock = [];
						paramsBlock['authorized'] = field.val();
						paramsBlock['label'] = field.find(':selected').text();
						thisInstance.save(paramsForm, 'save').then(
							function(data) {
								var params = {};
								response = data.result;
								if(response['success']) {
									paramsBlock['id'] = response['id'];
									thisInstance.displayNewCustomBlock(paramsBlock);
									app.hideModalWindow();
									params['text'] = app.vtranslate('JS_BLOCK_ADDED');
								} else {
									params['text'] = response['message'];
									params['type'] = 'error';
								}
								Settings_Vtiger_Index_Js.showMessage(params);
							}
						);
						
						return valid;
					}
				}
				form.validationEngine(params);
				form.submit(function(e) {
					e.preventDefault();
				})
			}
			app.showModalWindow(addBlockContainer,function(data) {

				if(typeof callBackFunction == 'function') {
					callBackFunction(data);
				}
			}, {'width':'1000px'});
		});
	},

	save : function(form, mode) {

		var thisInstance = this;
		var aDeferred = jQuery.Deferred();
		var progressIndicatorElement = jQuery.progressIndicator({
			'position' : 'html',
			'blockInfo' : {
				'enabled' : true
			}
		});
		
		var params = {};
		params['form'] = form;
		params['module'] = app.getModuleName();
		params['parent'] = app.getParentModuleName();
		params['sourceModule'] = jQuery('#selectedModuleName').val();
		params['action'] = 'SaveAjax';
		params['mode'] = mode;

		AppConnector.request(params).then(
			function(data) {
				progressIndicatorElement.progressIndicator({'mode' : 'hide'});
				aDeferred.resolve(data);
			},
			function(error) {
				progressIndicatorElement.progressIndicator({'mode' : 'hide'});
				aDeferred.reject(error);
			}
		);
		return aDeferred.promise();
	},

	displayNewCustomBlock : function(result) {
		var thisInstance = this;
		var contents = jQuery('#layoutDashBoards');
		var newBlockCloneCopy = contents.find('.newCustomBlockCopy').clone(true, true);
		newBlockCloneCopy.data('block-id', result['id']).find('.blockLabel span').append(jQuery('<strong>'+result['label']+'</strong>'));
		newBlockCloneCopy.find('.addCustomField').removeClass('hide');
		newBlockCloneCopy.find('.specialWidget').data('block-id', result['id']);
		contents.find('#moduleBlocks').append(newBlockCloneCopy.removeClass('hide newCustomBlockCopy').addClass('editFieldsTable block_'+result['id']).data('code', result['authorized']));
	},
	

    /*
	 * Function to add clickoutside event on the element - By using outside events plugin
	 * @params element---On which element you want to apply the click outside event
	 * @params callbackFunction---This function will contain the actions triggered after clickoutside event
	 */
	addClickOutSideEvent : function(element, callbackFunction) {
		element.one('clickoutside',callbackFunction);
	},

	registerAddCustomFieldEvent : function() {
		var thisInstance = this;
		var contents = jQuery('#layoutDashBoards');
		contents.find('.addCustomField').click(function(e) {
			var continer = jQuery(e.currentTarget).closest('.editFieldsTable');
			var blockId = continer.data('block-id');
			var addFieldContainer = contents.find('.createFieldModal').clone(true, true);
			var allFieldsInBlock = thisInstance.getAllFieldsInBlock(continer);
			addFieldContainer.find('select.fieldTypesList option').each(function(){
				if(jQuery.inArray(jQuery(this).val(),allFieldsInBlock) != -1){
					jQuery(this).remove();
				}
			});
			addFieldContainer.removeClass('hide');
			
			var callBackFunction = function(data) {
				//register all select2 Elements
				app.showSelect2ElementView(data.find('select'));

				var form = data.find('.createCustomFieldForm');
				form.attr('id', 'createFieldForm');

				var params = app.getvalidationEngineOptions(true);
				params.onValidationComplete = function(form, valid){
					if(valid) {
						var saveButton = form.find(':submit');
						saveButton.attr('disabled', 'disabled');
						var field = form.find('[name="widgets"]');
						
						paramsForm = form.serializeFormData();
						paramsForm['action'] = 'addWidget';
						paramsForm['blockid'] = blockId;
						paramsForm['linkid'] = field.val();
						paramsForm['label'] = field.find(':selected').text();
						if(form.find('[name="isdefault"]').prop("checked"))
							paramsForm['isdefault'] = 1;
						thisInstance.save(paramsForm, 'save').then(
							function(data) {
								var result = data['result'];
								var params = {};
								if(data['success']) {
									app.hideModalWindow();
									paramsForm['id'] = result['id']
									params['text'] = app.vtranslate('JS_CUSTOM_FIELD_ADDED');
									Settings_Vtiger_Index_Js.showMessage(params);
									thisInstance.showCustomField(paramsForm);
								} else {
									var message = data['error']['message'];
									if(data['error']['code'] != 513){
										var errorField = form.find('[name="fieldName"]');
									}else{
										var errorField = form.find('[name="fieldLabel"]');
									}
									errorField.validationEngine('showPrompt', message , 'error','topLeft',true);
									saveButton.removeAttr('disabled');
								}
							}
						);
					}
					//To prevent form submit
					return false;
				}
				form.validationEngine(params);
			}
			app.showModalWindow(addFieldContainer,function(data) {
				if(typeof callBackFunction == 'function') {
					callBackFunction(data);
				}
			}, {'width':'1000px'});
		});
	},

	/**
	 * Function to add new custom field ui to the list
	 */
	showCustomField : function(result) {

		var thisInstance = this;
		var contents = jQuery('#layoutDashBoards');
		var relatedBlock = contents.find('.block_'+result['blockid']);
		var fieldCopy = contents.find('.newCustomFieldCopy').clone(true, true);
		var fieldContainer = fieldCopy.find('div.marginLeftZero.border1px');
		fieldContainer.addClass('opacity editFields').attr('data-field-id', result['id']).attr('data-block-id', result['blockid']).attr('data-linkid', result['linkid']);
		fieldContainer.find('.deleteCustomField, .saveFieldDetails').attr('data-field-id', result['id']);
		fieldContainer.find('.fieldLabel').html(result['label']);
		var block = relatedBlock.find('.blockFieldsList');
		var sortable1 = block.find('ul[name=sortable1]');
		var length1 = sortable1.children().length;
		var sortable2 = block.find('ul[name=sortable2]');
		var length2 = sortable2.children().length;

		// Deciding where to add the new field
		if(length1 > length2) {
			sortable2.append(fieldCopy.removeClass('hide newCustomFieldCopy'));
		} else {
			sortable1.append(fieldCopy.removeClass('hide newCustomFieldCopy'));
		}
		var form = fieldCopy.find('form.fieldDetailsForm');
		thisInstance.setFieldDetails(result, form);
	},
	
	/**
	 * Function to set the field info for edit field actions
	 */
	setFieldDetails : function(result, form) {
		var thisInstance = this;
		//add field label to the field details
		form.find('.modal-header').html(jQuery('<strong>'+result['label']+'</strong><div class="pull-right"><a href="javascript:void(0)" class="cancel">X</a></div>'));

		if(result['isdefault']) {
			form.find('[name="isdefault"]').filter(':checkbox').attr('checked', true);
		}

	},
	
	registerEditFieldDetailsClick : function() {
		var thisInstance = this;
		contents = jQuery('#layoutDashBoards');
		contents.find('.editFieldDetails').click(function(e) {
			var currentTarget = jQuery(e.currentTarget);
			var fieldRow = currentTarget.closest('div.editFields');
			fieldRow.removeClass('opacity');
			var basicDropDown = fieldRow.find('.basicFieldOperations');
			var dropDownContainer = currentTarget.closest('.btn-group');
			dropDownContainer.find('.dropdown-menu').remove();
			var dropDown = basicDropDown.clone().removeClass('basicFieldOperations hide').addClass('dropdown-menu');
			dropDownContainer.append(dropDown);
			var dropDownMenu  = dropDownContainer.find('.dropdown-menu');
			var params = app.getvalidationEngineOptions(true);
			params.binded = false,
			params.onValidationComplete = function(form,valid){
				if(valid) {
					
					paramsForm = form.serializeFormData();
					if(form.find('[name="isdefault"]').prop("checked"))
							paramsForm['isdefault'] = 1;
					var id = form.find('.saveFieldDetails').data('field-id');
					paramsForm['action'] = 'saveDetails';
					paramsForm['id'] = id;
					thisInstance.save(paramsForm, 'save');
					thisInstance.registerSaveFieldDetailsEvent(form);
				}
				return false;
			}
			dropDownMenu.find('form').validationEngine(params);

			thisInstance.avoidDropDownClick(dropDownContainer);

			dropDownMenu.on('change', ':checkbox', function(e) {
				var currentTarget = jQuery(e.currentTarget);
				if(currentTarget.attr('readonly') == 'readonly') {
					var status = jQuery(e.currentTarget).is(':checked');
					if(!status){
						jQuery(e.currentTarget).attr('checked','checked')
					}else{
						jQuery(e.currentTarget).removeAttr('checked');
					}
					e.preventDefault();
				}
			});

			//added for drop down position change
			var offset = currentTarget.offset(),
                height = currentTarget.outerHeight(),
                dropHeight = dropDown.outerHeight(),
                viewportBottom = $(window).scrollTop() + document.documentElement.clientHeight,
                dropTop = offset.top + height,
                enoughRoomBelow = dropTop + dropHeight <= viewportBottom;
			   if(!enoughRoomBelow) {
				   dropDown.addClass('bottom-up');
			   } else {
				   dropDown.removeClass('bottom-up');
			   }

			var callbackFunction = function() {
				fieldRow.addClass('opacity');
				dropDown.remove();
			}
			thisInstance.addClickOutSideEvent(dropDown, callbackFunction);

			jQuery('.cancel').click(function(){
				callbackFunction();
			});
		});
	},

	/**
	 * Function to register the click event for save button after edit field details
	 */
	registerSaveFieldDetailsEvent : function(form) {
		var thisInstance = this;
		var submitButtton = form.find('.saveFieldDetails');
		var fieldId = submitButtton.data('field-id');
		var block = submitButtton.closest('.editFieldsTable');
		var blockId = block.data('block-id');
		//close the drop down
		submitButtton.closest('.btn-group').removeClass('open');
		//adding class opacity to fieldRow - to give opacity to the actions of the fields
		var fieldRow = submitButtton.closest('.editFields');
		fieldRow.addClass('opacity');
		var dropDownMenu = form.closest('.dropdown-menu');
				app.destroyChosenElement(form);
		var basicContents = form.closest('.editFields').find('.basicFieldOperations');
			basicContents.html(form);
			dropDownMenu.remove();
	},

	/**
	 * Function to register click event for drop-downs in fields list 
	 */
	avoidDropDownClick : function(dropDownContainer) {
		dropDownContainer.find('.dropdown-menu').click(function(e) {
			e.stopPropagation();
		});
	},
	
	registerSpecialWidget : function() {
		var thisInstance = this;
		jQuery('#layoutDashBoards').find('.addNotebook').click(function(e) {
			thisInstance.addNoteBookWidget(this, jQuery(this).data('url'));
		});
		jQuery('#layoutDashBoards').find('.addMiniList').click(function(e) {
			thisInstance.addMiniListWidget(this, jQuery(this).data('url'));
		});
	},
	
	addNoteBookWidget : function(element, url) {
		var thisInstance = this;
		element = jQuery(element);

		app.showModalWindow(null, "index.php?module=Home&view=AddNotePad", function(wizardContainer){
			var form = jQuery('form', wizardContainer);
			var params = app.validationEngineOptions;
			params.onValidationComplete = function(form, valid){
				if(valid) {
                    //To prevent multiple click on save
                    jQuery("[name='saveButton']").attr('disabled','disabled');
					var notePadName = form.find('[name="notePadName"]').val();
					var notePadContent = form.find('[name="notePadContent"]').val();
					var isDefault = 0;
					var linkId = element.data('linkid');
					var blockId = element.data('block-id');
					var noteBookParams = {
						'module' : 'Vtiger',
						'action' : 'NoteBook',
						'mode' : 'NoteBookCreate',
						'notePadName' : notePadName,
						'notePadContent' : notePadContent,
						'blockid' : blockId,
						'linkId' : linkId,
						'isdefault' : isDefault
					}
					AppConnector.request(noteBookParams).then(
						function(data){
							if(data.result.success){
								var widgetId = data.result.widgetId;
								app.hideModalWindow();
								noteBookParams['id'] = widgetId;
								noteBookParams['label'] = notePadName;
								params['text'] = app.vtranslate('JS_CUSTOM_FIELD_ADDED');
								Settings_Vtiger_Index_Js.showMessage(params);
								thisInstance.showCustomField(noteBookParams);
							}
						})
				}
				return false;
			}
			form.validationEngine(params);
		});
	},
	
	
	
	addMiniListWidget: function(element, url) {
		// 1. Show popup window for selection (module, filter, fields)
		// 2. Compute the dynamic mini-list widget url
		// 3. Add widget with URL to the page.
		var thisInstance = this;
		element = jQuery(element);

		app.showModalWindow(null, "index.php?module=Home&view=MiniListWizard&step=step1", function(wizardContainer){
			var form = jQuery('form', wizardContainer);

			var moduleNameSelectDOM = jQuery('select[name="module"]', wizardContainer);
			var filteridSelectDOM = jQuery('select[name="filterid"]', wizardContainer);
			var fieldsSelectDOM = jQuery('select[name="fields"]', wizardContainer);

			var moduleNameSelect2 = app.showSelect2ElementView(moduleNameSelectDOM, {
				placeholder: app.vtranslate('JS_SELECT_MODULE')
			});
			var filteridSelect2 = app.showSelect2ElementView(filteridSelectDOM,{
				placeholder: app.vtranslate('JS_PLEASE_SELECT_ATLEAST_ONE_OPTION')
			});
			var fieldsSelect2 = app.showSelect2ElementView(fieldsSelectDOM, {
				placeholder: app.vtranslate('JS_PLEASE_SELECT_ATLEAST_ONE_OPTION'),
				closeOnSelect: true,
				maximumSelectionSize: 6
			});
			var footer = jQuery('.modal-footer', wizardContainer);

			filteridSelectDOM.closest('tr').hide();
			fieldsSelectDOM.closest('tr').hide();
			footer.hide();

			moduleNameSelect2.change(function(){
				if (!moduleNameSelect2.val()) return;

				AppConnector.request({
					module: 'Home',
					view: 'MiniListWizard',
					step: 'step2',
					selectedModule: moduleNameSelect2.val()
				}).then(function(res) {
					filteridSelectDOM.empty().html(res).trigger('change');
					filteridSelect2.closest('tr').show();
				})
			});
			filteridSelect2.change(function(){
				if (!filteridSelect2.val()) return;

				AppConnector.request({
					module: 'Home',
					view: 'MiniListWizard',
					step: 'step3',
					selectedModule: moduleNameSelect2.val(),
					filterid: filteridSelect2.val()
				}).then(function(res){
					fieldsSelectDOM.empty().html(res).trigger('change');
					fieldsSelect2.closest('tr').show();
				});
			});
			fieldsSelect2.change(function() {
				if (!fieldsSelect2.val()) {
					footer.hide();
				} else {
					footer.show();
				}
			});

			form.submit(function(e){
				e.preventDefault();
                //To disable savebutton after one submit to prevent multiple submits
                jQuery("[name='saveButton']").attr('disabled','disabled');
				var selectedModule = moduleNameSelect2.val();
				var selectedModuleLabel = moduleNameSelect2.find(':selected').text();
				var selectedFilterId= filteridSelect2.val();
				var selectedFilterLabel= filteridSelect2.find(':selected').text();
				var selectedFields = [];
				fieldsSelect2.select2('data').map(function (obj) { 
					selectedFields.push(obj.id);
				});
				// TODO mandatory field validation
				finializeAdd(selectedModule, selectedModuleLabel, selectedFilterId, selectedFilterLabel, selectedFields);
			});
		});

		function finializeAdd(moduleName, moduleNameLabel, filterid, filterLabel, fields) {
			var data = {
				module: moduleName
			}
			if (typeof fields != 'object') fields = [fields];
			data['fields'] = fields;

			paramsForm = {};
			paramsForm['data'] = JSON.stringify(data);
			paramsForm['action'] = 'addWidget';
			paramsForm['blockid'] = element.data('block-id');;
			paramsForm['linkid'] = element.data('linkid');
			paramsForm['label'] = moduleNameLabel + ' - ' + filterLabel;
			paramsForm['filterid'] = filterid;
			paramsForm['isdefault'] = 0;

			thisInstance.save(paramsForm, 'save').then(
				function(data) {
					var result = data['result'];
					var params = {};
					if(data['success']) {
						app.hideModalWindow();
						paramsForm['id'] = result['id']
						params['text'] = app.vtranslate('JS_CUSTOM_FIELD_ADDED');
						Settings_Vtiger_Index_Js.showMessage(params);
						thisInstance.showCustomField(paramsForm);
					} else {
						var message = data['error']['message'];
						if(data['error']['code'] != 513){
							var errorField = form.find('[name="fieldName"]');
						}else{
							var errorField = form.find('[name="fieldLabel"]');
						}
						errorField.validationEngine('showPrompt', message , 'error','topLeft',true);
						saveButton.removeAttr('disabled');
					}
				}
			);
		}
	},
	
	/**
	 * Function to register the click event for delete custom field
	 */
	registerDeleteCustomFieldEvent : function(contents) {
		var thisInstance = this;
		if(typeof contents == 'undefined') {
			contents = jQuery('#layoutDashBoards');
		}
		contents.find('a.deleteCustomField').click(function(e) {
			var currentTarget = jQuery(e.currentTarget);
			var fieldId = currentTarget.data('field-id');
			var paramsForm = {}
			paramsForm['action'] = 'removeWidget';
			paramsForm['id'] = fieldId;
			var message = app.vtranslate('JS_LBL_ARE_YOU_SURE_YOU_WANT_TO_DELETE');
			Vtiger_Helper_Js.showConfirmationBox({'message' : message}).then(
				function(e) {
					thisInstance.save(paramsForm, 'delete').then(
						function(data) {
							var field = currentTarget.closest('div.editFields');
							var blockId = field.data('block-id');
							field.parent().fadeOut('slow').remove();
							var block = jQuery('#block_'+blockId);
							thisInstance.reArrangeBlockFields(block);
							var params = {};
							params['text'] = app.vtranslate('JS_CUSTOM_FIELD_DELETED');
							Settings_Vtiger_Index_Js.showMessage(params);
						},function(error, err) {

						}
					);
				},
				function(error, err){

				}
			);
		});
	},
	
	/**
	 * Function that rearranges fields in the block when the fields are moved
	 * @param <jQuery object> block
	 */
	reArrangeBlockFields : function(block) {
		// 1.get the containers, 2.compare the length, 3.if uneven then move the last element
		var leftSideContainer = block.find('ul[name=sortable1]');
		var rightSideContainer = block.find('ul[name=sortable2]');
		if(leftSideContainer.children().length < rightSideContainer.children().length) {
			var lastElementInRightContainer = rightSideContainer.children(':last');
			leftSideContainer.append(lastElementInRightContainer);
		} else if(leftSideContainer.children().length > rightSideContainer.children().length+1) {	//greater than 1
			var lastElementInLeftContainer = leftSideContainer.children(':last');
			rightSideContainer.append(lastElementInLeftContainer);
		}
	},
	
	/**
	 * Function to register the click event for delete custom block
	 */
	registerDeleteCustomBlockEvent : function() {
		var thisInstance = this;
		var contents = jQuery('#layoutDashBoards');
		var table = contents.find('.editFieldsTable');
		contents.on('click', '.deleteCustomBlock', function(e) {
			var currentTarget = jQuery(e.currentTarget);
			var table = currentTarget.closest('div.editFieldsTable');
			var blockId = table.data('block-id');
			var paramsFrom = {};
			paramsFrom['blockid'] = blockId;
			paramsFrom['action'] = 'removeBlock';
			var message = app.vtranslate('JS_LBL_ARE_YOU_SURE_YOU_WANT_TO_DELETE');
			Vtiger_Helper_Js.showConfirmationBox({'message' : message}).then(
				function(e) {
					thisInstance.save(paramsFrom, 'delete').then(
						function(data) {
							thisInstance.removeDeletedBlock(blockId, 'delete');
							var params = {};
							params['text'] = app.vtranslate('JS_CUSTOM_BLOCK_DELETED');
							Settings_Vtiger_Index_Js.showMessage(params);
						},function(error, err) {

						}
					);
				},
				function(error, err){

				}
			);
		});
	},
	
	/**
	 * Function to remove the deleted custom block from the ui
	 */
	removeDeletedBlock : function(blockId) {
		var contents = jQuery('#layoutDashBoards');
		var deletedTable = contents.find('.block_'+blockId);
		deletedTable.fadeOut('slow').remove();
	},
	
	
	/**
	 * Function to register the change event for layout editor modules list
	 */
	registerModulesChangeEvent : function() {
		var thisInstance = this;
		var container = jQuery('#widgetsManagementEditorContainer');
		var contentsDiv = container.closest('.contentsDiv');

		app.showSelect2ElementView(container.find('[name="widgetsManagementEditorModules"]'), {dropdownCss : {'z-index' : 0}});

		container.on('change', '[name="widgetsManagementEditorModules"]', function(e) {
			var currentTarget = jQuery(e.currentTarget);
			var selectedModule = currentTarget.val();
			thisInstance.getModuleLayoutEditor(selectedModule).then(
				function(data) {
					contentsDiv.html(data);
					thisInstance.registerEvents();
				}
			);
		});

	},
	/**
	 * Function to get the respective module layout editor through pjax
	 */
	getModuleLayoutEditor : function(selectedModule) {
		var thisInstance = this;
		var aDeferred = jQuery.Deferred();
		var progressIndicatorElement = jQuery.progressIndicator({
			'position' : 'html',
			'blockInfo' : {
				'enabled' : true
			}
		});

		var params = {};
		params['module'] = app.getModuleName();
		params['parent'] = app.getParentModuleName();
		params['view'] = 'Configuration';
		params['sourceModule'] = selectedModule;

		AppConnector.requestPjax(params).then(
			function(data) {
				progressIndicatorElement.progressIndicator({'mode' : 'hide'});
				aDeferred.resolve(data);
			},
			function(error) {
				progressIndicatorElement.progressIndicator({'mode' : 'hide'});
				aDeferred.reject();
			}
		);
		return aDeferred.promise();
	},
	
	/**
	 * register events for layout editor
	 */
	registerEvents : function() {
		var thisInstance = this;

		thisInstance.registerAddBlockDashBoard();
		thisInstance.registerAddCustomFieldEvent();
		thisInstance.registerEditFieldDetailsClick();
		thisInstance.registerSpecialWidget();
		thisInstance.registerDeleteCustomFieldEvent();
		thisInstance.registerDeleteCustomBlockEvent();
		thisInstance.registerModulesChangeEvent();
	}

});

jQuery(document).ready(function() {
	var instance = new Settings_WidgetsManagement_Js();
	instance.registerEvents();
})