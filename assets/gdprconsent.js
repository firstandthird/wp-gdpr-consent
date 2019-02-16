(function() {
  // IE < 9 Polyfill
  // https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/isArray
  if (!Array.isArray) {
    Array.isArray = function (arg) {
      return Object.prototype.toString.call(arg) === '[object Array]';
    };
  }

  // IE9 polyfill
  // adapted from https://developer.mozilla.org/en-US/docs/Web/API/Element/closest#Polyfill
  if (!Element.prototype.closest) {
    if (!Element.prototype.matches) {
      Element.prototype.matches = Element.prototype.msMatchesSelector;
    }

    if (!Element.prototype.matches) {
      // Fallback in case browser is really old
      Element.prototype.closest = function (s) {
        return null;
      };
    } else {
      Element.prototype.closest = function (s) {
        var el = this;
        if (!document.documentElement.contains(el)) return null;
        do {
          if (el.matches(s)) return el;
          el = el.parentElement || el.parentNode;
        } while (el !== null && el.nodeType === 1);
        return null;
      };
    }
  }

  var currentCookie = getCookie('gdprconsent');
  var pendingTracks = [
    // Adds support for Google Tag Manager
    function() {
      if (window.dataLayer && Array.isArray(window.dataLayer)) {
        window.dataLayer.push({ 'event': 'cookieConsent' });
      }
    }
  ];
  var consentBar;
  var scrollStart = 0;
  var scrollDelay = 75;

  function getCookie(name) {
    var nameEQ = `${name}=`;
    var split = document.cookie.split(';');
    var value = null;

    split.forEach(function(item) {
      var cleaned = item.trim();

      if (cleaned.indexOf(nameEQ) === 0) {
        value = decodeURIComponent(cleaned.substring(nameEQ.length, cleaned.length));

        if (value === 'undefined') {
          value = undefined;
        }
      }
    });

    return value;
  }

  function setCookie(name, value) {
    var date = new Date();
    var type = typeof(value);

    date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000));

    var expires = '; expires=' + date.toUTCString();
    var valueToUse = encodeURIComponent(value);

    document.cookie = `${name}=${valueToUse}${expires}; path=/`;
  }

  function grantConsent(event) {
    if (event.type === 'scroll') {
      if (Math.abs(scrollStart - window.scrollY) < scrollDelay) {
        return;
      }
    }

    if (event.type === 'click') {
      var target = event.target.closest('#GDPRConsentBar');

      if (target) {
        return;
      }
    }

    event.currentTarget.removeEventListener('click', grantConsent);
    event.currentTarget.removeEventListener('scroll', grantConsent);
    setCookie('gdprconsent', 'consent');
    consentBar.style.display = 'none';
    safeTrack();
  }

  function safeTrack(cb) {
    if (currentCookie !== 'consent' && typeof cb === 'function') {
      pendingTracks.push(cb);
      return;
    }

    pendingTracks.forEach(function(item) {
      item();
    });

    pendingTracks = [];

    if (typeof cb === 'function') {
      cb();
    }
  }

  window.gdprSafeTrack = safeTrack;

  document.addEventListener("DOMContentLoaded", function(event) {
    consentBar = document.getElementById('GDPRConsentBar');

    // Bar not loaded, bail out
    if (!consentBar) {
      return false;
    }

    if (currentCookie !== 'consent') {
      consentBar.style.display = 'block';
    }

    // Delay to prevent immediate acceptance if page is loaded with a hash anchor
    setTimeout(function() {
      scrollStart = window.scrollY;
      document.addEventListener('click', grantConsent);
      document.addEventListener('scroll', grantConsent);
    }, 500);
  });

}());
