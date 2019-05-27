/* {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} */

const rollup = require('rollup'),
	finder = require('findit')('layouts'),
	path = require('path'),
	sourcemaps = require('rollup-plugin-sourcemaps'),
	vue = require('rollup-plugin-vue'),
	commonjs = require('rollup-plugin-commonjs'),
	resolve = require('rollup-plugin-node-resolve'),
	globals = require('rollup-plugin-node-globals'),
	json = require('rollup-plugin-json'),
	{ terser } = require('rollup-plugin-terser')

let filesToMin = []
async function build(filePath) {
	const outputFile = `../${filePath.replace('.js', '.vue.js')}`
	const inputOptions = {
		input: filePath,
		plugins: [json(), resolve(), commonjs(), vue({ compileTemplate: true }), globals(), terser()]
	}
	const outputOptions = {
		sourcemap: true,
		file: outputFile,
		format: 'cjs'
	}
	const bundle = await rollup.rollup(inputOptions)
	const { code, map } = await bundle.generate(outputOptions)
	await bundle.write(outputOptions)
}

finder.on('directory', (dir, stat, stop) => {
	const base = path.basename(dir)
	if (base === 'node_modules' || base === 'libraries' || base === 'vendor' || base === '_private') stop()
})

finder.on('file', (file, stat) => {
	const re = new RegExp('(?<!\\.min)\\.js$')
	if (file.includes('roundcube') && !(!file.includes('skins') && file.includes('yetiforce'))) return
	if (file.match(re)) filesToMin.push(file)
})

finder.on('end', () => {
	filesToMin.forEach(file => {
		console.log(file)
		build(file)
	})
})
