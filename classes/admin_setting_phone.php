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

namespace local_whatsapp;

use admin_setting;
use core_text;

/**
 * Phone number setting with country selection and number input.
 *
 * This setting provides a composite field with a country code selector
 * and a text input for the phone number, with validation using country phone data.
 *
 * @package    local_whatsapp
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class admin_setting_phone extends admin_setting {
    /** @var array|null Country options (alpha2 => label). */
    protected $choices = null;

    /** @var bool Autocomplete: case sensitivity. */
    protected $casesensitive = false;
    /** @var bool Autocomplete: show suggestions by default. */
    protected $showsuggestions = true;
    /** @var string Autocomplete: text when no selection. */
    protected $noselectionstring = '';
    /** @var string Autocomplete: placeholder. */
    protected $placeholder = '';

    /**
     * Constructor.
     * @param string $name 'plugintype_plugin/settingname'
     * @param string $visiblename
     * @param string $description
     * @param string $defaultsetting JSON or '', default ''
     */
    public function __construct($name, $visiblename, $description, $defaultsetting = '') {
        parent::__construct($name, $visiblename, $description, $defaultsetting);
        $this->placeholder = get_string('searchcountry', 'local_whatsapp');
    }

    /**
     * Load options from phone_data::get_data().
     */
    protected function load_choices(): bool {
        if (is_array($this->choices)) {
            return true;
        }
        $this->choices = ['' => ''];
        $data = phone_data::get_data();
        foreach ($data as $alpha2 => $row) {
            // Label: +code (Country).
            $label = '+' . $row['country_code'] . ' (' . $row['country_name'] . ')';
            $this->choices[$alpha2] = $label;
        }
        return true;
    }

    /**
     * Read the setting (JSON string or '')
     */
    public function get_setting() {
        return $this->config_read($this->name);
    }

    /**
     * Phone number and country validation.
     *
     * - Both empty: OK (not required).
     * - Both present: validates length and mobile prefixes (if defined).
     * - Partial (one empty): error.
     */
    protected function validate_phone(string $alpha2, string $numberclean) {
        if ($alpha2 === '' && $numberclean === '') {
            return true; // Not required.
        }
        if ($alpha2 === '' || $numberclean === '') {
            return get_string('validateerror', 'admin');
        }
        $data = phone_data::get_data();
        if (!isset($data[$alpha2])) {
            return get_string('validateerror', 'admin');
        }
        $info = $data[$alpha2];

        // Exact length (if one or more are defined).
        if (!empty($info['phone_number_lengths'])) {
            $len = core_text::strlen($numberclean);
            if (!in_array($len, $info['phone_number_lengths'])) {
                return get_string('validateerror', 'admin');
            }
        }

        // Mobile prefixes (if defined).
        if (!empty($info['mobile_begin_with'])) {
            $ok = false;
            foreach ($info['mobile_begin_with'] as $prefix) {
                if (str_starts_with($numberclean, (string)$prefix)) {
                    $ok = true;
                    break;
                }
            }
            if (!$ok) {
                return get_string('validateerror', 'admin');
            }
        }

        return true;
    }

    /**
     * Override validate() from admin_setting to accept array or JSON.
     * @param mixed $data
     */
    public function validate($data) {
        $alpha2 = '';
        $numraw = '';

        if (is_array($data)) {
            $alpha2 = (string)($data['alpha2'] ?? '');
            $numraw = (string)($data['number'] ?? '');
        } else if (is_string($data) && $data !== '') {
            $decoded = json_decode($data, true);
            if (is_array($decoded)) {
                $alpha2 = (string)($decoded['alpha2'] ?? '');
                $numraw = (string)($decoded['number'] ?? '');
            }
        }
        // Normalize to digits only for validation.
        $numberclean = preg_replace('/\D+/', '', $numraw ?? '');

        return $this->validate_phone($alpha2, $numberclean);
    }

    /**
     * Write the setting.
     * @param mixed $data Array ['alpha2'=>..., 'number'=>...] or JSON string or ''.
     * @return string '' if OK or localizable error message.
     */
    public function write_setting($data) {
        $validated = $this->validate($data);
        if ($validated !== true) {
            return $validated;
        }

        if (is_array($data)) {
            $alpha2 = trim((string)($data['alpha2'] ?? ''));
            $numraw = trim((string)($data['number'] ?? ''));
            $numberclean = preg_replace('/\D+/', '', $numraw);

            if ($alpha2 === '' && $numberclean === '') {
                return $this->config_write($this->name, '') ? '' : get_string('errorsetting', 'admin');
            }

            $tostore = json_encode(['alpha2' => $alpha2, 'number' => $numberclean]);
            return $this->config_write($this->name, $tostore) ? '' : get_string('errorsetting', 'admin');

        } else {
            // String: normalize if JSON, otherwise save as is.
            if ($data === '' || $data === null) {
                return $this->config_write($this->name, '') ? '' : get_string('errorsetting', 'admin');
            }
            $decoded = json_decode((string)$data, true);
            if (is_array($decoded)) {
                $decoded['number'] = preg_replace('/\D+/', '', (string)($decoded['number'] ?? ''));
                $tostore = json_encode([
                    'alpha2' => (string)($decoded['alpha2'] ?? ''),
                    'number' => (string)$decoded['number'],
                ]);
            } else {
                $tostore = (string)$data;
            }
            return $this->config_write($this->name, $tostore) ? '' : get_string('errorsetting', 'admin');
        }
    }

    /**
     * Render the select (autocomplete) + text input using Core templates.
     *
     * @param mixed $data Can come as array (POST) or string (stored config).
     */
    public function output_html($data, $query = '') {
        global $OUTPUT, $PAGE;

        $this->load_choices();

        // Determina valores actuales.
        $alpha2 = '';
        $number = '';
        if (is_array($data)) {
            $alpha2 = (string)($data['alpha2'] ?? '');
            $number = (string)($data['number'] ?? '');
        } else if (is_string($data) && $data !== '') {
            $decoded = json_decode($data, true);
            if (is_array($decoded)) {
                $alpha2 = (string)($decoded['alpha2'] ?? '');
                $number = (string)($decoded['number'] ?? '');
            }
        }

        // Select country code via core_admin/setting_configselect.
        $selectid = $this->get_id() . '_alpha2';
        $selectname = $this->get_full_name() . '[alpha2]';
        $options = [];
        foreach ($this->choices as $val => $label) {
            $options[] = [
                'value' => $val,
                'name' => $label,
                'selected' => ($val === $alpha2),
            ];
        }
        $selectcontext = (object)[
            'id' => $selectid,
            'name' => $selectname,
            'options' => $options,
            'readonly' => $this->is_readonly(),
        ];
        $selecthtml = $OUTPUT->render_from_template('core_admin/setting_configselect', $selectcontext);

        // Activate select2/autocomplete (same as admin_setting_configselect_autocomplete).
        $PAGE->requires->js_call_amd('core/form-autocomplete', 'enhance', [
            '#' . $selectid,
            false,
            '',
            $this->placeholder,
            $this->casesensitive,
            $this->showsuggestions,
            $this->noselectionstring,
        ]);

        // Input number via core_admin/setting_configtext.
        $textcontext = (object)[
            'size' => 20,
            'id' => $this->get_id() . '_number',
            'name' => $this->get_full_name() . '[number]',
            'value' => $number,
            'forceltr' => true,
            'readonly' => $this->is_readonly(),
            'data' => [],
            'maxcharacter' => false,
        ];
        $texthtml = $OUTPUT->render_from_template('core_admin/setting_configtext', $textcontext);

        // Combined element: select + space + input.
        $element = $selecthtml . ' ' . $texthtml;

        $default = $this->get_defaultsetting();

        return format_admin_setting(
            $this,
            $this->visiblename,
            $element,
            $this->description,
            true,
            '',
            $default,
            $query
        );
    }
}

