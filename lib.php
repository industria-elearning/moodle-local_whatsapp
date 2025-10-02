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

use local_whatsapp\phone_data;

defined('MOODLE_INTERNAL') || die();

/**
 * Extend the navigation menu.
 *
 * @param global_navigation $nav The navigation menu to extend.
 */
function local_whatsapp_extend_navigation(global_navigation $nav) {
    global $PAGE, $CFG, $OUTPUT;

    // Check if WhatsApp button should be shown.
    $showbutton = get_config('local_whatsapp', 'showbutton');
    if (!$showbutton) {
        return;
    }

    $baserooturl = $CFG->wwwroot;
    $whatsappiconurl = $OUTPUT->image_url('whatsapp', 'local_whatsapp');
    $whatsappicon = $baserooturl. $whatsappiconurl->out_as_local_url();
    $sendiconurl = $OUTPUT->image_url('send', 'local_whatsapp');
    $sendicon = $baserooturl. $sendiconurl->out_as_local_url();

    $phonenumber = get_config('local_whatsapp', 'phonenumber');
    $popupmessage = get_config('local_whatsapp', 'popupmessage');
    $headertitle = get_config('local_whatsapp', 'headertitle');
    $position = get_config('local_whatsapp', 'position');

    $phonenumber = json_decode($phonenumber, true);
    $phonenumber = phone_data::get_phone_with_code($phonenumber['alpha2'], $phonenumber['number']);

    $config = [
        'phonenumber' => $phonenumber,
        'popupmessage' => $popupmessage,
        'headertitle' => $headertitle,
        'position' => $position,
        'whatsappicon' => $whatsappicon,
        'sendicon' => $sendicon,
    ];
    $PAGE->requires->js_call_amd('local_whatsapp/whatsapp', 'init', [$config]);
}
