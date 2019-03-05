/* {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} */
import Vue from 'vue'
import VueRouter from 'vue-router'
import routes from './routes'
import ModuleLoader from './ModuleLoader.js'
import getters from '../store/getters.js'
import actions from '../store/actions.js'

import { Loading, QSpinnerGears } from 'quasar'

// Load module routes
if (typeof window.modules === 'object') {
  for (const moduleName in window.modules) {
    ModuleLoader.attachRoutes(routes, window.modules[moduleName])
  }
}

Vue.use(VueRouter)

/*
 * If not building with SSR mode, you can
 * directly export the Router instantiation
 */

export default function({ store }) {
  const Router = new VueRouter({
    scrollBehavior: () => ({ y: 0 }),
    routes,

    // Leave these as is and change from quasar.conf.js instead!
    // quasar.conf.js -> build -> vueRouterMode
    // quasar.conf.js -> build -> publicPath
    mode: store.getters[getters.Base.env.routerMode], //process.env.VUE_ROUTER_MODE,
    base: '/'
  })

  Router.beforeEach((routeTo, routeFrom, next) => {
    const setRoute = () => {
      if (store.getters[getters.Auth.isAuthenticated] || routeTo.path.startsWith('/auth')) {
        next()
      } else {
        next({ name: 'Login' })
      }
    }
    Loading.show({
      spinner: QSpinnerGears
    })
    if (!routeFrom.name) {
      store.dispatch(actions.Auth.tryAutoLogin).then(() => {
        setRoute()
      })
    } else {
      setRoute()
    }
  })
  Router.afterEach(() => {
    Loading.hide()
  })

  return Router
}
