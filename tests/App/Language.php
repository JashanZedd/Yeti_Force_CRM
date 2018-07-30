<?php
/**
 * Languages test class.
 *
 * @copyright YetiForce Sp. z o.o
 * @license   YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Sławomir Kłos <s.klos@yetiforce.com>
 */

namespace Tests\App;

class Language extends \Tests\Base
{
	/**
	 * Testing get language function.
	 */
	public function testGetLanguage()
	{
		\App\Session::set('language', 'pl_pl');
		$currLang = \App\Language::getLanguage();
		$this->assertSame($currLang, \App\Language::getLanguage());
		$this->assertSame(explode('_', $currLang), explode('-', strtolower(\App\Language::getLanguageTag())));
		$this->assertSame('Język Polski', \App\Language::getLanguageLabel('pl_pl'));
		$this->assertSame('SINGLE_Leads', \App\Language::getSingularModuleName('Leads'));
		$this->assertSame('Lead', \App\Language::translateSingularModuleName('Leads'));
	}

	/**
	 * Testing get all languages data.
	 */
	public function testGetAll()
	{
		$this->assertNotEmpty(\App\Language::getAll(true, true));
		$this->assertNotEmpty(\App\Language::getAll(true, true));
	}

	/**
	 * Testing get language info by prefix.
	 */
	public function testGetLangInfo()
	{
		$this->assertNotEmpty(\App\Language::getLangInfo('pl_pl'));
	}

	/**
	 * Testing init locale function.
	 */
	public function testInitLocale()
	{
		$this->assertNull(\App\Language::initLocale());
	}
}
