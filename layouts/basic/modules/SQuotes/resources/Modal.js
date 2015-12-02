/**
 * @license licenses/License.html
 * @package YetiForce.Edit
 * @author Radosław Skrzypczak <r.skrzypczak@yetiforce.com>
 */
jQuery.Class("SQuotes_Modal_Js", {}, {
	registerChangeStatus: function () {
		var thisInstance = this;
		jQuery('#sQuotesModal .changeStatus').on('click', function (e) {
			var currentTarget = jQuery(e.currentTarget);
			currentTarget.closest('.modal').addClass('hide');
			thisInstance.updateStatus(currentTarget);
		});
	},
	updateStatus: function (currentTarget) {
		var thisInstance = this;
		var params = {
			'module': 'SQuotes',
			'action': "UpdateStatus",
			'record': currentTarget.data('id'),
			'state': currentTarget.data('state')
		}
		app.hideModalWindow();
		var progressIndicatorElement = jQuery.progressIndicator({
			'position': 'html',
			'blockInfo': {
				'enabled': true
			}
		});
		AppConnector.request(params).then(
				function (data) {
					if (data.success) {
						var viewName = app.getViewName();
						if (viewName === 'Detail') {
							if (app.getModuleName() == 'SQuotes') {
								window.location.reload();
							} else {
								Vtiger_Detail_Js.reloadRelatedList();
							}
						}
						if (viewName == 'List') {
							var listinstance = new Vtiger_List_Js();
							listinstance.getListViewRecords();
						}
						if (viewName == 'DashBoard') {
							var instance = new Vtiger_DashBoard_Js();
							instance.getContainer().find('a[name="drefresh"]').trigger('click');
						}
						progressIndicatorElement.progressIndicator({'mode': 'hide'});
					} else {
						return false;
					}
				}
		);
	},
	registerHelpInfo: function () {
		var form = jQuery('#sQuotesModal');
		var elemente = app.showPopoverElementView(form.find('.helpInfoPopover'), {trigger: 'click', html: true});
		elemente.trigger('click').trigger('click');
		elemente.on('shown.bs.popover', function (e, i) {
			var element = jQuery(e.currentTarget);
			var popover = element.next();
			app.showScrollBar(popover.find('.popover-content'), {
				height: '300px',
				railVisible: true,
			});
		});
	},
	registerEvents: function () {
		this.registerChangeStatus();
		this.registerHelpInfo();
		jQuery('#sQuotesModal .convert').each(function () {
			var text = jQuery(this).text();
			jQuery(this).text(text);
		})
	}

});

jQuery(document).ready(function (e) {
	var instance = new SQuotes_Modal_Js();
	instance.registerEvents();
})
