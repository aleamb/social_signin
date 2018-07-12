<div class="socialsn-settings">
    <?php
    $modules = SocialSignInPlugin\SocialModuleFactory::MODULES;
    foreach ($modules as $group) { ?>
    <fieldset>
        <legend><?php echo elgg_echo("social_signin:settings:$group:legend"); $moduleEnabledAttrib = "${group}_enabled"; ?></legend>
        <label for="m-enable"><?php echo elgg_echo("social_signin:settings:$group:enabled"); ?></label>
        <input type="hidden" name="<?php echo "params[$moduleEnabledAttrib]" ?>" value="0" ?>
        <input id="m-enable" type="checkbox" name="<?php echo "params[$moduleEnabledAttrib]" ?>" value="1" <?php echo $vars['entity']->$moduleEnabledAttrib === '1' ? 'checked' : '' ?> >
        <?php echo elgg_view("$group/settings", $vars); ?>
    </fieldset>
    <?php } ?>
</div>
