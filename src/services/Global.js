/* {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} */
import axios from 'axios'

const apiClient = axios.create({
  baseURL: window.CONFIG.baseURL, //dev path,
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json'
  },
  timeout: 10000
})

export default apiClient
