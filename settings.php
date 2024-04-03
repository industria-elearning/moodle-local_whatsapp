<?php

defined('MOODLE_INTERNAL') || die();

if ( $hassiteconfig ) {

    $settings = new admin_settingpage( 'local_whatsapp',
        new lang_string('pluginname', 'local_whatsapp')
    );

    $ADMIN->add( 'localplugins', $settings );

    $settings->add( new admin_setting_configtext(
        'local_whatsapp/phonenumber',
        new lang_string('phonenumber', 'local_whatsapp'),
        new lang_string('phonenumber_desc', 'local_whatsapp'),
        'xxxxxx',
        PARAM_TEXT
    ));

    $settings->add( new admin_setting_configtext(
        'local_whatsapp/popupmessage',
        new lang_string('popupmessage', 'local_whatsapp'),
        new lang_string('popupmessage_desc', 'local_whatsapp'),
        'Chat with us on WhatsApp!',
        PARAM_TEXT
    ));

    $settings->add( new admin_setting_configtext(
        'local_whatsapp/headertitle',
        new lang_string('headertitle', 'local_whatsapp'),
        new lang_string('headertitle_desc', 'local_whatsapp'),
        'Hello, how can we help you?',
        PARAM_TEXT
    ));

    $settings->add( new admin_setting_configtext(
        'local_whatsapp/headertitle',
        new lang_string('headertitle', 'local_whatsapp'),
        new lang_string('headertitle_desc', 'local_whatsapp'),
        'Hello, how can we help you?',
        PARAM_TEXT
    ));
}
