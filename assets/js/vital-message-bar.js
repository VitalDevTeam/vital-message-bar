/* globals VTLMB */
(function() {
    var body, bar, dismiss;

    /**
     * Sets cookie
     * http://www.quirksmode.org/js/cookies.html
     *
     * @param  {string}  name     Cookie name
     * @param  {string}  value    Cookie value
     * @param  {integer} seconds  Cookie expiration in seconds
     */
    function setCookie(name, value, seconds) {
        var expires;
        if (seconds) {
            var date = new Date();
            date.setTime(date.getTime() + (seconds * 1000));
            expires = '; expires=' + date.toGMTString();
        } else {
            expires = '';
        }
        document.cookie = name + '=' + value + expires + '; path=/';
    }

    /**
     * Closes message bar and sets cookie.
     */
    function dismissBar() {
        bar.parentNode.removeChild(bar);
        body.classList.remove('vtlmb-message-bar');
        setCookie(VTLMB.cookie_dismissed, '1', parseInt(VTLMB.cookie_expires));
    }

    function onDocumentReady() {
        body = document.querySelector('body');
        bar = document.querySelector('#vtlmb-bar');
        dismiss = document.querySelector('#vtlmb-bar-dismiss');

        if (dismiss) {
            dismiss.addEventListener('click', dismissBar);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        onDocumentReady();
    });

})();
