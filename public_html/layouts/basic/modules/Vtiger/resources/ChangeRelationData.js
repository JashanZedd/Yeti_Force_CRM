/* {[The file is published on the basis of YetiForce Public License 5.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} */
'use strict';
$.Class(
	'Base_ChangeRelationData_JS',
	{},
	{
		registerEvents(container) {
			container.find('.js-modal__save').on('click', (e) => {
				let progress = $.progressIndicator({
					position: 'html',
					blockInfo: {
						enabled: true
					}
				});
				const formValidate = container.find('form').validationEngine(app.validationEngineOptions);
				if (formValidate.validationEngine('validate')) {
					let params = container.find('form').serializeFormData();
					AppConnector.request(params)
						.done(function (data) {
							progress.progressIndicator({
								mode: 'hide'
							});
							app.hideModalWindow();
							let params = {};
							if (data.result) {
								params = {
									text: app.vtranslate('JS_SAVE_NOTIFY_OK'),
									type: 'success'
								};
								let detailInstance = Vtiger_Detail_Js.getInstance(),
									selectedTabElement = detailInstance.getSelectedTab();
								if (selectedTabElement) {
									selectedTabElement.trigger('click');
								}
							} else {
								params = {
									text: app.vtranslate('JS_ERROR'),
									type: 'error'
								};
							}
							app.showNotify(params);
						})
						.fail(function (textStatus, errorThrown) { });
				}
				$(".blockOverlay, .blockMessageContainer, .blockUI").remove();
			});
		}
	}
);
