import Vue from 'vue'
import VueRouter from 'vue-router'

import routes from './routes'
import ModuleLoader from './ModuleLoader'

// Load module routes
if (typeof window.modules === 'object') {
  for (const moduleName in window.modules) {
    const moduleConf = window.modules[moduleName]
    ModuleLoader.attachRoutes(routes, moduleConf)
  }
}

Vue.use(VueRouter)

/*
 * If not building with SSR mode, you can
 * directly export the Router instantiation
 */

export default function(/* { store, ssrContext } */) {
  const Router = new VueRouter({
    scrollBehavior: () => ({ y: 0 }),
    routes,

    // Leave these as is and change from quasar.conf.js instead!
    // quasar.conf.js -> build -> vueRouterMode
    // quasar.conf.js -> build -> publicPath
    mode: process.env.VUE_ROUTER_MODE,
    base: '/'
  })

  return Router
}
