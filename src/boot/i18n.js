/* {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} */
import VueI18n from 'vue-i18n'
import messages from 'src/i18n'

export default async ({ app, Vue }) => {
  Vue.use(VueI18n)
  // Set i18n instance on app
  app.i18n = new VueI18n({
    fallbackLocale: '_Base',
    messages
  })
}
