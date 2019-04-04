/* {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} */
import Objects from '/src/utilities/Objects.js'

export default {
  /**
   * Get urls
   *
   * @param {object} state
   *
   * @returns {object}
   */
  get(state) {
    return path => {
      return Objects.get(state.url, path)
    }
  }
}
