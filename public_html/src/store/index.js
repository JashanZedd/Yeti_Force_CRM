/**
 * Vuex store
 *
 * @description Vuex store initialization
 * @license YetiForce Public License 3.0
 * @author Tomasz Poradzewski <t.poradzewski@yetiforce.com>
 */

import Vuex from 'vuex'

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'

const vuexStore = new Vuex.Store({
	strict: debug
})

export default vuexStore
