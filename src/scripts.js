import { get as getCookie, set as setCookie } from '@firstandthird/cookie-monster';
import { findOne, hide, show, on, find } from 'domassist';

class GDPRConsent {
  barId = 'GDPRConsentBar';
  cookieName = 'gdpr-consent';
  pendingTracks = [];
  consentBar = null;

  constructor() {
    this.currentCookie = getCookie(this.cookieName);
    this.isDebug = window.location.hash && window.location.hash.includes('gdpr-debug');

    if (this.isDebug && window.location.hash.includes('-clean')) {
      this.currentCookie = null;
      this.log('Not using stored cookie.');
    }

    if (this.isDebug && window.location.hash.includes('-cookies')) {
      setTimeout(() => {
        this.log({
          cookies: document.cookie.split(";")
        });
      }, 10000);
    }

    this.log({
      currentCookie: this.currentCookie
    });

    this.setupEvents();
    this.safeTrack(() => {
      if (window.dataLayer && Array.isArray(window.dataLayer)) {
        window.dataLayer.push({ 'event': 'cookieConsent' });
      }
    });
  }

  log(msg) {
    if (this.isDebug) {
      console.log('GDPR DEBUG', msg);
    }
  }

  setupEvents() {
    on(document, 'DOMContentLoaded', () => {
      this.consentBar = findOne(`#${this.barId}`);

      // Bar not loaded, bail out
      if (!this.consentBar) {
        this.log('Consent bar html not found. Make sure copy is defined in the admin.');
        return false;
      }

      if (this.currentCookie !== 'consent') {
        this.log('Showing consent bar');
        show(this.consentBar);
      }

      on(find('[data-gdpr-accept]', this.consentBar), 'click', this.grantConsent.bind(this));
    });
  }

  grantConsent() {
    this.log('Consent granted');
    setCookie(this.cookieName, 'consent', 365);
    hide(this.consentBar);
    this.safeTrack();
  }

  safeTrack(callback) {
    // handle creating scripts
    if (typeof callback === 'string') {
      this.safeTrack(() => {
        const script = document.createElement('script');
        script.type = 'text/javascript';
        script.async = true;
        script.src = callback;
        const firstScript = findOne('script');
        firstScript.parentNode.insertBefore(script, firstScript);
      });
      return;
    }

    if (this.currentCookie !== 'consent' && typeof callback === 'function') {
      this.log({
        message: 'Callback queued pending consent',
        callback
      });
      this.pendingTracks.push(callback);
      return;
    }

    this.pendingTracks.forEach(item => {
      this.log({
        message: 'Running callback',
        callback: item
      });
      item();
    });

    this.pendingTracks = [];

    if (typeof callback === 'function') {
      this.log({
        message: 'Running callback',
        callback
      });
      callback();
    }
  }
}

const gdpr = new GDPRConsent();

window.gdprSafeTrack = gdpr.safeTrack.bind(gdpr);
