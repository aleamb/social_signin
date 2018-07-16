<?php

/**
 * This file is part of SocialIntegrationPlugin for RSIC.
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
 * This class is used as data transfer object between social netowrk's user profile to this plugin.
 *
 * @package SocialIntegrationPlugin
 */
class SocialUser {
   public $id = null;
   public $email = null;
   public $pictureUrl = null;
   public $firstName = null;
   public $lastName = null;
}
