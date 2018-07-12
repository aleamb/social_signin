<?php elgg_echo('social_facebook:settings:title'); ?>

<div class="facebook-reg-settings">
   <div>
     <label><?php echo elgg_echo('social_facebook:settings:label:clientId'); ?></label>
     <input name="params[fb_appId]" type="text" value="<?php echo $vars['entity']->fb_appId ?>" required>
  </div>
  <div>
     <label><?php echo elgg_echo('social_facebook:settings:label:clientSecret'); ?></label>
     <input name="params[fb_appSecretId]" type="text" value="<?php echo $vars['entity']->fb_appSecretId ?>" required>
  </div>
</div>
