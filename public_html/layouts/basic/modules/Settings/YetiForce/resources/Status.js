/* {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} */
'use strict';

jQuery.Class('Settings_YetiForce_Status_Js', {}, {
	getTabId: function () {
		return $(".WidgetsManage [name='tabid']").val();
	},
	getType: function () {
		return $(".form-modalAddWidget [name='type']").val();
	},
	registerSaveEvent: function (mode, data) {
		var resp = '';
		var params = {}
		params.data = {
			module: app.getModuleName(),
			parent: app.getParentModuleName(),
			action: 'SaveAjax',
			mode: mode,
			params: data
		}
		if (mode == 'saveWidget') {
			params.async = false;
		} else {
			params.async = true;
		}
		params.dataType = 'json';
		AppConnector.request(params).done(function (data) {
			var response = data['result'];
			var params = {
				text: response['message'],
				type: 'success'
			};
			Vtiger_Helper_Js.showPnotify(params);
			resp = response['success'];
		});
	},
	registerEvents: function (container) {
		var thisInstance = this;
		if (typeof container === "undefined") {
			container = jQuery('.YetiForceStatusContainer');
		}
		container.find(".YetiForceStatusUrlInput").on('change', function (e) {
			AppConnector.request({
				'module': 'YetiForce',
				'parent': 'Settings',
				'action': 'Status',
				'type': 'url',
				'newUrl': e.currentTarget.value
			}).done(function (data) {
				var response = data['result'], params;
				if (response['success']) {
					params = {
						text: response['data'],
						type: 'info',
					};
					Vtiger_Helper_Js.showPnotify(params);
				} else {
					params = {
						text: response['data'],
					};
					Vtiger_Helper_Js.showPnotify(params);
				}
			});
		});

		container.find(".YetiForceStatusFlagBool").on('change', function (e) {
			console.log(e);
			AppConnector.request({
				'module': 'YetiForce',
				'parent': 'Settings',
				'action': 'Status',
				'type': 'flag',
				'flagName': e.currentTarget.dataset.flag,
				'newParam': e.currentTarget.value
			}).done(function (data) {
				var response = data['result'], params;
				if (response['success']) {
					params = {
						text: response['data'],
						type: 'info',
					};
					Vtiger_Helper_Js.showPnotify(params);
				} else {
					params = {
						text: response['data'],
					};
					Vtiger_Helper_Js.showPnotify(params);
				}
			});
		});
	}
});
