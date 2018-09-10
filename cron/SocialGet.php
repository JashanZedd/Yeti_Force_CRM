<?php
/**
 * Cron for downloading messages from social media.
 *
 * @copyright YetiForce Sp. z o.o
 * @license   YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Arkadiusz Adach <a.adach@yetiforce.com>
 */
$socialMediaType = [];
foreach (\App\SocialMedia\SocialMedia::ALLOWED_UITYPE as $uiType) {
	if (\App\SocialMedia\SocialMedia::isConfigured($uiType)) {
		$socialMediaType[] = \App\SocialMedia\SocialMedia::getSocialMediaType($uiType);
	}
}
if (count($socialMediaType) > 0) {
	foreach (\App\SocialMedia\SocialMedia::getSocialMediaAccount($socialMediaType) as $socialMedia) {
		$socialMedia->retrieveDataFromApi();
	}
} else {
	\App\Log::info('The Social API is unconfigured');
}
