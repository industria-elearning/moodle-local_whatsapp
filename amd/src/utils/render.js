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
 * This module contain helpers to render functionality
 *
 * @module     local_whatsapp/utils/render
 * @copyright  2024 2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import { exception as displayException } from "core/notification";
import Templates from "core/templates";

/**
 * @param {string} templateName Name of template, example `local_marketplace/home`
 * @param {object} context Object of properties that the template needs to render
 */
export async function renderTemplate(templateName, context = {}) {
  return Templates.renderForPromise(templateName, context)
    .then(({ html, js }) => {
      return Templates.appendNodeContents("body", html, js);
    })
    .catch((error) => displayException(error));
}
