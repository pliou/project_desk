// utils/ajax.js
import AjaxRequest from '@typo3/core/ajax/ajax-request.js';

/**
 * Utility function to send a POST AjaxRequest using TYPO3 ajaxUrls
 * @param {string} urlKey - Key from TYPO3.settings.ajaxUrls
 * @param {Object} data - Payload to send
 * @returns {Promise<any>} - Resolved JSON result from server
 */
export async function postAjax(urlKey, data) {
  try {
    const request = new AjaxRequest(TYPO3.settings.ajaxUrls[urlKey]);
    const response = await request.post(data, {
      headers: {
        'Content-Type': 'application/json; charset=utf-8'
      }
    });
    return await response.resolve();
  } catch (error) {
    console.error(`Ajax request to ${urlKey} failed`, error);
    return null;
  }
}
