<?php

namespace App\SocialMedia;

/**
 * SocialMedia Twitter class.
 *
 * @copyright YetiForce Sp. z o.o
 * @license   YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Arkadiusz Adach <a.adach@yetiforce.com>
 */
class Twitter implements SocialMediaInterface
{
	/**
	 * Tweet mode.
	 *
	 * @link https://developer.twitter.com/en/docs/tweets/tweet-updates.html
	 */
	public const TWEET_MODE = 'extended';
	/**
	 * User account name.
	 *
	 * @var string
	 */
	private $userName;

	/**
	 * @var \Abraham\TwitterOAuth\TwitterOAuth
	 */
	private static $twitterConnection = null;

	/**
	 * Is configured.
	 *
	 * @throws \App\Exceptions\AppException
	 *
	 * @return bool
	 */
	public static function isConfigured()
	{
		$configTitter = \Settings_SocialMedia_Config_Model::getInstance('twitter');
		return !empty($configTitter->get('twitter_api_key')) && !empty($configTitter->get('twitter_api_secret'));
	}

	/**
	 * Twitter constructor.
	 *
	 * @param $userName
	 *
	 * @throws \App\Exceptions\AppException
	 */
	public function __construct($userName)
	{
		$this->userName = $userName;
		if (!\is_object(static::$twitterConnection)) {
			$configTitter = \Settings_SocialMedia_Config_Model::getInstance('twitter');
			static::$twitterConnection = new \Abraham\TwitterOAuth\TwitterOAuth(
				$configTitter->get('twitter_api_key'),
				$configTitter->get('twitter_api_secret')
			);
			static::$twitterConnection->setDecodeJsonAsArray(true);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function retrieveDataFromApi()
	{
		$db = \App\Db::getInstance();
		$maxId = (new \App\Db\Query())
			->from('u_#__social_media_twitter')
			->where(['twitter_login' => $this->userName])
			->max((new \yii\db\Expression('CAST(id_twitter AS INT)')));
		$param['screen_name'] = $this->userName;
		$indexOfText = 'text';
		if (static::TWEET_MODE === 'extended') {
			$param['tweet_mode'] = 'extended';
			$indexOfText = 'full_text';
		}
		if (!\is_null($maxId)) {
			//Retrieve only new data from Api
			//"There are limits to the number of Tweets that can be accessed through the API. If the limit of Tweets has occured since the since_id, the since_id will be forced to the oldest ID available."
			$param['since_id'] = $maxId;
		}
		$allMessages = $this->getFromApi('statuses/user_timeline', $param);
		foreach ($allMessages as $rowTwitter) {
			$rowTwitter['id'] = \App\Purifier::encodeHtml($rowTwitter['id']);
			$rowTwitter['created_at'] = \App\Purifier::encodeHtml($rowTwitter['created_at']);
			$rowTwitter[$rowTwitter[$indexOfText]] = \App\Purifier::encodeHtml($rowTwitter[$indexOfText]);
			if (!isset($rowTwitter['user']['name'])) {
				throw new \App\Exceptions\AppException('Twitter API error on "user name"');
			}
			$rowTwitter['user'] = \App\Purifier::encodeHtml($rowTwitter['user']['username']);
			if (!(new \App\Db\Query())->from('u_#__social_media_twitter')->where(['id_twitter' => $rowTwitter['id']])->exists()) {
				$db->createCommand()->insert('u_#__social_media_twitter', [
					'id_twitter' => $rowTwitter['id'],
					'twitter_login' => $this->userName,
					'message' => $rowTwitter[$indexOfText],
					'created' => \DateTimeField::convertToUserTimeZone($rowTwitter['created_at'])->format('Y-m-d H:i:s'),
				])->execute();
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function removeAccount()
	{
		$db = \App\Db::getInstance();
		$db->createCommand()->delete('u_#__social_media_twitter', ['twitter_login' => $this->userName])->execute();
		$db->createCommand()->delete('b_#__social_media_twitter', ['twitter_login' => $this->userName])->execute();
	}

	/**
	 * Check if the Twitter API returned an error.
	 *
	 * @param string $response - Response from Twitter API
	 *
	 * @throws \App\Exceptions\AppException
	 *
	 * @return bool
	 */
	private function isError($response)
	{
		if (isset($response['errors'])) {
			throw new \App\Exceptions\AppException('Twitter API error' . $response['errors']['message'],
				(int) $response['errors']['code']);
		}
		return false;
	}

	/**
	 * Make GET requests to the API.
	 *
	 * @param string $path
	 * @param array  $parameters
	 *
	 * @throws \App\Exceptions\AppException
	 *
	 * @return array|object|string
	 */
	private function getFromApi($path, array $parameters = [])
	{
		$response = static::$twitterConnection->get($path, $parameters);
		$this->isError($response);
		return $response;
	}
}
