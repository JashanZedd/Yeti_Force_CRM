/* {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} */
App.Fields = {
	'Date': {
		months: ["JS_JAN", "JS_FEB", "JS_MAR", "JS_APR", "JS_MAY", "JS_JUN", "JS_JUL", "JS_AUG", "JS_SEP", "JS_OCT", "JS_NOV", "JS_DEC"],
		monthsTranslated: ["JS_JAN", "JS_FEB", "JS_MAR", "JS_APR", "JS_MAY", "JS_JUN", "JS_JUL", "JS_AUG", "JS_SEP", "JS_OCT", "JS_NOV", "JS_DEC"].map((monthName) => app.vtranslate(monthName)),
		fullMonths: ["JS_JANUARY", "JS_FEBRUARY", "JS_MARCH", "JS_APRIL", "JS_MAY", "JS_JUNE", "JS_JULY", "JS_AUGUST", "JS_SEPTEMBER", "JS_OCTOBER", "JS_NOVEMBER", "JS_DECEMBER"],
		fullMonthsTranslated: ["JS_JANUARY", "JS_FEBRUARY", "JS_MARCH", "JS_APRIL", "JS_MAY", "JS_JUNE", "JS_JULY", "JS_AUGUST", "JS_SEPTEMBER", "JS_OCTOBER", "JS_NOVEMBER", "JS_DECEMBER"].map((monthName) => app.vtranslate(monthName)),
		days: ["JS_SUN", "JS_MON", "JS_TUE", "JS_WED", "JS_THU", "JS_FRI", "JS_SAT"],
		daysTranslated: ["JS_SUN", "JS_MON", "JS_TUE", "JS_WED", "JS_THU", "JS_FRI", "JS_SAT"].map((monthName) => app.vtranslate(monthName)),
		fullDays: ["JS_SUNDAY", "JS_MONDAY", "JS_TUESDAY", "JS_WEDNESDAY", "JS_THURSDAY", "JS_FRIDAY", "JS_SATURDAY"],
		fullDaysTranslated: ["JS_SUNDAY", "JS_MONDAY", "JS_TUESDAY", "JS_WEDNESDAY", "JS_THURSDAY", "JS_FRIDAY", "JS_SATURDAY"].map((monthName) => app.vtranslate(monthName)),

		/**
		 * Register DatePicker
		 * @param {jQuery} parentElement
		 * @param {boolean} registerForAddon
		 * @param {object} customParams
		 */
		register(parentElement, registerForAddon, customParams) {
			if (typeof parentElement === 'undefined') {
				parentElement = jQuery('body');
			} else {
				parentElement = jQuery(parentElement);
			}
			if (typeof registerForAddon === 'undefined') {
				registerForAddon = true;
			}
			let elements = jQuery('.dateField', parentElement);
			if (parentElement.hasClass('dateField')) {
				elements = parentElement;
			}
			if (elements.length === 0) {
				return;
			}
			if (registerForAddon === true) {
				const parentDateElem = elements.closest('.date');
				jQuery('.input-group-addon:not(.notEvent)', parentDateElem).on('click', function inputGroupAddonClickHandler(e) {
					// Using focus api of DOM instead of jQuery because show api of datePicker is calling e.preventDefault
					// which is stopping from getting focus to input element
					jQuery(e.currentTarget).closest('.date').find('input.dateField').get(0).focus();
				});
			}
			let format = CONFIG.dateFormat;
			const elementDateFormat = elements.data('dateFormat');
			if (typeof elementDateFormat !== 'undefined') {
				format = elementDateFormat;
			}
			// Default first day of the week
			const defaultFirstDay = typeof CONFIG.firstDayOfWeekNo === 'undefined' ? 1 : CONFIG.firstDayOfWeekNo;
			if (typeof $.fn.datepicker.dates[CONFIG.langKey] === 'undefined') {
				$.fn.datepicker.dates[CONFIG.langKey] = {
					days: App.Fields.Date.fullDaysTranslated,
					daysShort: App.Fields.Date.daysTranslated,
					daysMin: App.Fields.Date.daysTranslated,
					months: App.Fields.Date.fullMonthsTranslated,
					monthsShort: App.Fields.Date.monthsTranslated,
					today: app.vtranslate('JS_TODAY'),
					clear: app.vtranslate('JS_CLEAR'),
					format,
					titleFormat: 'MM yyyy', /* Leverages same syntax as 'format' */
					weekStart: defaultFirstDay
				};
			}
			let params = {
				todayBtn: "linked",
				clearBtn: true,
				language: CONFIG.langKey,
				starts: defaultFirstDay,
				autoclose: true,
				todayHighlight: true,
			};
			if (typeof customParams !== 'undefined') {
				params = jQuery.extend(params, customParams);
			}
			elements.datepicker(params);
		},

		/**
		 * Register dateRangePicker
		 * @param {jQuery} parentElement
		 * @param {object} customParams
		 */
		registerRange(parentElement, customParams) {
			if (typeof parentElement === 'undefined') {
				parentElement = jQuery('body');
			} else {
				parentElement = jQuery(parentElement);
			}
			let elements = jQuery('.dateRangeField', parentElement);
			if (parentElement.hasClass('dateRangeField')) {
				elements = parentElement;
			}
			if (elements.length === 0) {
				return;
			}
			let format = CONFIG.dateFormat.toUpperCase();
			const elementDateFormat = elements.data('dateFormat');
			if (typeof elementDateFormat !== 'undefined') {
				format = elementDateFormat.toUpperCase();
			}
			const defaultFirstDay = typeof CONFIG.firstDayOfWeekNo === 'undefined' ? 1 : CONFIG.firstDayOfWeekNo;
			let ranges = {};
			ranges[app.vtranslate('JS_TODAY')] = [moment(), moment()];
			ranges[app.vtranslate('JS_YESTERDAY')] = [moment().subtract(1, 'days'), moment().subtract(1, 'days')];
			ranges[app.vtranslate('JS_LAST_7_DAYS')] = [moment().subtract(6, 'days'), moment()];
			ranges[app.vtranslate('JS_CURRENT_MONTH')] = [moment().startOf('month'), moment().endOf('month')];
			ranges[app.vtranslate('JS_LAST_MONTH')] = [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')];
			ranges[app.vtranslate('JS_LAST_3_MONTHS')] = [moment().subtract(3, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')];
			ranges[app.vtranslate('JS_LAST_6_MONTHS')] = [moment().subtract(6, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')];
			let params = {
				autoUpdateInput: false,
				autoApply: true,
				ranges: ranges,
				opens: "center",
				locale: {
					format: format,
					separator: ",",
					applyLabel: app.vtranslate('JS_APPLY'),
					cancelLabel: app.vtranslate('JS_CANCEL'),
					fromLabel: app.vtranslate('JS_FROM'),
					toLabel: app.vtranslate('JS_TO'),
					customRangeLabel: app.vtranslate('JS_CUSTOM'),
					weekLabel: app.vtranslate('JS_WEEK').substr(0, 1),
					firstDay: defaultFirstDay,
					daysOfWeek: App.Fields.Date.daysTranslated,
					monthNames: App.Fields.Date.fullMonthsTranslated,
				},
			};
			if (typeof customParams !== 'undefined') {
				params = jQuery.extend(params, customParams);
			}
			elements.each(function (index, element) {
				element = $(element);
				element.daterangepicker(params);
				element.on('apply.daterangepicker', function (ev, picker) {
					$(this).val(picker.startDate.format(format) + ',' + picker.endDate.format(format));
				});
			});

		},
	},
	DateTime: {
		/*
		 * Initialization datetime fields
		 * @param {jQuery} parentElement
		 * @param {object} customParams
		 */
		register: function (parentElement, customParams) {
			if (typeof parentElement === 'undefined') {
				parentElement = jQuery('body');
			} else {
				parentElement = jQuery(parentElement);
			}
			let elements = jQuery('.dateTimePickerField', parentElement);
			if (parentElement.hasClass('dateTimePickerField')) {
				elements = parentElement;
			}
			if (elements.length === 0) {
				return;
			}
			jQuery('.input-group-text', elements.closest('.dateTime')).on('click', function (e) {
				jQuery(e.currentTarget).closest('.dateTime').find('input.dateTimePickerField ').get(0).focus();
			});
			let dateFormat = CONFIG.dateFormat.toUpperCase();
			const elementDateFormat = elements.data('dateFormat');
			if (typeof elementDateFormat !== 'undefined') {
				dateFormat = elementDateFormat.toUpperCase();
			}
			let hourFormat = CONFIG.hourFormat;
			const elementHourFormat = elements.data('hourFormat');
			if (typeof elementHourFormat !== 'undefined') {
				hourFormat = elementHourFormat;
			}
			let timePicker24Hour = true;
			let timeFormat = 'hh:mm';
			if (hourFormat !== 24) {
				timePicker24Hour = false;
				timeFormat = 'hh:mm A';
			}
			const format = dateFormat + ' ' + timeFormat;
			let params = {
				singleDatePicker: true,
				showDropdowns: true,
				timePicker: true,
				timePicker24Hour: timePicker24Hour,
				timePickerIncrement: 1,
				autoUpdateInput: true,
				autoApply: true,
				opens: "left",
				locale: {
					format: format,
					separator: ",",
					applyLabel: app.vtranslate('JS_APPLY'),
					cancelLabel: app.vtranslate('JS_CANCEL'),
					fromLabel: app.vtranslate('JS_FROM'),
					toLabel: app.vtranslate('JS_TO'),
					customRangeLabel: app.vtranslate('JS_CUSTOM'),
					weekLabel: app.vtranslate('JS_WEEK').substr(0, 1),
					firstDay: defaultFirstDay,
					daysOfWeek: App.Fields.Date.daysTranslated,
					monthNames: App.Fields.Date.fullMonthsTranslated,
				},
			};
			if (typeof customParams !== 'undefined') {
				params = jQuery.extend(params, customParams);
			}
			elements.each(function (index, element) {
				$(element).daterangepicker(params).on('apply.daterangepicker', function applyDateRangePickerHandler(ev, picker) {
					$(this).val(picker.startDate.format(format));
				});
			});
		},
	},
	Colors: {
		/**
		 * Function to check whether the color is dark or light
		 */
		getColorContrast: function (hexcolor) {
			var r = parseInt(hexcolor.substr(0, 2), 16);
			var g = parseInt(hexcolor.substr(2, 2), 16);
			var b = parseInt(hexcolor.substr(4, 2), 16);
			var yiq = ((r * 299) + (g * 587) + (b * 114)) / 1000;
			return (yiq >= 128) ? 'light' : 'dark';
		},
		getRandomColor: function () {
			var letters = '0123456789ABCDEF'.split('');
			var color = '#';
			for (var i = 0; i < 6; i++) {
				color += letters[Math.floor(Math.random() * 16)];
			}
			return color;
		},
		getRandomColors: function (count) {
			const colors = [];
			for (var i = 0; i < count; i++) {
				colors.push(this.getRandomColor());
			}
			return colors;
		}
	},
	Password: {
		/**
		 * Register clip
		 * @param {string} key
		 * @returns {ClipboardJS}
		 */
		registerCopyClipboard: function (key) {
			if (key == undefined) {
				key = '.clipboard';
			}
			return new ClipboardJS(key, {
				text: function (trigger) {
					Vtiger_Helper_Js.showPnotify({
						text: app.vtranslate('JS_NOTIFY_COPY_TEXT'),
						type: 'success'
					});
					trigger = jQuery(trigger);
					var element = jQuery(trigger.data('copyTarget'));
					var val;
					if (typeof trigger.data('copyType') !== 'undefined') {
						if (element.is("select")) {
							val = element.find('option:selected').data(trigger.data('copyType'));
						} else {
							val = element.data(trigger.data('copyType'));
						}
					} else if (typeof trigger.data('copy-attribute') !== 'undefined') {
						val = trigger.data(trigger.data('copy-attribute'));
					} else {
						val = element.val();
					}
					return val;
				}
			});
		},
	},
	Text: {
		/*
		 * Initialization CkEditor
		 * @param {jQuery} parentElement
		 * @param {Object} params
		 */
		registerCkEditor: function (parentElement, params) {
			if (typeof parentElement == 'undefined') {
				parentElement = jQuery('body');
			} else {
				parentElement = jQuery(parentElement);
			}
			if (parentElement.hasClass('js-ckeditor') && !parentElement.prop('disabled')) {
				var elements = parentElement;
			} else {
				var elements = jQuery('.js-ckeditor:not([disabled])', parentElement);
			}
			if (elements.length == 0) {
				return;
			}
			$.each(elements, function (key, element) {
				var ckEditorInstance = new Vtiger_CkEditor_Js();
				ckEditorInstance.loadCkEditor($(element), params);
			});
		},
		/**
		 * Destroy ckEditor
		 * @param {jQuery} element
		 */
		destroyCkEditor: function (element) {
			if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances && element.attr('id') in CKEDITOR.instances) {
				CKEDITOR.instances[element.attr('id')].destroy();
			}
		},
		/**
		 * Generate random character
		 * @returns {string}
		 */
		generateRandomChar: function () {
			const chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZ";
			const rand = Math.floor(Math.random() * chars.length);
			return chars.substring(rand, rand + 1);
		},
		/**
		 * generate random hash
		 * @returns {string}
		 */
		generateRandomHash(prefix = '') {
			prefix = prefix.toString();
			const hash = Math.random().toString(36).substr(2, 9) + '-' + Math.random().toString(36).substr(2, 9) + '-' + new Date().valueOf();
			return prefix ? prefix + '-' + hash : hash;
		}

	},
	Picklist: {
		/**
		 * Function which will convert ui of select boxes.
		 * @params parent - select element
		 * @params view - select2
		 * @params viewParams - select2 params
		 * @returns jquery object list which represents changed select elements
		 */
		changeSelectElementView: function (parent, view, viewParams) {
			if (typeof parent === 'undefined') {
				parent = $('body');
			}
			if (typeof view === 'undefined') {
				const select2Elements = $('select.select2', parent).toArray();
				const selectizeElements = $('select.selectize', parent).toArray();
				const choosenElements = $('.chzn-select', parent).toArray();
				select2Elements.forEach((elem) => {
					this.changeSelectElementView($(elem), 'select2');
				});
				selectizeElements.forEach((elem) => {
					this.changeSelectElementView($(elem), 'selectize');
				});
				choosenElements.forEach((elem) => {
					this.changeSelectElementView($(elem), 'choosen');
				});
				return;
			}
			//If view is select2, This will convert the ui of select boxes to select2 elements.
			if (typeof view === 'string') {
				switch (view) {
					case 'select2':
						return App.Fields.Picklist.showSelect2ElementView(parent, viewParams);
					case 'selectize':
						return App.Fields.Picklist.showSelectizeElementView(parent, viewParams);
					case 'choosen':
						return App.Fields.Picklist.showChoosenElementView(parent, viewParams);
				}
				app.errorLog(new Error(`Unknown select type [${view}]`));
			}
		},
		/**
		 * Function which will show the select2 element for select boxes . This will use select2 library
		 */
		showSelect2ElementView: function (selectElement, params) {
			if (typeof params === 'undefined') {
				params = {};
			}
			let data = selectElement.data();
			if (data != null) {
				params = jQuery.extend(data, params);
			}
			params.language = {};
			params.theme = "bootstrap4";
			params.width = "100%";
			params.language.noResults = function (msn) {
				return app.vtranslate('JS_NO_RESULTS_FOUND');
			};

			// Sort DOM nodes alphabetically in select box.
			if (typeof params['customSortOptGroup'] != 'undefined' && params['customSortOptGroup']) {
				jQuery('optgroup', selectElement).each(function () {
					var optgroup = jQuery(this);
					var options = optgroup.children().toArray().sort(function (a, b) {
						var aText = jQuery(a).text();
						var bText = jQuery(b).text();
						return aText < bText ? 1 : -1;
					});
					jQuery.each(options, function (i, v) {
						optgroup.prepend(v);
					});
				});
				delete params['customSortOptGroup'];
			}

			//formatSelectionTooBig param is not defined even it has the maximumSelectionLength,
			//then we should send our custom function for formatSelectionTooBig
			if (typeof params.maximumSelectionLength != "undefined" && typeof params.formatSelectionTooBig == "undefined") {
				var limit = params.maximumSelectionLength;
				//custom function which will return the maximum selection size exceeds message.
				var formatSelectionExceeds = function (limit) {
					return app.vtranslate('JS_YOU_CAN_SELECT_ONLY') + ' ' + limit.maximum + ' ' + app.vtranslate('JS_ITEMS');
				}
				params.language.maximumSelected = formatSelectionExceeds;
			}
			if (typeof selectElement.attr('multiple') != 'undefined' && !params.placeholder) {
				params.placeholder = app.vtranslate('JS_SELECT_SOME_OPTIONS');
			} else if (!params.placeholder) {
				params.placeholder = app.vtranslate('JS_SELECT_AN_OPTION');
			}
			if (typeof params.templateResult === 'undefined') {
				params.templateResult = function (data, container) {
					if (data.element && data.element.className) {
						$(container).addClass(data.element.className);
					}
					if (typeof data.name == 'undefined') {
						return data.text;
					}
					if (data.type == 'optgroup') {
						return '<strong>' + data.name + '</strong>';
					} else {
						return '<span>' + data.name + '</span>';
					}
				};
			}
			if (typeof params.templateSelection === 'undefined') {
				params.templateSelection = function (data, container) {
					if (data.element && data.element.className) {
						$(container).addClass(data.element.className);
					}
					if (data.text === '') {
						return data.name;
					}
					return data.text;
				};
			}
			if (selectElement.data('ajaxSearch') === 1) {
				params.tags = false;
				params.language.searching = function () {
					return app.vtranslate('JS_SEARCHING');
				}
				params.language.inputTooShort = function (args) {
					var remainingChars = args.minimum - args.input.length;
					return app.vtranslate('JS_INPUT_TOO_SHORT').replace("_LENGTH_", remainingChars);
				}
				params.language.errorLoading = function () {
					return app.vtranslate('JS_NO_RESULTS_FOUND');
				}
				params.placeholder = '';
				params.ajax = {
					url: selectElement.data('ajaxUrl'),
					dataType: 'json',
					delay: 250,
					data: function (params) {
						return {
							value: params.term, // search term
							page: params.page
						};
					},
					processResults: function (data, params) {
						var items = new Array;
						if (data.success == true) {
							selectElement.find('option').each(function () {
								var currentTarget = $(this);
								items.push({
									label: currentTarget.html(),
									value: currentTarget.val(),
								});
							});
							items = items.concat(data.result.items);
						}
						return {
							results: items,
							pagination: {
								more: false
							}
						};
					},
					cache: false
				};
				params.escapeMarkup = function (markup) {
					if (markup !== 'undefined')
						return markup;
				};
				var minimumInputLength = 3;
				if (selectElement.data('minimumInput') != 'undefined') {
					minimumInputLength = selectElement.data('minimumInput');
				}
				params.minimumInputLength = minimumInputLength;
				params.templateResult = function (data) {
					if (typeof data.name == 'undefined') {
						return data.text;
					}
					if (data.type == 'optgroup') {
						return '<strong>' + data.name + '</strong>';
					} else {
						return '<span>' + data.name + '</span>';
					}
				};
				params.templateSelection = function (data, container) {
					if (data.text === '') {
						return data.name;
					}
					return data.text;
				};
			}
			var selectElementNew = selectElement;
			selectElement.each(function (e) {
				var select = $(this);
				if (select.attr('readonly') == 'readonly' && !select.attr('disabled')) {
					var selectNew = select.clone().addClass('d-none');
					select.parent().append(selectNew);
					select.prop('disabled', true);
				}
				if (select.hasClass('tags')) {
					params.tags = true;
				}
				select.select2(params)
					.on("select2:open", function (e) {
						if (select.data('unselecting')) {
							select.removeData('unselecting');
							setTimeout(function (e) {
								select.each(function () {
									jQuery(this).select2('close');
								});
							}, 1);
						}
						var element = jQuery(e.currentTarget);
						var instance = element.data('select2');
						instance.$dropdown.css('z-index', 1000002);
					}).on("select2:unselect", function (e) {
					select.data('unselecting', true);
				});
			})
			return selectElement;
		},
		/**
		 * Replace select with choosen
		 * @param {jQuery} parent
		 * @param {object} viewParams
		 */
		showChoosenElementView(parent, viewParams) {
			selectElement = jQuery('.chzn-select', parent);
			//parent itself is the element
			if (parent.is('select.chzn-select')) {
				selectElement = parent;
			}
			// generate random ID
			selectElement.each(function () {
				if ($(this).prop("id").length === 0) {
					$(this).attr('id', "sel" + App.Fields.Text.generateRandomChar() + App.Fields.Text.generateRandomChar() + App.Fields.Text.generateRandomChar());
				}
			});
			//fix for multiselect error prompt hide when validation is success
			selectElement.filter('[multiple]').filter('[data-validation-engine*="validate"]').on('change', function (e) {
				jQuery(e.currentTarget).trigger('focusout');
			});
			let params = {
				no_results_text: app.vtranslate('JS_NO_RESULTS_FOUND') + ':'
			};
			const moduleName = app.getModuleName();
			if (selectElement.filter('[multiple]') && moduleName !== 'Install') {
				params.placeholder_text_multiple = ' ' + app.vtranslate('JS_SELECT_SOME_OPTIONS');
			}
			if (moduleName !== 'Install') {
				params.placeholder_text_single = ' ' + app.vtranslate('JS_SELECT_AN_OPTION');
			}
			selectElement.chosen(params);
			selectElement.each(function () {
				const select = $(this);
				// hide selected items in the chosen instance when item is hidden.
				if (select.hasClass('hideSelected')) {
					const ns = [];
					select.find('optgroup,option').each(function (n, e) {
						if (jQuery(this).hasClass('d-none')) {
							ns.push(n);
						}
					});
					if (ns.length) {
						select.next().find('.search-choice-close').each(function (n, e) {
							if (jQuery.inArray($(this).data('option-array-index'), ns) !== -1) {
								$(this).closest('li').remove();
							}
						})
					}
				}
				if (select.attr('readonly') === 'readonly') {
					select.on('chosen:updated', function () {
						if (select.attr('readonly')) {
							let selectData = select.data('chosen');
							select.attr('disabled', 'disabled');
							if (typeof selectData === 'object') {
								selectData.search_field_disabled();
							}
							if (select.is(':disabled')) {
								select.attr('disabled', 'disabled');
							} else {
								select.removeAttr('disabled');
							}
						}
					});
					select.trigger('chosen:updated');
				}
			});
			// Improve the display of default text (placeholder)
			return jQuery('.chosen-container-multi .default, .chosen-container').css('width', '100%');
		},
		/**
		 * Function to destroy the chosen element and get back the basic select Element
		 */
		destroyChosenElement: function (parent) {
			if (typeof parent === 'undefined') {
				parent = jQuery('body');
			}
			let selectElement = jQuery('.chzn-select', parent);
			//parent itself is the element
			if (parent.is('select.chzn-select')) {
				selectElement = parent;
			}
			return selectElement.css('display', 'block').removeClass("chzn-done").data("chosen", null).next().remove();
		},

		/**
		 * Function which will show the selectize element for select boxes . This will use selectize library
		 */
		showSelectizeElementView: function (selectElement, params) {
			if (typeof params === 'undefined') {
				params = {plugins: ['remove_button']};
			}
			selectElement.selectize(params);
			return selectElement;
		},
		/**
		 * Function to destroy the selectize element
		 */
		destroySelectizeElement: function (parent) {
			if (typeof parent === 'undefined') {
				parent = jQuery('body');
			}
			let selectElements = jQuery('.selectized', parent);
			//parent itself is the element
			if (parent.is('select.selectized')) {
				selectElements = parent;
			}
			selectElements.each(function () {
				$(this)[0].selectize.destroy();
			});
		},
	},
	MultiImage: {
		/**
		 * Register multi image upload
		 *
		 * @param {jQuery.Class} thisInstance - instance of class
		 */
		register(container) {
			$(document).bind('drop dragover', function (e) {
				// prevent default browser drop behaviour
				e.preventDefault();
			});
			const fileUploads = $('.c-multi-image .c-multi-image__file');
			fileUploads.fileupload({
				dataType: 'json',
				autoUpload: false,
				acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
				done(e, data) {
					$.each(data.result.files, function (index, file) {
						console.log('file', file);
					});
				},
				submit(e, data) {
					data.formData = {
						'hashes[]': $(this).closest('.c-multi-image').find('input[name="hashes[]"]').val()
					};
				},
				add(e, data) {
					const component = $(this).closest('.c-multi-image');
					data.files.forEach((file) => {
						if (typeof file.hash === 'undefined') {
							file.hash = App.Fields.Text.generateRandomHash(CONFIG.userId);
							$(component).find('input[name="hashes[]"]').val([file.hash]);
						}
					});
					$(component).find('.c-multi-image__progress').removeClass('d-none');
					data.submit()
						.success((result, textStatus, jqXHR) => {
							console.log('upload success', this);
							$(component).find('.c-multi-image__progress').addClass('d-none')
								.find('.c-multi-image__progress-bar').css({width: "0%"});
						})
						.error((jqXHR, textStatus, errorThrown) => {
							console.log('error', this, errorThrown.message);
						})
						.complete((result, textStatus, jqXHR) => {
							console.log('upload complete', this, textStatus);
							$(component).find('.c-multi-image__progress').addClass('d-none')
								.find('.c-multi-image__progress-bar').css({width: "0%"});
						});
				},
				progressall(e, data) {
					const progress = parseInt(data.loaded / data.total * 100, 10);
					$(this).closest('.c-multi-image').find('.c-multi-image__progress-bar').css({width: progress + "%"});
				},
				change(e, data) {
					return App.Fields.MultiImage.change.call(this, e, data);
				},
				drop(e, data) {
					return App.Fields.MultiImage.change.call(this, e, data);
				}
			});
			$(fileUploads).each(function () {
				$(this).fileupload('option', 'dropZone', $(this).closest('.c-multi-image'));
			});
			$(document).on('click', '.c-multi-image__preview__popover-img', function (e) {
				const fileInfo = App.Fields.MultiImage.getFileInfo.call(this);
				App.Fields.MultiImage.zoomPreview(fileInfo.hash);
			});
			$(document).on('click', '.c-multi-image__preview__popover-btn-zoom', function (e) {
				const fileInfo = App.Fields.MultiImage.getFileInfo.call(this);
				App.Fields.MultiImage.zoomPreview(fileInfo.hash);
			});
			$(document).on('dblclick', '.c-multi-image__preview-img', function (e) {
				const fileInfo = App.Fields.MultiImage.getFileInfo.call(this);
				App.Fields.MultiImage.zoomPreview(fileInfo.hash);
			});
			$(document).on('click', '.c-multi-image__preview__popover-btn-delete', function (e) {
				const fileInfo = App.Fields.MultiImage.getFileInfo.call(this);
				App.Fields.MultiImage.deleteFile(fileInfo.hash);
			});
		},
		/**
		 * Get file info from element with data-hash attribute or hash argument
		 * @param {boolean|string} hash
		 * @returns {{hash: string, filename: string, imageSrc: string}}
		 */
		getFileInfo(hash = false) {
			if (!hash) {
				hash = $(this).data('hash');
			}
			const previewElement = $('#c-multi-image__preview-hash-' + hash);
			const image = $(previewElement).find('.c-multi-image__preview-img').eq(0);
			return {
				image,
				filename: $(image).data('filename'),
				imageSrc: $(image).data('image'),
				previewElement
			};
		},
		/**
		 * Display modal window with large preview
		 *
		 * @param {string} hash
		 */
		zoomPreview(hash) {
			const fileInfo = App.Fields.MultiImage.getFileInfo(hash);
			bootbox.dialog({
				size: 'large',
				backdrop: true,
				onEscape: true,
				title: `<i class="fa fa-image"></i> ${fileInfo.filename}`,
				message: `<img src="${fileInfo.imageSrc}" class="w-100" />`,
				buttons: {
					Delete: {
						label: `<i class="fa fa-trash-alt"></i> ${app.vtranslate('JS_DELETE')}`,
						className: "float-left btn btn-danger",
						callback() {
							App.Fields.MultiImage.deleteFile(fileInfo.hash);
						}
					},
					Close: {
						label: `<i class="fa fa-times"></i> ${app.vtranslate('JS_CLOSE')}`,
						className: "btn btn-default",
						callback: () => {
						},
					}
				}
			});

		},
		/**
		 * Delete image from server
		 * Should be called with this pointing on button element with data-hash attribute
		 * @param {string} hash
		 */
		deleteFile(hash) {
			const fileInfo = App.Fields.MultiImage.getFileInfo(hash);
			bootbox.confirm({
				title: `<i class="fa fa-trash-alt"></i> ${app.vtranslate("JS_DELETE_FILE")}`,
				message: `${app.vtranslate("JS_DELETE_FILE_CONFIRMATION")} <span class="font-weight-bold">${fileInfo.filename}</span>?`,
				callback: function (result) {
					if (result) {
						fileInfo.previewElement.popover('dispose').remove();
					}
				}
			});
		},
		/**
		 * File change event
		 * Should be called with this pointing on file input element inside .c-multi-image
		 *
		 * @param {Event} e
		 * @param {object} data
		 */
		change(e, data) {
			App.Fields.MultiImage.generatePreviewElements(data.files, (element) => {
				const resultsElement = $(this).closest('.c-multi-image').find('.c-multi-image__result');
				resultsElement.append(element);
			});
		}
		,
		/**
		 * Generate and apply popover to preview
		 *
		 * @param {File} file
		 * @param {string} template
		 * @param {string} imageSrc
		 * @returns {*|jQuery}
		 */
		addPreviewPopover(file, template, imageSrc) {
			return $(template).popover({
				title: `<div class="u-text-ellipsis"><i class="fa fa-image"></i> ${file.name}</div>`,
				html: true,
				trigger: 'focus',
				placement: 'top',
				content: `<img src="${imageSrc}" class="w-100 c-multi-image__preview__popover-img" data-hash="${file.hash}" />`,
				template: `<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div><div class="text-right popover-footer c-multi-image__preview__popover-actions">
		<button class="btn btn-sm btn-danger c-multi-image__preview__popover-btn-delete" data-hash="${file.hash}" title="${app.vtranslate('JS_DELETE')}"><i class="fa fa-trash-alt"></i></button>
		<button class="btn btn-sm btn-primary c-multi-image__preview__popover-btn-zoom" data-hash="${file.hash}" title="${app.vtranslate('JS_ZOOM')}"><i class="fa fa-search-plus"></i></button>
	</div></div>`
			});
		}
		,
		/**
		 * Generate preview of images and append to multi image results view
		 *
		 * @param {Array} files - array of Files
		 * @param {function} callback
		 */
		generatePreviewElements(files, callback) {
			files.forEach((file, index) => {
				if (file instanceof File) {
					App.Fields.MultiImage.generatePreviewFromFile(file, (template, imageSrc) => {
						file.preview = App.Fields.MultiImage.addPreviewPopover(file, template, imageSrc);
						callback(file.preview);
					});
				} else {

				}
			});
		}
		,
		/**
		 * Generate preview of image as html string
		 * @param {File} file
		 * @param {function} callback
		 */
		generatePreviewFromFile(file, callback) {
			const fr = new FileReader();
			fr.onload = function fileReaderLoadCallback() {
				file.imageSrc = fr.result;
				callback(`<div class="d-inline-block mr-1 mb-1 c-multi-image__preview" id="c-multi-image__preview-hash-${file.hash}" data-hash="${file.hash}">
		<div class="img-thumbnail c-multi-image__preview-img" style="background-image:url(${fr.result})" tabindex="0" data-hash="${file.hash}" data-filename="${file.name}" data-image="${fr.result}" title="${file.name}"></div>
</div>`, fr.result);
			};
			fr.readAsDataURL(file);
		}
		,
	}
}
