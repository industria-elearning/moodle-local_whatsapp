<?php

defined('MOODLE_INTERNAL') || die();

if ( $hassiteconfig ){

    $settings = new admin_settingpage( 'local_whatsapp',
        new lang_string('pluginname', 'local_whatsapp')
    );

    $ADMIN->add( 'localplugins', $settings );

    $settings->add( new admin_setting_configtext(
        'local_whatsapp/phonenumber',
        new lang_string('phonenumber','local_whatsapp'),
        new lang_string('phonenumber_desc','local_whatsapp'),
        'xxxxxx',
        PARAM_TEXT
    ));

    $settings->add( new admin_setting_configtext(
        'local_whatsapp/popupmessage',
        new lang_string('popupmessage','local_whatsapp'),
        new lang_string('popupmessage_desc','local_whatsapp'),
        'Chat with us on WhatsApp!',
        PARAM_TEXT
    ));

    $settings->add( new admin_setting_configtext(
        'local_whatsapp/headertitle',
        new lang_string('headertitle','local_whatsapp'),
        new lang_string('headertitle_desc','local_whatsapp'),
        'Hello, how can we help you?',
        PARAM_TEXT
    ));

    $settings->add( new admin_setting_configtext(
        'local_whatsapp/headertitle',
        new lang_string('headertitle','local_whatsapp'),
        new lang_string('headertitle_desc','local_whatsapp'),
        'Hello, how can we help you?',
        PARAM_TEXT
    ));

    $settings->add( new admin_setting_configcheckbox(
        'local_whatsapp/showpopup',
        new lang_string('showpoup','local_whatsapp'),
        new lang_string('showpoup_desc','local_whatsapp'),
        1
    ));

    $positions = ["right" => "Right", "left" => "Left"];
    $settings->add( new admin_setting_configselect(
        'local_whatsapp/position',
        new lang_string('position','local_whatsapp'),
        new lang_string('position_desc','local_whatsapp'),
        "right",
        $positions
    ) );
}