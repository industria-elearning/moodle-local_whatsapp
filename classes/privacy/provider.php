<?php

/**
 * Whatsapp Chat.
 *
 * @package    local_whatsapp
 * @copyright 2020 BuenData Developer <hernan@buendata.com>
 */


namespace whatsapp\privacy;

defined('MOODLE_INTERNAL') || die();

/**
 * Privacy Subsystem for local_whatsapp implementing null_provider.
 *
 * @package    local_whatsapp
 * @copyright 2020 BuenData Developer <hernan@buendata.com>
 */
class provider implements \core_privacy\local\metadata\null_provider {

    /**
     * Get the language string identifier with the component's language
     * file to explain why this plugin stores no data.
     *
     * @return  string
     */
    public static function get_reason (): string {
        return 'privacy:metadata';
    }
}
