var AppTalking = {
  nativeBridge: {
    callbacksCount: 1,
    callbacks: {},
    resultForCallback: function(callbackId, resultArray) {
      try {
        const callback = this.callbacks[callbackId]
        if (!callback) {
          return
        }
        callback.apply(null, resultArray)
      } catch (e) {
        alert(e)
      }
    },
    call: function(functionName, args, callback) {
      const hasCallback = callback && typeof callback === 'function'
      const callbackId = hasCallback ? this.callbacksCount++ : 0

      if (hasCallback) {
        this.callbacks[callbackId] = callback
      }
      var iframe = document.createElement('IFRAME')
      iframe.setAttribute('src', 'js-frame:' + functionName + ':' + callbackId + ':' + encodeURIComponent(JSON.stringify(args)))
      document.documentElement.appendChild(iframe)
      iframe.parentNode.removeChild(iframe)
      iframe = null
    }
  },
  launch_game: function() {
    this.jsToAppMessage('{"event":"EVENT_OPEN_GAME", "vendor":"67", "data":{"kind":"1", "vendor":"boe", "target":"Web"}}')
  },
  iosAppToJsMessage: function(response) {},
  androidAppToJsMessage: function(message) {},
  iosJsToAppMessage: function(message) {
    this.nativeBridge.call('JsToAppMessage', [message], this.iosAppToJsMessage)
  },

  androidJsToAppMessage: function(message) {
    window.MyHandler.JsToAppMessage(message)
  },

  jsToAppMessage: function(message) {
    const device = {
      isiPad: navigator.userAgent.match(/iPad/i) !== null,
      isiPhone: navigator.userAgent.match(/iPhone/i) !== null,
      isandroid: navigator.userAgent.match(/Android/i) !== null
    }

    if (device.isiPad || device.isiPhone) {
      this.iosJsToAppMessage(message)
    } else if (device.isandroid) {
      this.androidJsToAppMessage(message)
    }
  }

}
export default {
  AppTalking
}
