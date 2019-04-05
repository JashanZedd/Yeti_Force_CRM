/* {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} */
'use strict'
let connection = null
function connect() {
  if (connection === null) {
    // connection = new WebSocket('ws://127.0.0.1:1337')
    // connection.onopen = function(data) {
    //   console.log('opened')
    //   console.log(data)
    //   return connection

    // connection is opened and ready to use
    return new Promise(function(resolve, reject) {
      connection = new WebSocket('ws://127.0.0.1:1337')
      connection.onopen = function() {
        console.log('conn')
        console.log(connection)
        resolve(connection)
      }
      connection.onerror = function(err) {
        reject(err)
      }
    })
  } else {
    return connection
  }
}
export default connect()
