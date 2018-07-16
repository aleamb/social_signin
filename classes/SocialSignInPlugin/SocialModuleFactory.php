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

class SocialModuleFactory
{
    const FACEBOOK_MODULE_ID = 'social_facebook';
    const GOOGLE_MODULE_ID   = 'social_google';
    const LINKEDIN_MODULE_ID = 'social_linkedin';

    const MODULES = [self::FACEBOOK_MODULE_ID, self::GOOGLE_MODULE_ID, self::LINKEDIN_MODULE_ID];

    /**
     * Build a SocialModule instance by id.
     *
     * @param string $id Module id.
     *
     * @param SocialSigInPlugin plugin
     * @return SocialModule Module.
     */
    public static function create($id, $socialSigInPluginModule)
    {
        $instance = null;
        switch ($id) {
        case self::FACEBOOK_MODULE_ID:
            $instance = new FacebookModule(self::FACEBOOK_MODULE_ID, $socialSigInPluginModule);
            break;
        case self::GOOGLE_MODULE_ID:
            $instance = new GoogleModule(self::GOOGLE_MODULE_ID, $socialSigInPluginModule);
            break;
        case self::LINKEDIN_MODULE_ID:
            $instance = new LinkedinModule(self::LINKEDIN_MODULE_ID, $socialSigInPluginModule);
            break;
        default:
            throw new \Exception('No module with id ' . $id);
        }
        return $instance;
    }
}
