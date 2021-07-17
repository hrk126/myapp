window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.axios.interceptors.response.use(
  response => response,
  error => error.response || error
);

window.axios.interceptors.request.use(config => {
    config.headers['X-XSRF-TOKEN'] = getCookieValue('XSRF-TOKEN');
    return config;
});

/**
 * 引数で指定したkeyのcookieの値を返す
 *
 * @param  string searchKey
 * @return string val
 */

function getCookieValue (searchKey) {

    if (typeof searchKey === 'undefined') {
      return '';
    }

    let val = ''

    document.cookie.split(';').forEach(cookie => {
      const [key, value] = cookie.split('=');
      if (key === searchKey) {
        return val = value;
      }
    });
    return val;
}