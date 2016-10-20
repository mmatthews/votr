<?php
/**
 * @internal    never define functions inside callbacks.
 *              these functions could be run multiple times; this would result in a fatal error.
 */

/**
 * custom option and settings
 */
function votr_settings_init()
{
    // register a new setting for "votr" page
    register_setting('votr', 'votr_options');

    // register a new section in the "votr" page
    add_settings_section(
        'votr_section_developers',
        //__('The Matrix has you.', 'votr'),
        null,
        'votr_section_developers_cb',
        'votr'
    );

    // register a new field in the "votr_section_developers" section, inside the "votr" page
    add_settings_field(
        'votr_field_number', // as of WP 4.6 this value is used only internally
        // use $args' label_for to populate the id inside the callback
        __('# of Downvotes', 'votr'),
        'votr_field_number_cb',
        'votr',
        'votr_section_developers',
        [
            'label_for'         => 'votr_field_number',
            'class'             => 'votr_row',
            'votr_custom_data' => 'custom',
        ]
    );
}

/**
 * register our votr_settings_init to the admin_init action hook
 */
add_action('admin_init', 'votr_settings_init');

/**
 * custom option and settings:
 * callback functions
 */

// developers section cb

// section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// the values are defined at the add_settings_section() function.
function votr_section_developers_cb($args)
{
    ?>
    <p id="<?= esc_attr($args['id']); ?>"><?= esc_html__('Follow the white rabbit.', 'votr'); ?></p>
    <?php
}

// number field cb

// field callbacks can accept an $args parameter, which is an array.
// $args is defined at the add_settings_field() function.
// wordpress has magic interaction with the following keys: label_for, class.
// the "label_for" key value is used for the "for" attribute of the <label>.
// the "class" key value is used for the "class" attribute of the <tr> containing the field.
// you can add custom key value pairs to be used inside your callbacks.
function votr_field_number_cb($args)
{
    // get the value of the setting we've registered with register_setting()
    $options = get_option('votr_options');
    // output the field
    ?>
    <p>Number of downvotes before a comment is marked for moderation.</p>
    <input  id="<?= esc_attr($args['label_for']); ?>"
            type="number"
            name="votr_options<?= esc_attr($args['label_for']); ?>"
            data-custom="<?= esc_attr($args['votr_custom_data']); ?>"
            value="<?php get_option('votr_options') ?>" />
    <!--
        <option value="red" <?= isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], 'red', false)) : (''); ?>>
            <?= esc_html('red number', 'votr'); ?>
        </option>
        <option value="blue" <?= isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], 'blue', false)) : (''); ?>>
            <?= esc_html('blue number', 'votr'); ?>
        </option>
      -->
    <?php
}

/**
 * top level menu
 */
function votr_options_page()
{
    // add top level menu page
    add_menu_page(
        'Votr Settings',
        'Votr Options',
        'manage_options',
        'votr',
        'votr_options_page_html'
    );
}

/**
 * register our votr_options_page to the admin_menu action hook
 */
add_action('admin_menu', 'votr_options_page');

/**
 * top level menu:
 * callback functions
 */
function votr_options_page_html()
{
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
    ?>
    <div class="wrap">
        <h1><?= esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "votr"
            settings_fields('votr');
            // output setting sections and their fields
            // (sections are registered for "votr", each field is registered to a specific section)
            do_settings_sections('votr');
            // output save settings button
            submit_button('Save Settings');
            ?>
        </form>
    </div>
    <?php
}