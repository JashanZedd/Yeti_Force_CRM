/* {[The file is published on the basis of YetiForce Public License 5.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} */
'use strict';

Settings_Vtiger_List_Js(
	'Settings_PickListDependency_List_Js',
	{},
	{
		container: false,
		registerFilterChangeEvent: function () {
			var thisInstance = this;
			$('#moduleFilter').on('change', function (e) {
				$('#pageNumber').val('1');
				$('#pageToJump').val('1');
				$('#orderBy').val('');
				$('#sortOrder').val('');
				var params = {
					module: app.getModuleName(),
					parent: app.getParentModuleName(),
					sourceModule: $(e.currentTarget).val()
				};
				$('#recordsCount').val('');
				$('#totalPageCount').text('');
				thisInstance.getListViewRecords(params).done(function (_data) {
					thisInstance.updatePagination();
				});
			});
		},
		getContainer: function () {
			if (this.container == false) {
				this.container = $('div.contentsDiv');
			}
			return this.container;
		},
		registerEvents: function () {
			this._super();
			this.registerFilterChangeEvent();
		}
	}
);
