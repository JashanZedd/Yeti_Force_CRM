/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): YetiForce.com
 ************************************************************************************/

jQuery.Class("Vtiger_DashBoard_Js", {
	gridster : false,

	//static property which will store the instance of dashboard
	currentInstance : false,

	addWidget : function(element, url) {
		var element = jQuery(element);
		var linkId = element.data('linkid');
		var name = element.data('name');
		jQuery(element).parent().hide();
		var widgetContainer = jQuery('<li class="new dashboardWidget" id="'+ linkId +'" data-name="'+name+'" data-mode="open"></li>');
		widgetContainer.data('url', url);
		var width = element.data('width');
		var height = element.data('height');
		Vtiger_DashBoard_Js.gridster.add_widget(widgetContainer, width, height);
		Vtiger_DashBoard_Js.currentInstance.loadWidget(widgetContainer);
	},

	


	restrictContentDrag : function(container){
		container.on('mousedown.draggable', function(e){
			var element = jQuery(e.target);
			var isHeaderElement = element.closest('.dashboardWidgetHeader').length > 0 ? true : false;
			if(isHeaderElement){
				return;
			}
			//Stop the event propagation so that drag will not start for contents
			e.stopPropagation();
		})
	},

}, {

	container : false,

	instancesCache : {},

	init : function() {
		Vtiger_DashBoard_Js.currentInstance = this;
	},

	getContainer : function() {
		if(this.container == false) {
			this.container = jQuery('.gridster ul');
		}
		return this.container;
	},

	getWidgetInstance : function(widgetContainer) {
		var id = widgetContainer.attr('id');
		if(!(id in this.instancesCache)) {
			var widgetName = widgetContainer.data('name');
			this.instancesCache[id] = Vtiger_Widget_Js.getInstance(widgetContainer, widgetName);
		}
		return this.instancesCache[id];
	},

	registerGridster : function() {
		var thisInstance = this;
		Vtiger_DashBoard_Js.gridster = this.getContainer().gridster({
			widget_margins: [7, 7],
			widget_base_dimensions: [100, 300],
			min_cols: 6,
			min_rows: 20,
			draggable: {
				'stop': function() {
					thisInstance.savePositions(jQuery('.dashboardWidget'));
				}
			}
		}).data('gridster');
	},

	savePositions: function(widgets) {
		var widgetRowColPositions = {}
		for (var index=0, len = widgets.length; index < len; ++index) {
			var widget = jQuery(widgets[index]);
			widgetRowColPositions[widget.attr('id')] = JSON.stringify({
				row: widget.attr('data-row'), col: widget.attr('data-col')
			});
		}

		AppConnector.request({module: 'Vtiger', action: 'SaveWidgetPositions', 'positionsmap': widgetRowColPositions}).then(function(data){
		});
	},

	loadWidgets : function() {
		var thisInstance = this;
		var widgetList = jQuery('.dashboardWidget');
		widgetList.each(function(index,widgetContainerELement){
			thisInstance.loadWidget(jQuery(widgetContainerELement));
		});

	},

	loadWidget : function(widgetContainer) {
		var thisInstance = this;
		var urlParams = widgetContainer.data('url');
		var mode = widgetContainer.data('mode');
		widgetContainer.progressIndicator();
		if(mode == 'open') {
			AppConnector.request(urlParams).then(
				function(data){
					widgetContainer.html(data);
					var adjustedHeight = widgetContainer.height()-50;
					app.showScrollBar(widgetContainer.find('.dashboardWidgetContent'),{'height' : adjustedHeight});
					var widgetInstance = thisInstance.getWidgetInstance(widgetContainer);
					widgetContainer.trigger(Vtiger_Widget_Js.widgetPostLoadEvent);
				},
				function(){
				}
				);
		} else {
	}
	},


	registerEvents : function() {
		this.registerGridster();
		this.loadWidgets();
		this.registerRefreshWidget();
		this.showWidgetIcons();
		this.hideWidgetIcons();
		this.removeWidget();
		this.registerFilterInitiater();
		this.gridsterStop();
		this.registerShowMailBody();
		this.registerChangeMailUser();
	},

	gridsterStop : function() {
		// TODO: we need to allow the header of the widget to be draggable
		var gridster = Vtiger_DashBoard_Js.gridster;

	},

	registerRefreshWidget : function() {
		var thisInstance = this;
		this.getContainer().on('click', 'a[name="drefresh"]', function(e) {
			var element = $(e.currentTarget);
			var parent = element.closest('li');
			var widgetInstnace = thisInstance.getWidgetInstance(parent);
			widgetInstnace.refreshWidget();
			return;
		});
	},

	showWidgetIcons : function() {
		this.getContainer().on('mouseover', 'li', function(e) {
			var element = $(e.currentTarget);
			var widgetIcons = element.find('.widgeticons');
			widgetIcons.fadeIn('slow', function() {
				widgetIcons.css('visibility', 'visible');
			});
		});
	},

	hideWidgetIcons : function() {
		this.getContainer().on('mouseout', 'li', function(e) {
			var element = $(e.currentTarget);
			var widgetIcons = element.find('.widgeticons');
			widgetIcons.css('visibility', 'hidden');
		});
	},

	removeWidget : function() {
		this.getContainer().on('click', 'li a[name="dclose"]', function(e) {
			var element = $(e.currentTarget);
            var listItem = jQuery(element).parents('li');
            var width = listItem.attr('data-sizex');
            var height = listItem.attr('data-sizey');
            
			var url = element.data('url');
			var parent = element.closest('.dashboardWidgetHeader').parent();
			var widgetName = parent.data('name');
			var widgetTitle = parent.find('.dashboardTitle').attr('title');

			var message = app.vtranslate('JS_ARE_YOU_SURE_TO_DELETE_WIDGET')+"["+widgetTitle+"]. "+app.vtranslate('JS_ARE_YOU_SURE_TO_DELETE_WIDGET_INFO');
			Vtiger_Helper_Js.showConfirmationBox({'message' : message}).then(
				function(e) {
						AppConnector.request(url).then(
							function(response) {
								if (response.success) {
									var nonReversableWidgets = []

									parent.fadeOut('slow', function() {
										parent.remove();
									});
									if (jQuery.inArray(widgetName, nonReversableWidgets) == -1) {
										var data = '<li><a onclick="Vtiger_DashBoard_Js.addWidget(this, \''+response.result.url+'\')" href="javascript:void(0);"';
										data += 'data-width='+width+' data-height='+height+ ' data-linkid='+response.result.linkid+' data-name='+response.result.name+'>'+response.result.title+'</a></li>';
										var divider = jQuery('.widgetsList .divider');
										if(divider.length) {
											jQuery(data).insertBefore(divider);
										} else {
											jQuery(data).insertAfter(jQuery('.widgetsList li:last'));
										}
									}
								}
							}
						);
					},
				function(error, err){
				}
			);
		});
	},

	registerFilterInitiater : function() {
		var container = this.getContainer();
		container.on('click', 'a[name="dfilter"]', function(e) {
			var widgetContainer = jQuery(e.currentTarget).closest('.dashboardWidget');
			var filterContainer = widgetContainer.find('.filterContainer');
			var dashboardWidgetHeader = jQuery('.dashboardWidgetHeader', widgetContainer);

			filterContainer.slideToggle(500);

			var callbackFunction = function() {
				filterContainer.slideUp(500);
				jQuery('.dateRange').DatePickerHide();
			}
			//adding clickoutside event on the dashboardWidgetHeader
			Vtiger_Helper_Js.addClickOutSideEvent(dashboardWidgetHeader, callbackFunction);

			return false;
		})
	},
	registerShowMailBody : function() {
		var container = this.getContainer();
		container.on('click', '.showMailBody', function(e) {
			var widgetContainer = jQuery(e.currentTarget).closest('.mailRow');
			var mailBody = widgetContainer.find('.mailBody');
			var bodyIcon = jQuery(e.currentTarget).find('.body-icon');
			if( mailBody.css( "display" ) == 'none'){
				mailBody.show();
				bodyIcon.removeClass( "icon-chevron-down" ).addClass( "icon-chevron-up" );
			}else{
				mailBody.hide();
				bodyIcon.removeClass( "icon-chevron-up" ).addClass( "icon-chevron-down" );
			}
		});
	},
	registerChangeMailUser : function() {
		var thisInstance = this;
		var container = this.getContainer();

		container.on('change', '#mailUserList', function(e) {
			var element = $(e.currentTarget);
			var parent = element.closest('li');
			var contentContainer = parent.find('.refresh');
			var optionSelected = $("option:selected", this);
			var url = parent.data('url')+'&user='+optionSelected.val();
			
			console.log( url );
			params = {};
			params.url = url
			params.data = {};
			contentContainer.progressIndicator({});
			AppConnector.request(params).then(
				function(data){
					contentContainer.progressIndicator({'mode': 'hide'});
					parent.html(data).trigger(Vtiger_Widget_Js.widgetPostRefereshEvent);
				},
				function(){
					contentContainer.progressIndicator({'mode': 'hide'});
				}
			);
		});
	}
});
