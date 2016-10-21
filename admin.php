<?php
add_action( 'admin_menu', 'votr_add_admin_menu' );
add_action( 'admin_init', 'votr_settings_init' );


function votr_add_admin_menu(  ) {

  add_menu_page( 'Votr', 'Votr', 'manage_options', 'votr', 'votr_options_page' );

}


function votr_settings_init(  ) {

  register_setting( 'pluginPage', 'votr_settings' );

  add_settings_section(
    'votr_pluginPage_section',
    null,
    'votr_settings_section_callback',
    'pluginPage'
  );

  add_settings_field(
    'votr_limit',
    __( '# of Downvotes', 'votr' ),
    'votr_limit_render',
    'pluginPage',
    'votr_pluginPage_section'
  );


}


function votr_limit_render(  ) {

  //add_settings_error('votr_messages', 'votr_message', __('Settings Saved', 'votr'), 'updated');
  //settings_errors('votr_messages');

  $options = get_option( 'votr_settings' );
  ?>
  <input type='text' name='votr_settings[votr_limit]' value='<?php echo $options['votr_limit']; ?>'>
  <?php

}


function votr_settings_section_callback(  ) {
  echo __( 'Number of downvotes before comment is marked for moderation.', 'votr' );

}


function votr_options_page(  ) {

  ?>
  <form action='options.php' method='post'>

    <h2>Votr</h2>

    <?php

      // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    // add error/update messages

    // check if the user have submitted the settings
    // wordpress will add the "settings-updated" $_GET parameter to the url
    if (isset($_GET['settings-updated'])) {
        // add settings saved message with the class of "updated"
        add_settings_error('votr_messages', 'votr_message', __('Settings Saved', 'votr'), 'updated');
    }

    // show error/update messages
    settings_errors('votr_messages');

    settings_fields( 'pluginPage' );
    do_settings_sections( 'pluginPage' );
    submit_button();


    ?>

  </form>
  <?php

}

?>