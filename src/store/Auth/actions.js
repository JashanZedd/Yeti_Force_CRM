/* {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} */
import loginAxios from '../../services/Auth.js'
import globalAxios from '../../services/Global.js'
import { LocalStorage } from 'quasar'
import actions from '../actions.js'
import mutations from '../mutations.js'

export default {
  [actions.Auth.fetchViewData]({ commit }) {
    commit(mutations.Auth.fetchViewData, {
      LANGUAGES: ['polish', 'english', 'german'],
      IS_BLOCKED_IP: false, //bruteforce check,
      MESSAGE: '', //\App\Session::get('UserLoginMessageType'),
      MESSAGE_TYPE: '',
      LOGIN_PAGE_REMEMBER_CREDENTIALS: true, // AppConfig::security('LOGIN_PAGE_REMEMBER_CREDENTIALS')
      FORGOT_PASSWORD: true, //{if AppConfig::security('RESET_LOGIN_PASSWORD') && App\Mail::getDefaultSmtp()}
      LANGUAGE_SELECTION: true,
      DEFAULT_LANGUAGE: 'polish',
      LAYOUT_SELECTION: true,
      LAYOUTS: ['material', 'ios'] //\App\Layout::getAllLayouts()
    })
  },
  /**
   * Login action
   *
   * @param   {any}     commit
   * @param   {any}     dispatch
   * @param   {object}  user
   */
  [actions.Auth.login]({ commit, dispatch }, user) {
    loginAxios({
      url: 'login.php/',
      data: user,
      method: 'POST'
    })
      .then(response => {
        const data = response.data
        if (response.status !== 200) {
          return console.error('Server error', response)
        }
        const now = new Date()
        const expirationDate = new Date(now.getTime() + data.expiresIn * 100000)
        LocalStorage.set('tokenId', data.tokenId)
        LocalStorage.set('userId', data.userId)
        LocalStorage.set('userName', data.userName)
        LocalStorage.set('admin', data.admin)
        LocalStorage.set('expiresIn', expirationDate)
        commit(mutations.Auth.authUser, {
          tokenId: data.tokenId,
          userId: data.userId,
          admin: data.admin,
          userName: data.userName
        })
        globalAxios.defaults.headers.common['Authorization'] = data.tokenId
        dispatch(actions.Auth.setLogoutTimer, data.expiresIn)
        this.$router.replace('/')
      })
      .catch(error => console.log(error))
      .catch(err => {
        LocalStorage.remove('tokenId')
        reject(err)
      })
  },

  /**
   * Clear authentication data, when the expirationTime passed
   *
   * @param   {any}     commit
   * @param   {any}     dispatch
   * @param   {number}  expirationTime
   */
  [actions.Auth.setLogoutTimer]({ commit }, expirationTime) {
    setTimeout(() => {
      commit(mutations.Auth.clearAuthData)
    }, expirationTime * 100000)
  },

  /**
   * Try auto login on application start
   *
   * @param   {any}     commit
   */
  [actions.Auth.tryAutoLogin]({ commit }) {
    return new Promise(resolve => {
      const token = localStorage.getItem('tokenId')
      const expirationDate = new Date(localStorage.getItem('expiresIn')).getTime()
      const now = new Date().getTime()
      if (!token || now >= expirationDate) {
        commit(mutations.Auth.clearAuthData)
        resolve(false)
      } else {
        commit(mutations.Auth.authUser, {
          tokenId: token,
          userId: localStorage.getItem('userId'),
          admin: localStorage.getItem('admin'),
          userName: localStorage.getItem('userName')
        })
        resolve(true)
      }
    })
  }
}
