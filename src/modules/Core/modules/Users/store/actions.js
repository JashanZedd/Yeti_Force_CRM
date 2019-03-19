/* {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} */
import loginAxios from 'services/Login.js'
import getters from 'store/getters.js'
import mutations from 'store/mutations.js'

export default {
  /**
   * Fetch view data
   *
   * @param {object} state
   */
  fetchData({ commit, rootGetters }, view) {
    loginAxios({
      url: rootGetters[getters.Core.Url.get](`Users.${view}.getData`),
      method: 'POST'
    }).then(response => {
      commit('Global/update', { App: response.data.env })
    })
    //TODO commit to remove when rootGetters[getters.Url.all].Users.getData is ready
    commit('Global/update', {
      App: {
        Env: {
          layout: 'material',
          layouts: ['material', 'ios']
        },
        Language: {
          defaultLanguage: 'en-US',
          lang: 'en-US',
          langs: ['pl-PL', 'en-US']
        },
        Users: {
          isBlockedIp: false,
          message: '',
          messageType: '',
          loginPageRememberCredentials: true,
          forgotPassword: true,
          languageSelection: true,
          layoutSelection: true
        }
      }
    })
  },
  /**
   * Login action
   *
   * @param   {object}  store
   * @param   {object}  formData
   */
  login({ commit, rootGetters }, formData) {
    loginAxios({
      url: rootGetters[getters.Core.Url.get]('Users.Login.login'),
      data: formData,
      method: 'POST'
    }).then(response => {
      const data = response.data
      if (data.result === true) {
        commit('Global/update', { App: data.env })
        this.$router.replace('/')
      } else if (data.result.step !== undefined) {
        this.$router.replace(`/app/users/login/${data.result.step}`)
      } else {
        return console.error('Server error', response)
      }
    })
  },
  /**
   * Logout action
   *
   * @param   {object}  store
   */
  logout({ commit, rootGetters }) {
    loginAxios({
      url: rootGetters[getters.Core.Url.get]('Users.Login.logout'),
      method: 'POST'
    }).then(response => {
      const data = response.data
      if (data.result === true) {
        commit(mutations.Core.Users.isLoggedIn, false)
        this.$router.replace('/app/core/users/login')
      }
    })
  },
  /**
   * Remind action
   *
   * @param   {object}  store
   * @param   {object}  formData
   */
  remind({ commit, rootGetters }, formData) {
    loginAxios({
      url: rootGetters[getters.Core.Url.get]('Users.remind'),
      data: formData,
      method: 'POST'
    }).then(response => {
      this.$router.replace('/app/users/login/form')
    })
  }
}
