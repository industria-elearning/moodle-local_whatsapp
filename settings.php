<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * TODO describe file competencies2
 *
 * @package    local_whatsapp
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ( $hassiteconfig ) {

    $settings = new admin_settingpage( 'local_whatsapp',
        new lang_string('pluginname', 'local_whatsapp')
    );

    $ADMIN->add( 'localplugins', $settings );

    $settings->add(
        new \local_whatsapp\admin_setting_phone(
            'local_whatsapp/phonenumber',
            new lang_string('phonenumber', 'local_whatsapp'),
            new lang_string('phonenumber_desc', 'local_whatsapp'),
            '',
        )
    );

    $settings->add(
        new admin_setting_configtext(
            'local_whatsapp/popupmessage',
            new lang_string('popupmessage', 'local_whatsapp'),
            new lang_string('popupmessage_desc', 'local_whatsapp'),
            get_string('default_popupmessage', 'local_whatsapp'),
            PARAM_TEXT
        )
    );

    $settings->add(
        new admin_setting_configtext(
            'local_whatsapp/headertitle',
            new lang_string('headertitle', 'local_whatsapp'),
            new lang_string('headertitle_desc', 'local_whatsapp'),
            get_string('default_headertitle', 'local_whatsapp'),
            PARAM_TEXT
        )
    );
    $positions = [
        "right" => get_string('right', 'local_whatsapp'),
        "left" => get_string('left', 'local_whatsapp'),
    ];
    $settings->add(
        new admin_setting_configselect(
            'local_whatsapp/position',
            new lang_string('position', 'local_whatsapp'),
            new lang_string('position_desc', 'local_whatsapp'),
            "right",
            $positions,
    ));
}
