import VueCookie from 'vue-cookie'
import store from '@/store'

export default class Chatroom {
  constructor(vue) {
    this.vue = vue
    this.config = {
      wsUrl: `${store.state.m6app.chatRoomUrl}?gameType=43&ch=${store.state.m6app.currentChannel}`,
      maxWebsocketRetries: 30,
      reconnectTimesCount: 0,
      wsHeartCheck: {
        timeout: 5e3,
        timeoutObj: null,
        heartbeat: 2e3
      },
      live: false
    }
    this.sendWS = this.sendWS
    this.closeWS = this.closeWS
    this.connect()
  }

  // ws心跳連線
  wsHeartBeat = {
    start: () => {
      this.config.wsHeartCheck.timeoutObj = setInterval(() => {
        // this.sendWS('ping')
      }, this.config.wsHeartCheck.timeout)
    },
    stop: () => {
      if (this.config.wsHeartCheck.timeoutObj) {
        clearInterval(this.config.wsHeartCheck.timeoutObj)
      }
    },
    reset: () => {
      this.config.reconnectTimesCount = 0
    }
  }

  // 開啟連線
  connect() {
    const ws = new WebSocket(this.config.wsUrl)
    this.ws = ws
    ws.onopen = () => {
      ws.disconnect = false
      this.config.live = true
      // this.wsHeartBeat.start()
    }
    ws.onclose = () => {
      this.config.live = false
      if (this.config.tryCount < 3) {
        this.config.tryCount += 1
        this.wsHeartBeat.stop()
        this.reconnect()
        ws.onclose = () => {}
      } else {
        this.vue.$store.state.reloadFlag = true
      }
    }
    ws.onmessage = (e) => {
      this.wsHeartBeat.reset()
      let {
        data
      } = e

      try {
        data = JSON.parse(e.data)
      } catch (err) {
        this.config.errorData += 1
        if (this.config.errorData > 3) {
          this.vue.$store.state.reloadFlag = true
          this.closeWS()
        }
      }
      if (!data) {
        return
      }

      if (data.action === 'ready') {
        this.sendWS({
          action: 'login',
          token: store.state.m6app.chatRoomToken
        })
      }

      if (data.action === 'onLogin' && data.event) {
        this.sendWS({
          action: 'joinRoom',
          room: 'sport'
        })
      }

      if (data.action === 'onJoinRoom') {
        const username = data.name
        const {
          chList
        } = data
        const {
          literalLimit,
          talkInterval
        } = data.service
        store.dispatch('m6app/actionsetChatRoomSetting', {
          username,
          channelList: chList,
          literalLimit,
          talkInterval
        })
      }

      if (data.action === 'onMessage') {
        const {
          nickname,
          content,
          timestamp,
          ch
        } = data.data[0]
        store.dispatch('m6app/actionsetChatRoomInbox', {
          nickname,
          content,
          timestamp,
          ch
        })
      }
    }
  }

  // 關閉連線
  closeWS() {
    this.ws.onclose = () => {
      this.config.live = false
    }
    this.ws.close()
    this.ws.disconnect = true
  }

  sendWS(val) {
    this.ws.send(JSON.stringify(val))
  }

  reconnect() {
    if (this.config.reconnectTimesCount <= this.config.maxWebsocketRetries) {
      setTimeout(() => {
        this.connect()
      }, this.config.wsHeartCheck.heartbeat)
    }
  }
}
