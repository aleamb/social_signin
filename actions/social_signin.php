<?php
/**
 * Elgg action for Social SignIn
 *
 * @category  ElggPlugin
 * @package   SocialSignIn
 * @author    Alejandro Ambroa <jandroz@gmail.com>
 * @license   MIT
 * @version   $Id$
 * @link      https://github.com/aleamb/social_signin
 */

$type = get_input('type');

$socialSignIn = new SocialSignInPlugin\SocialSignIn();

try {

    $module = SocialSignInPlugin\SocialModuleFactory::create($type, $socialSignIn);

    $attributes = array();
    parse_str($_SERVER['QUERY_STRING'], $attributes);

    $socialProfile = $module->retrieveUserSocialProfile($attributes);

    $user = $module->getElggUser($socialProfile);

    if (!$user) {
        $userName = $module->generateUsername($socialProfile);
        $user = $module->registerUser($userName, $socialProfile);
    }

    login($user);
    forward('activity');

} catch (Exception $e) {
    register_error($e->getMessage());
    forward('/');
}
