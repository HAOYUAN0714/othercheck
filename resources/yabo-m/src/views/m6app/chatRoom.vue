<template>
  <div class="chatRoom">
    <!-- <update-timer
      style="display:none"
      ref="updateTimer"
      :updateTime="5"
      @updateFunc="$_pushFakeMsg"
    ></update-timer>-->
    <div v-show="!openChat" class="tab" @click="$_openChat">
      <MessageProcessingOutline />
      <transition name="fade-text" mode="out-in">
        <div :key="currentRoomInbox.length" class="currentMsg">{{ currentMsg.content }}</div>
      </transition>
    </div>
    <transition name="chat" mode="in-out">
      <div v-show="openChat" class="chatTable">
        <!-- 9090 -->
        <div class="header">
          <div class="textSet">
            <div class="top">
              <MessageText />
              <span>聊天室</span>
            </div>
            <span class="bottom">任何聯繫方式均非官方，聯繫造成的任何經濟損失平台概不負責</span>
          </div>
          <div class="closeIcon" @click="openChat=false">
            <i class="el-icon-close" />
          </div>
        </div>
        <div ref="chatBody" class="body">
          <div v-for="(msg,index) in currentRoomInbox" :key="index" class="msg">
            <div class="user">{{ msg.nickname }}:&nbsp</div>
            <div class="text">{{ msg.content }}</div>
          </div>
        </div>
        <div class="inputBox">
          <input v-model="inputText" class="input" spellcheck="false">
          <div class="sendBtn" :class="{validInput:inputText.length!=0}" @click="$_sendText">
            <Send class="sendIcon" />
          </div>
        </div>
        <transition name="hint">
          <div v-show="hasMoreMsg" class="hasMoreMsg" @click="$_scrollBottom()">有更多消息</div>
        </transition>
      </div>
    </transition>
  </div>
</template>
<script>
import { mapGetters, mapActions } from 'vuex'

import {
  getChatRoomUrl,
  getChatRoomToken,
  getChatRoomUserName
} from '@/api/m6'
import $ from 'jquery'
import Send from 'vue-material-design-icons/Send.vue'
import MessageText from 'vue-material-design-icons/MessageText.vue'
import MessageProcessingOutline from 'vue-material-design-icons/MessageProcessingOutline.vue'
import updateTimer from '@/components/UpdateTimer'
import ChatroomJS from '@/utils/chatroom'
export default {
  components: {
    updateTimer,
    Send,
    MessageProcessingOutline,
    MessageText
  },
  props: {
    eventId: {
      type: Number,
      default: 0
    },
    event: {
      type: Object,
      default: () => {
        return {}
      }
    }
  },
  data() {
    return {
      openChat: false,
      inputText: '',
      isMounted: false,
      hasMoreMsg: false
    }
  },
  computed: {
    ...mapGetters({
      socket: 'm6app/getSocket',
      chatRoomSetting: 'm6app/getChatRoomSetting',
      username: 'm6app/getChatRoomUserName',
      ChatRoomInbox: 'm6app/getChatRoomInbox'
    }),
    IsChat() {
      const { event } = this
      return event ? (event.Events ? event.Events[0].IsChat : false) : false
    },
    currentRoomInbox() {
      const { eventId, ChatRoomInbox } = this
      return ChatRoomInbox[eventId] || []
    },

    currentMsg() {
      const { IsChat } = this
      return this.currentRoomInbox[this.currentRoomInbox.length - 1] || !IsChat
        ? { content: '聊天室在開賽前半小時開放' }
        : { content: '點擊進入聊天室，開始聊天喔' }
    }
  },
  methods: {
    ...mapActions({
      actionsetChannel: 'm6app/actionsetChannel'
    }),
    init() {
      this.actionsetChannel(this.eventId)
      getChatRoomUrl().then(res => {
        getChatRoomToken().then(res => {
          this.$store.dispatch(
            'm6app/actionsetSocketInstance',
            new ChatroomJS()
          )
        })
      })
    },
    $_isScrollAtBottom() {
      if (!this.isMounted) return false
      const chatBody = this.$refs.chatBody
      if (
        chatBody.scrollHeight - (chatBody.offsetHeight + chatBody.scrollTop) <
        5
      ) {
        return true
      } else {
        return false
      }
    },
    $_openChat() {
      const { IsChat } = this
      if (!IsChat) return
      this.openChat = true
      this.$_scrollBottom()
    },
    $_scrollBottom() {
      var height = $('.body')[0].scrollHeight
      $('.body').animate({ scrollTop: height }, 200)
      this.hasMoreMsg = false
    },
    $_sendText() {
      this.socket.sendWS({
        action: 'message',
        msg: this.inputText,
        username: this.username,
        ch: `${this.eventId}`
      })
      const { inputText, username } = this
      if (inputText.length != 0) {
        // this.currentRoomInbox.push({
        //   user: username,
        //   text: inputText
        // });
        this.inputText = ''
        this.$_scrollBottom()
      }
    }
  },
  beforeDestroy() {
    this.socket.closeWS()
  },
  mounted() {
    this.init()
    this.isMounted = true
  }
}
</script>
<style lang="scss" scoped>
.hint-enter-active,
.hint-leave-active,
.hint-move {
  transition: 300ms;
  transition-property: opacity, transform;
}

.hint-enter {
  opacity: 0;
  transform: translateY(50px) scaleX(1);
}

.hint-enter-to {
  opacity: 1;
  transform: translateY(0) scaleX(1);
}
.hint-leave-to {
  opacity: 0;
  transform: translateY(-50px) scaleX(1);
}

.hint-leave-active {
  position: absolute;
}

.chat-enter-active,
.chat-leave-active,
.chat-move {
  transition: 300ms;
  position: absolute;
  transition-property: opacity, transform;
}

.chat-enter,
.chat-leave-to {
  transform: translateY(calc(100vh - 45px)) scaleX(1);
}

.chat-enter-to,
.chat-leave {
  transform: translateY(0) scaleX(1);
}
.chatRoom {
  width: 100%;
  z-index: 3;
  background: transparent;
  box-shadow: 0 -3px 6px rgba(0, 0, 0, 0.1), 0 -3px 16px rgba(0, 0, 0, 0.23);
  position: fixed;
  // top: 45px;
  bottom: 0;
  .tab {
    height: 40px;
    width: 100%;
    background: white;
    overflow: hidden;
    display: flex;
    padding: 5px;
    align-items: center;
    & > i {
      color: black;
      font-size: 16px;
    }
    .currentMsg {
      max-width: 50%;
      color: grey;
      font-size: 13px;
      padding: 0 5px;
      text-overflow: ellipsis;
      overflow: hidden;
      white-space: nowrap;
    }
  }
  .chatTable {
    height: calc(100vh - 45px);
    width: 100%;
    position: relative;
    background: white;
    position: fixed;
    top: 45px;
    & > .header {
      width: 100%;
      height: 40px;
      color: white;
      background: black;
      font-weight: 600;
      display: flex;
      align-items: center;
      padding: 0 5px;
      // position: fixed;
      // top: 45px;
      // z-index: 3;
      @media (min-width: 320px) {
        height: 60px;
      }
      .closeIcon {
        margin-left: auto;
        font-size: 16px;
      }
      & > .textSet {
        display: flex;
        padding: 0 5px;
        flex-direction: column;
        & > .top {
          height: 50%;
          display: flex;
          align-items: center;
          line-height: 20px;
        }
        & > .bottom {
          height: 50%;
          font-size: 8px;
          color: rgb(161, 161, 161);
          line-height: 20px;
        }
      }
    }
    & > .body {
      overflow-y: scroll;
      background-color: rgb(248, 248, 248);
      height: 85%;
      // height: calc(100% - 180px);
      @media (min-width: 320px) {
        height: 85%;
      }
      .msg {
        display: flex;
        padding: 0 10px;
        height: auto;
        margin: 8px 0;
        line-height: 20px;
        font-size: 14px;
        .user {
          white-space: nowrap;
          color: rgb(54, 63, 85);
        }
        .text {
          color: rgb(148, 148, 148);
        }
      }
    }
    & > .inputBox {
      width: 100%;
      height: 40px;
      background: white;
      display: flex;
      padding: 0 5px;
      align-items: center;
      position: fixed;
      bottom: 0;
      input {
        height: 30px;
        background-color: rgb(238, 238, 238);
        border: none;
        outline: none;
        padding: 0 5px;
        width: 100%;
      }
      .sendBtn {
        margin-left: 5px;
        width: 40px;
        transition-duration: 0.1s;
        border-radius: 3px;
        height: 30px;
        color: white;
        background: grey;
        display: flex;
        font-size: 18px;
        align-items: center;
        text-align: center;
        .sendIcon {
          margin: auto;
        }
        &.validInput {
          background: orange;
          color: black;
        }
      }
    }
    & > .hasMoreMsg {
      position: absolute;
      bottom: 60px;
      margin: auto;
      display: block;
      border-radius: 3px;
      padding: 0 15px;
      margin-left: 50%;
      left: -50px;
      width: 100px;

      font-size: 12px;
      line-height: 30px;
      height: 30px;
      background: rgb(26, 26, 26);
      color: white;
    }
  }
}
.fade-text-enter-active {
  transition: all 0.3s;
}
.fade-text-leave-active {
  transition: all 0.3s;
}

.fade-text-leave-to {
  transform: translateY(-30px);
}
.fade-text-enter {
  transform: translateY(30px);
}

.fade-enter-active {
  transition: all 0.3s;
}
.fade-leave-active {
  transition: all 0.3s;
}

.fade-leave-to {
  bottom: 100%;
}
.fade-enter {
  bottom: 0;
}
</style>
