/* {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} */

'use strict';

jQuery.Class('KnowledgeBase_Tree_Js', {
	registerEvents: function() {
		DocView.mount({
			el: '#KnowledgeDocView',
			moduleName: 'KnowledgeBase'
		});
	}
});
