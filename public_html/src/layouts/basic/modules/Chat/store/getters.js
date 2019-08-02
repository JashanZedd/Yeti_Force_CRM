/* {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} */
export default {
	dialog(state) {
		return state.session.dialog
	},
	miniMode(state) {
		return state.session.miniMode
	},
	leftPanel(state) {
		return state.session.leftPanel
	},
	rightPanel(state) {
		return state.session.rightPanel
	},
	historyTab(state) {
		return state.session.historyTab
	},
	isSearchActive(state) {
		return state.session.isSearchActive
	},
	tab(state) {
		return state.session.tab
	},
	coordinates(state) {
		return state.session.coordinates
	},
	buttonCoordinates(state) {
		return state.session.buttonCoordinates
	},
	isSoundNotification(state) {
		return state.local.isSoundNotification === null
			? state.config.isDefaultSoundNotification
			: state.local.isSoundNotification
	},
	roomSoundNotificationsOff(state) {
		return state.local.roomSoundNotificationsOff
	},
	sendByEnter(state) {
		return state.local.sendByEnter
	},

	isDesktopNotification(state) {
		return state.local.isDesktopNotification
	},
	data(state) {
		return state.data
	},
	config(state) {
		return state.config
	}
}
