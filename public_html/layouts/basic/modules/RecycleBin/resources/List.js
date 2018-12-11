/* {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} */
'use strict';

Vtiger_List_Js("RecycleBin_List_Js", {
	/**
	 * Mass activation trigerred on the list
	 */
	massActivation: function () {
		let params = this.getSelectedRecordsParams(),
			listInstance = Vtiger_List_Js.getInstance(),
			container = listInstance.getListViewContainer(),
			overrideParams = {
				module: container.find('.js-source-module').val(),
				state: 'Active',
				entityState: 'Trash',
				action: 'MassState',
			};
		AppConnector.request($.extend(overrideParams, params)).done(function (data) {
			if (data && data.result && data.result.notify) {
				Vtiger_Helper_Js.showMessage(data.result.notify);
			}
			listInstance.getListViewRecords({
				module: app.getModuleName(),
				view: 'List',
				parent: app.getModuleName(),
				sourceModule: container.find('.js-source-module').val()
			});
		});
	},

}, {
	/**
	 * Register module select
	 */
	registerModuleFilter: function () {
		const self = this,
			container = this.getListViewContainer();
		let filterSelectElement = container.find('.js-source-module');
		filterSelectElement.on('select2:selecting', function (e) {
			self.getListViewRecords({
				module: app.getModuleName(),
				view: 'List',
				parent: app.getModuleName(),
				sourceModule: e.params.args.data.id
			}).done(function () {
				self.calculatePages().done(function () {
					container.find('.js-pagination-list').data('totalCount', '');
					self.updatePagination();
				});
			});
		});
	},
	/**
	 * Function to update pagination page numer
	 * @param {boolean} force
	 */
	updatePaginationAjax(force = false) {
		const self = this,
			listViewPageDiv = this.getListViewContainer();
		let params = self.getDefaultParams(),
			container = listViewPageDiv.find('.paginationDiv'),
			overrideParams = {
				totalCount: -1,
				view: 'Pagination',
				mode: 'getPagination',
				entityState: 'Trash',
				module: listViewPageDiv.find('.js-source-module').val(),
			};
		;
		Vtiger_Helper_Js.showMessage({
			title: app.vtranslate('JS_LBL_PERMISSION'),
			text: app.vtranslate('JS_GET_PAGINATION_INFO'),
			type: 'info',
		});
		if (container.find('.js-pagination-list').data('total-count') > 0 || force) {
			AppConnector.request($.extend(overrideParams, params)).done(function (data) {
				container.html(data);
				self.registerPageNavigationEvents();
			});
		}
	},
	/**
	 * Register empty recycle button
	 */
	registerEmptyRecycle: function () {
		const self = this,
			container = this.getListViewContainer();
		container.find('.js-recycle-empty').on('click', function () {
			Vtiger_Helper_Js.showConfirmationBox({
				message: app.vtranslate('JS_DELETE_ALL_RECYCLE_RECORD_DESC'),
				title: app.vtranslate('JS_DELETE_ALL_RECYCLE_RECORD')
			}).done(function (e) {
				let progressIndicatorElement = $.progressIndicator();
				AppConnector.request({
					module: 'RecycleBin',
					sourceModule: container.find('.js-source-module').val(),
					action: 'MassDeleteAll',
					sourceView: 'List'
				}).done(function (data) {
					progressIndicatorElement.progressIndicator({mode: 'hide'});
					if (data && data.result && data.result.notify) {
						Vtiger_Helper_Js.showMessage(data.result.notify);
					}
					self.getListViewRecords();
				}).fail(function (error, err) {
					progressIndicatorElement.progressIndicator({mode: 'hide'});
				});
			});
		});
	},
	/**
	 * Get record list of actual selected module
	 * @param {string} urlParams
	 * @returns {*|jQuery}
	 */
	getListViewRecords: function (urlParams) {
		let overrideUrlParams = {},
			aDeferred = $.Deferred();
		overrideUrlParams.sourceModule = $('.js-source-module').val();
		urlParams = $.extend(overrideUrlParams, urlParams);
		this._super(urlParams).done(function () {
			aDeferred.resolve();
		}).fail(function (textStatus, errorThrown) {
			aDeferred.reject(textStatus, errorThrown);
		});
		return aDeferred.promise();
	},
	registerEvents: function () {
		this._super();
		this.registerModuleFilter();
		this.registerEmptyRecycle();
	}
});
