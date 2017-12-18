/* {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} */
Vtiger_List_Js("Vtiger_ListPreview_Js", {}, {
	frameProgress: false,
	updatePreview: function (url) {
		var frame = $('#listPreviewframe');
		this.frameProgress = $.progressIndicator({
			position: 'html',
			message: app.vtranslate('JS_FRAME_IN_PROGRESS'),
			blockInfo: {
				enabled: true
			}
		});
		var defaultView = '';
		if (app.getMainParams('defaultDetailViewName')) {
			defaultView = defaultView + '&mode=showDetailViewByMode&requestMode=' + app.getMainParams('defaultDetailViewName'); // full, summary
		}
		frame.attr('src', url.replace("view=Detail", "view=DetailPreview") + defaultView);
	},
	registerPreviewEvent: function () {
		var thisInstance = this;
		var iframe = $("#listPreviewframe");
		$("#listPreviewframe").load(function () {
			thisInstance.frameProgress.progressIndicator({mode: "hide"});
			iframe.height($(this).contents().find(".bodyContents").height() - 20);
		});
		$(".listViewEntriesTable .listViewEntries").first().trigger("click");
	},
	postLoadListViewRecordsEvents: function (container) {
		this._super(container);
		this.registerPreviewEvent();
	},
	registerRowClickEvent: function () {
		var thisInstance = this;
		var listViewContentDiv = this.getListViewContentContainer();
		listViewContentDiv.on('click', '.listViewEntries', function (e) {
			if ($(e.target).closest('div').hasClass('actions'))
				return;
			if ($(e.target).is('button') || $(e.target).parent().is('button'))
				return;
			if ($(e.target).closest('a').hasClass('noLinkBtn'))
				return;
			if ($(e.target, $(e.currentTarget)).is('td:first-child'))
				return;
			if ($(e.target).is('input[type="checkbox"]'))
				return;
			if ($.contains($(e.currentTarget).find('td:last-child').get(0), e.target))
				return;
			if ($.contains($(e.currentTarget).find('td:first-child').get(0), e.target))
				return;
			var elem = $(e.currentTarget);
			var recordUrl = elem.data('recordurl');
			if (typeof recordUrl == 'undefined') {
				return;
			}
			e.preventDefault();
			$('.listViewEntriesTable .listViewEntries').removeClass('active');
			$(this).addClass('active');
			thisInstance.updatePreview(recordUrl);
		});
	},
	updateListPreviewSize: function (currentElement) {
		var fixedList = $('.fixedListInitial, .fixedListContent');
		var vtFooter = $('.vtFooter').height();
		if ($(window).width() > 993) {
			var height = $(window).height() - (vtFooter + currentElement.offset().top + 2);
			fixedList.css('max-height', height);
		}
	},
	updateGutterPosition: function (container) {
		window.console.log(container.find('#listPreview'));
		var gutter = container.find('.gutter');
		var listPreview = container.find('#listPreview');
		gutter.on('mousedown', function () {
			window.console.log('asdf');
			$(this).on('mousemove', function (e) {
				window.console.log($(this).position().left);
				window.console.log($('#listPreview').position().left);
				window.console.log($('#listPreview'));

				var left = listPreview.position().left;
				$(this).css('left', left);
			})
		})
	},
	registerListPreviewScroll: function (container) {
		var thisInstance = this;
		var currentElement = $('.fixedListInitial');
		var gutter = container.find('.gutter');
		var listPreview = container.find('#listPreview');
		$(window).resize(function () {
			thisInstance.updateListPreviewSize(currentElement);
		});
		var commactHeight = $('.commonActionsContainer').height();
		$('.mainBody').scroll(function () {
			console.log('scroll');
			if ($(this).scrollTop() >= (currentElement.offset().top + commactHeight)) {
				currentElement.addClass('fixedListInScroll');
				var left = listPreview.position().left - 2;
				console.log(gutter);
				gutter.css('left', left);
				gutter.on('mousedown', function () {
					window.console.log('asdf');
					$(this).on('mousemove', function (e) {
						left = listPreview.position().left - 2;
						$(this).css('left', left);
						window.console.log('asdasdasdf');
					})
				})
			} else {
				currentElement.removeClass('fixedListInScroll');
				left = listPreview.position().left - 2;
				gutter.css('left', 0);
				console.log('off');
				gutter.off();
			}
			thisInstance.updateListPreviewSize(currentElement);
		});
		thisInstance.updateListPreviewSize(currentElement);

	},
	registerEvents: function () {
		var listViewContainer = this.getListViewContentContainer();
		this._super();
		this.registerPreviewEvent();
		this.registerSplit('.fixedListInitial', '#listPreview');
		this.registerListPreviewScroll(listViewContainer);
	},
});
