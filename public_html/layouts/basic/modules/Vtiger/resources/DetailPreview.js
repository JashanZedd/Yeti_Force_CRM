/* {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} */
Vtiger_Detail_Js("Vtiger_DetailPreview_Js", {}, {
	registerLinkEvent: function (container) {
		$('#page').on('click', 'a', function (e) {
			e.preventDefault();
			var target = $(this);
			if (!target.closest('div').hasClass('fieldValue') || target.hasClass('showReferenceTooltip')) {
				if (target.attr('href') && target.attr('href') != '#') {
					top.location.href = target.attr('href');
				}
			}
		});
	},
	/**
	 * Function updates iframes' size with widgets
	 */
	updateWidgetEvent: function (iframe, bodyContents) {
		$("#page").find(".widget_contents").on("Vtiger.Widget.FinishLoad", function (e, widgetName) {
			iframe.height(bodyContents.height() - 10);
		});
	},
	/**
	 * Function changes iframes' size
	 */
	registerSizeEvent: function (container) {
		var thisInstance = this;
		var iframe = $(top.document).find("#listPreviewframe");
		var inifr = iframe.contents().find("#listPreviewframe");
		var mainContainer = $(".mainContainer");
		var panelBody = inifr.closest(".panel-body");
		//event for iframe in document record
		inifr.contents().find('body').on('DetailView.BlockToggle.PostLoad', function (e, data) {
			if (inifr.closest(".panel-body").length) {
				inifr.height(inifr.contents().find('.mainContainer').height() - 10);
				panelBody.height(inifr.height() + 100);
				iframe.height(iframe.contents().find(".detailViewContainer").height());
			}
		});
		//event on loading content in tab, adding records
		$("body").on("LoadRelatedRecordList.PostLoad", function (e, data) {
			iframe.height(iframe.contents().find(".detailViewContainer").height());
		});
		//event for toggle list's records
		$('body').on('DetailView.BlockToggle.PostLoad', function (e, data) {
			if (inifr.length) {
				inifr.height(mainContainer.height() - 10);
				iframe.height(inifr.height() + inifr.offset().top + 10);
			} else {
				iframe.height(mainContainer.height() - 10);
			}
		});
		//widgets loader
		thisInstance.updateWidgetEvent(iframe, mainContainer);
		//tabs loader
		$("#page").on("DetailView.Tab.FinishLoad", function (e, data) {
			//check if tab has listpreview
			if (iframe.contents().find("#listPreviewframe").length) {
				var inifr = iframe.contents().find("#listPreviewframe");
				inifr.height($(".detailViewContainer").height());
				iframe.height(inifr.height() + inifr.offset().top + 10);
				var currentIf = $("#listPreviewframe");
				$("#listPreviewframe").load(function () {
					currentIf.height(currentIf.contents().find(".detailViewContainer").height());
					iframe.height(currentIf.height() + currentIf.offset().top + 15);
				});
				thisInstance.updateWidgetEvent(currentIf, mainContainer);
			} else {
				iframe.height(mainContainer.height() - 10);
				thisInstance.updateWidgetEvent(iframe, mainContainer);
			}
		});
	},
	registerEvents: function () {
		this._super();
		this.registerLinkEvent();
		this.registerSizeEvent();
	}
});
