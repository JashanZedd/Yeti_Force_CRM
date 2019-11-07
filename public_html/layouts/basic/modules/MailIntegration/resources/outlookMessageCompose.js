/* {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} */
'use strict';
const MailIntegration_Compose = {
	registerAutocompleteTemplate() {
		$.widget('ui.autocomplete', $.ui.autocomplete, {
			_renderItem: function(ul, item) {
				const listItemTemplate = user => {
					return `
							<li class="c-search-item js-search-item">
								<div class="d-flex flex-nowrap">
									<div class="d-flex flex-wrap">
										<div class="u-font-size-14px">
											${user.name}
										</div>
										<div class="c-search-item__mail small">
											${user.mail}
										</div>
									</div>
									<div class="btn-group flex-nowrap align-items-center">
										<button class="c-search-item__btn btn btn-xs btn-outline-primary" data-copy-target="cc">
											${app.vtranslate('JS_CC')}
										</button>
										<button class="c-search-item__btn btn btn-xs btn-outline-primary" data-copy-target="bcc">
											${app.vtranslate('JS_BCC')}
										</button>
									</div>
								</div>
							</li>`;
				};
				return $(listItemTemplate(item)).appendTo(ul);
			}
		});
	},
	registerAutocomplete() {
		this.container.find('.js-search-input').autocomplete({
			delay: '600',
			minLength: '3',
			classes: {
				'ui-autocomplete': 'mobile'
			},
			source: this.findEmail.bind(this),
			select: this.onRecipientSelect.bind(this)
		});
	},
	findEmail(request, callBack) {
		AppConnector.request({
			module: 'MailIntegration',
			action: 'Mail',
			mode: 'findEmail',
			search: request.term
		})
			.done(responseData => {
				const data = responseData.result.map(user => {
					let userData = user.split(' <');
					const name = userData[0];
					const mail = userData[1].slice(0, -1);
					return { name, mail };
				});
				callBack(data);
			})
			.fail(function(error) {
				console.error(error);
			});
	},
	onRecipientSelect({ toElement }, { item }) {
		const newRecipient = [
			{
				displayName: item.name,
				emailAddress: item.mail
			}
		];
		const recipientsField = toElement.dataset.copyTarget ? toElement.dataset.copyTarget : 'to';
		this.copyRecipient(recipientsField, newRecipient);
	},
	copyRecipient(recipientsField, newRecipient) {
		Office.context.mailbox.item[recipientsField].addAsync(newRecipient, function(result) {
			if (result.error) {
				Office.context.mailbox.item.notificationMessages.replaceAsync('error', {
					type: 'errorMessage',
					message: app.vtranslate('JS_ERROR') + ' ' + result.error
				});
			}
		});
	},
	registerEvents() {
		this.container = $('#page');
		this.registerAutocompleteTemplate();
		this.registerAutocomplete();
	}
};

(function($) {
	Office.onReady(info => {
		if (info.host === Office.HostType.Outlook) {
			MailIntegration_Compose.registerEvents();
		}
	});
})($);
