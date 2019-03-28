/* {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} */
export default {
  /**
   * Check if user is logged in
   *
   * @param   {null|string}  state
   *
   * @return  {bool}
   */
  isLoggedIn(state) {
    return state.isLoggedIn !== false
  },
  getMessage(state) {
    return state.message
  }
}
