<?php
/**
 * Whatsapp Chat.
 *
 * @package    local_whatsapp
 * @copyright 2020 BuenData Developer <hernan@buendata.com>
 */

defined('MOODLE_INTERNAL') || die();


function local_whatsapp_extend_navigation(global_navigation $nav) {
    global $PAGE, $CFG, $OUTPUT;

    $baserooturl = $CFG->wwwroot;
    $whatsappiconurl = $OUTPUT->image_url('whatsapp', 'local_whatsapp');
    $whatsappicon = $baserooturl. $whatsappiconurl->out_as_local_url();
    $sendiconurl = $OUTPUT->image_url('send', 'local_whatsapp');
    $sendicon = $baserooturl. $sendiconurl->out_as_local_url();

    $phonenumber = get_config('local_whatsapp', 'phonenumber');
    $popupmessage = get_config('local_whatsapp', 'popupmessage');
    $headertitle = get_config('local_whatsapp', 'headertitle');
    $showpopup = get_config('local_whatsapp', 'showpopup');
    $position = get_config('local_whatsapp', 'position');

    $config = [
        'phonenumber' => $phonenumber,
        'popupmessage' => $popupmessage,
        'headertitle' => $headertitle,
        'showpopup' => $showpopup,
        'position' => $position,
        'whatsappicon' => $whatsappicon,
        'sendicon' => $sendicon,
    ];
    $PAGE->requires->js_call_amd('local_whatsapp/whatsapp', 'init', [$config]);
}
