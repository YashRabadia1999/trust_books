(function () {
  try {
    var blockedHosts = ["envato.workdo.io"]; // hosts to block
    var blockedPath = /\/verify\.js(?:[?#]|$)/i; // path pattern to block

    function shouldBlock(input) {
      try {
        var u = new URL(String(input), window.location.href);
        return blockedHosts.indexOf(u.hostname) !== -1 && blockedPath.test(u.pathname + (u.search || ""));
      } catch (e) {
        return false;
      }
    }

    // Intercept window.fetch
    if (typeof window.fetch === "function") {
      var _origFetch = window.fetch;
      window.fetch = function (input, init) {
        var url = (typeof input === "string") ? input : (input && input.url) || "";
        if (shouldBlock(url)) {
          console.warn("[verify-block] Blocked fetch:", url);
          return Promise.reject(new Error("blocked by verify-block"));
        }
        return _origFetch.apply(this, arguments);
      };
    }

    // Intercept XMLHttpRequest
    var _origXHROpen = XMLHttpRequest.prototype.open;
    var _origXHRSend = XMLHttpRequest.prototype.send;
    XMLHttpRequest.prototype.open = function (method, url) {
      try { this.__verifyBlock = shouldBlock(url); } catch (_) { this.__verifyBlock = false; }
      this.__verifyUrl = url;
      return _origXHROpen.apply(this, arguments);
    };
    XMLHttpRequest.prototype.send = function (body) {
      if (this.__verifyBlock) {
        console.warn("[verify-block] Blocked XHR:", this.__verifyUrl);
        try { this.abort(); } catch (_) {}
        return; // do not send
      }
      return _origXHRSend.apply(this, arguments);
    };

    // Intercept jQuery ajax/getScript if jQuery is present
    if (window.jQuery) {
      var $ = window.jQuery;
      if ($.ajax) {
        var _origAjax = $.ajax;
        $.ajax = function (url, options) {
          var reqUrl = (typeof url === "string") ? url : (url && url.url) || (options && options.url) || "";
          if (shouldBlock(reqUrl)) {
            console.warn("[verify-block] Blocked $.ajax:", reqUrl);
            // Return a rejected Deferred to preserve jQuery contract
            var d = $.Deferred();
            d.reject({ blocked: true, url: reqUrl });
            return d.promise();
          }
          return _origAjax.apply(this, arguments);
        };
      }
      if ($.getScript) {
        var _origGetScript = $.getScript;
        $.getScript = function (url, success) {
          if (shouldBlock(url)) {
            console.warn("[verify-block] Blocked $.getScript:", url);
            var d = $.Deferred();
            d.reject({ blocked: true, url: url });
            return d.promise();
          }
          return _origGetScript.apply(this, arguments);
        };
      }
    }

  // Intercept <script src=...>
    var desc = Object.getOwnPropertyDescriptor(HTMLScriptElement.prototype, "src");
    if (desc && typeof desc.set === "function") {
      var _origSrcSet = desc.set;
      var _origSrcGet = desc.get;
      Object.defineProperty(HTMLScriptElement.prototype, "src", {
        set: function (val) {
          if (shouldBlock(val)) {
            console.warn("[verify-block] Blocked <script src>:", val);
            return val;
          }
          return _origSrcSet.call(this, val);
        },
        get: function () { return _origSrcGet.call(this); },
        configurable: true,
        enumerable: desc.enumerable
      });
  } else {
      // Fallback: intercept setAttribute for script tags
      var _origSetAttribute = Element.prototype.setAttribute;
      Element.prototype.setAttribute = function (name, value) {
        if (this.tagName === "SCRIPT" && String(name).toLowerCase() === "src" && shouldBlock(value)) {
          console.warn("[verify-block] Blocked setAttribute src:", value);
          return;
        }
        return _origSetAttribute.apply(this, arguments);
      };
    }

    // Intercept <img src=...> beacons
    var imgDesc = Object.getOwnPropertyDescriptor(HTMLImageElement.prototype, "src");
    if (imgDesc && typeof imgDesc.set === "function") {
      var _origImgSrcSet = imgDesc.set;
      var _origImgSrcGet = imgDesc.get;
      Object.defineProperty(HTMLImageElement.prototype, "src", {
        set: function (val) {
          if (shouldBlock(val)) {
            console.warn("[verify-block] Blocked <img src>:", val);
            return val;
          }
          return _origImgSrcSet.call(this, val);
        },
        get: function () { return _origImgSrcGet.call(this); },
        configurable: true,
        enumerable: imgDesc.enumerable
      });
    }

    // Intercept sendBeacon
    if (navigator && typeof navigator.sendBeacon === "function") {
      var _origSendBeacon = navigator.sendBeacon.bind(navigator);
      navigator.sendBeacon = function (url, data) {
        if (shouldBlock(url)) {
          console.warn("[verify-block] Blocked sendBeacon:", url);
          return false;
        }
        return _origSendBeacon(url, data);
      };
    }
  } catch (err) {
    console.warn("[verify-block] Shim error:", err);
  }
})();
