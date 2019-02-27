/* {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} */
export function AUTH_USER(state, userData) {
  state.tokenId = userData.tokenId
  state.userId = userData.userId
  state.userName = userData.userName
  state.admin = userData.admin
}

export function STORE_USER(state, user) {
  state.user = user
}

export function CLEAR_AUTH_DATA(state) {
  state.tokenId = null
  state.userId = null
  state.userName = null
  state.admin = null
}
