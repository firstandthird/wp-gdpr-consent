(function() {
  var currentCookie = getCookie('gdprconsent');
  var pendingTracks = [];

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
    event.currentTarget.removeEventListener('click', grantConsent);
    event.currentTarget.removeEventListener('scroll', grantConsent);
    setCookie('gdprconsent', 'consent');
    consentBar.style.display = 'none';
  }

  function safeTrack(cb) {
    if (currentCookie !== 'consent') {
      pendingTracks.push(cb);
      return;
    }

    pendingTracks.forEach(function(item) {
      item();
    });

    pendingTracks = [];

    cb();
  }

  window.gdprSafeTrack = safeTrack;

  document.addEventListener("DOMContentLoaded", function(event) {
    var consentBar = document.getElementById('GDPRConsentBar');

    // Bar not loaded, bail out
    if (!consentBar) {
      return false;
    }

    if (currentCookie !== 'consent') {
      consentBar.style.display = 'block';
    }

    // Delay to prevent immediate acceptance if page is loaded with a hash anchor
    setTimeout(function() {
      document.addEventListener('click', grantConsent);
      document.addEventListener('scroll', grantConsent);
    }, 500);
  });

}());
