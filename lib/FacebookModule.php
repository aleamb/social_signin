<?php
/**
 * This file is part of SocialSigInPlugin for Elgg.
 *
 * @category  ElggPlugin
 * @package   SocialSignIn
 * @author    Alejandro Ambroa <jandroz@gmail.com>
 * @license   MIT
 * @version   $Id$
 * @link      https://github.com/aleamb/social_signin
 */
namespace SocialSignInPlugin;

/**
 * Class for Facebook Module
 *
 * @package SocialSigInPlugin
 */
class FacebookModule extends SocialModule
{
    /**
     *
     */
    public function init()
    {
    }

    /**
     * Write elgg plugin configuration with default values.
     *
     * @return void
     */

    public function initValues()
    {
        if ($this->isFirstInit()) {
            $this->setEnabled(true);
        }
        if (empty($this->getAppId())) {
            $this->setAppId('Create application and get client App ID from Facebook developers console.');
        }
        if (empty($this->getAppSecretId())) {
            $this->setAppSecretId('Create application and get client secret ID from Facebook developers console.');
        }
    }


    /**
     * Register elgg views.
     *
     * @return void
     */
    public function registerElggViews()
    {
        elgg_extend_view('page/elements/head', 'social_facebook/head');
    }

    public function getAppClientIdSettingName()
    {
        return 'fb_appId';
    }

    public function getAppClientSecretIdSettingName()
    {
        return 'fb_appSecretId';
    }

    public function retrieveUserSocialProfile($attributtes)
    {
        $fb = new \Facebook\Facebook(
            [
            'app_id' => $this->getAppId(),
            'app_secret' => $this->getAppSecretId(),
            'default_graph_version' => 'v2.2'
            ]
        );

        $helper = $fb->getJavaScriptHelper();
        $accessToken = $attributtes['token'];
        if (!isset($accessToken)) {
            throw new \Exception('Error retrieving Facebook accessToken');
        }
        $response = $fb->get(
            '/me?fields=address,email,hometown,first_name,last_name,picture{width,height,url}',
            $accessToken
        );
        $graphUser = $response->getGraphUser();
        $socialUser = new SocialUser();
        $socialUser->id = $graphUser->getId();
        $socialUser->email = $graphUser->getEmail();
        $socialUser->firstName = $graphUser->getFirstName();
        $socialUser->lastName = $graphUser->getLastName();
        $socialUser->pictureUrl = $graphUser->getPicture()->getUrl();
        return $socialUser;
    }
}
