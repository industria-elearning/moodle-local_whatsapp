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
 * TODO describe module watsapp
 *
 * @module     local_whatsapp/whatsapp
 * @copyright  2024 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import { renderTemplate } from "./utils/render";

// 'phonenumber' => $phonenumber,
// 'popupmessage' => $popupmessage,
// 'headertitle' => $headertitle,
// 'showpopup' => $showpopup,
// 'position' => $position,
// 'whatsappicon' => $whatsappicon,

/**
 * Init function for render floating whatsapp
 *
 * @param {Object} settings
 * @param {number} settings.phonenumber
 * @param {string} settings.popupmessage
 * @param {string} settings.headertitle
 * @param {boolean} settings.showpopup
 * @param {string} settings.position
 * @param {string} settings.whatsappicon
 * @param {string} settings.sendicon
 */
export async function init({
  phonenumber,
  popupmessage,
  headertitle,
  showpopup,
  position,
  whatsappicon,
  sendicon,
}) {
  await renderTemplate("local_whatsapp/floating_button", {
    phonenumber,
    popupmessage,
    headertitle,
    showpopup,
    position,
    whatsappicon,
    sendicon
  });
  const floatingButton = document.getElementById(
    "local_whatsapp_floating_button"
  );

  floatingButton.addEventListener("click", () => {
    openPopup();
  });
}

/**
 * Check if browser is on a mobile device
 *
 * @returns {boolean} true if mobile or false if not
 */
// function isMobile() {
//   const regex =
//     /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i;
//   return regex.test(navigator.userAgent);
// }

/**
 *
 */
function openPopup() {
  const popup = document.getElementById("local_whatsapp_popup");
  popup.classList.add("active");

  // if (!$popup.hasClass('active')) {
  //     $popup.addClass('active');
  //     $textarea.focus();
  // }
}
