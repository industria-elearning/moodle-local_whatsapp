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

/**
 * Init function for render floating whatsapp
 *
 * @param {Object} settings
 * @param {number} settings.phonenumber
 * @param {string} settings.popupmessage
 * @param {string} settings.headertitle
 * @param {string} settings.position
 * @param {string} settings.whatsappicon
 * @param {string} settings.sendicon
 */
export async function init({
  phonenumber,
  popupmessage,
  headertitle,
  position,
  whatsappicon,
  sendicon,
}) {
  await renderTemplate("local_whatsapp/floating_button", {
    popupmessage,
    headertitle,
    position,
    whatsappicon,
    sendicon,
  });
  const floatingButton = document.getElementById(
    "local_whatsapp_floating_button"
  );
  const closeButton = document.getElementById("local_whatsapp_close_button");
  const buttonSend = document.getElementById("local_whatsapp_button_send");
  const inputMessage = document.getElementById("local_whatsapp_input_message");

  floatingButton.addEventListener("click", togglePopup);
  closeButton.addEventListener("click", togglePopup);
  buttonSend.addEventListener("click", () => {
    sendWhatsappMessage({ phonenumber, message: inputMessage.value });
  });
  inputMessage.addEventListener("keypress", (event) => {
    if (event.key === "Enter" && !event.shiftKey && !verifyIfMobile()) {
      event.preventDefault();
      sendWhatsappMessage({ phonenumber, message: inputMessage.value });
    }
  });
}

/**
 * Send message to whatsapp
 * @param {Object} options
 * @param {number} options.phonenumber
 * @param {string} options.message
 */
function sendWhatsappMessage({ phonenumber, message }) {
  let apilink = "http://";
  const isMobile = verifyIfMobile();
  apilink += isMobile ? "api" : "web";
  apilink +=
    ".whatsapp.com/send?phone=" + phonenumber + "&text=" + encodeURI(message);

  window.open(apilink);
}

/**
 * Check if browser is on a mobile device
 *
 * @returns {boolean} true if mobile or false if not
 */
function verifyIfMobile() {
  const regex =
    /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i;
  return regex.test(navigator.userAgent);
}

/**
 * Close or open popup
 */
function togglePopup() {
  const popup = document.getElementById("local_whatsapp_popup");
  const inputMessage = document.getElementById("local_whatsapp_input_message");

  if (!popup.classList.contains("active")) {
    popup.classList.add("active");
    inputMessage.focus();
  } else {
    popup.classList.remove("active");
  }
}
