<?php
/**
 * Whatsapp Chat.
 *
 * @package    local_whatsapp
 * @copyright 2020 BuenData Developer <hernan@buendata.com>
 */

defined('MOODLE_INTERNAL') || die();


function local_whatsapp_extend_navigation(global_navigation $nav) {
    global $PAGE, $CFG;
    $phonenumber = get_config('local_whatsapp','phonenumber');
    $popupmessage = get_config('local_whatsapp','popupmessage');
    $headertitle = get_config('local_whatsapp','headertitle');
    $showpopup = get_config('local_whatsapp','showpopup');
    $position = get_config('local_whatsapp','position');
    $buttonimage = $CFG->wwwroot . "/local/whatsapp/pix/whatsapp.svg";
    $config = array(
        'phonenumber' => $phonenumber,
        'popupmessage' => $popupmessage,
        'headertitle' => $headertitle,
        'showpopup' => $showpopup,
        'position' => $position,
        'buttonimage' => $buttonimage
    );
    $PAGE->requires->js_call_amd('local_whatsapp/whatsapp', 'init', array($config));
}