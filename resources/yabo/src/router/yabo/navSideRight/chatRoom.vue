<template>
  <div class="chatRoom">
    <update-timer
      style="display:none"
      ref="updateTimer"
      :updateTime="10"
      @updateFunc="$_pushFakeMsg"
    ></update-timer>
    <div class="room" v-if="chatroom.EventId">
      <div class="header">{{chatroom.HomeTeam}}vs{{chatroom.AwayTeam}}</div>
      <div class="body">
        <div class="msgTable">
          <!-- <div class="scrollBottom"></div> -->
          <transition-group name="insertMsg">
            <div v-for="(msg,index) in msgList" class="msg" :class="msg.from" :key="msg.time">
              <div class="user" v-show="msg.user!=user">{{msg.user}}</div>
              <div class="text">{{msg.text}}</div>
              <div class="time">{{msg.time}}</div>
            </div>
          </transition-group>
        </div>
        <div class="input">
          <div class="inputBox">
            <input v-model="msg" v-on:keyup.13="$_sendMsg" autofocus="true" spellcheck="false" />
            <div class="sendBtn" @click="$_sendMsg">
              <i class="el-icon-s-promotion" />
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-else class="empty">{{$t('TEXT_SELECT_MATCH_FIRST')}}</div>
  </div>
</template>
<script>
import updateTimer from "@/components/common/updateTimer";
import $ from "jquery";
export default {
  components: {
    updateTimer
  },
  data() {
    return {
      msg: "",
      msgList: [
        {
          user: "Derpy智能AI",
          text: "澳門首家線上賭場上線啦！",
          time: this.$_getCurrentTime(),
          from: "other"
        }
      ]
    };
  },
  watch: {
    msgList() {
      if (this.msgList.length > 20) this.msgList.shift();
      //長度超過20就剪一
    }
  },
  computed: {
    chatroom() {
      return this.$store.state.status.chatroom;
    },
    user() {
      return this.$store.state.common.user;
    }
  },
  methods: {
    $_pushFakeMsg() {
      var random = Math.floor(Math.random() * 6) + 1;
      var msg;
      switch (random) {
        case 1:
          msg = {
            user: "Derpy智能AI",
            text: "Tomorrow comes today (tomorrow comes today)",
            time: this.$_getCurrentTime(),
            from: "other"
          };
          break;
        case 2:
          msg = {
            user: "Derpy智能AI",
            text: "Get the cool! Get the cool shoeshine!",
            time: this.$_getCurrentTime(),
            from: "other"
          };
          break;
        case 3:
          msg = {
            user: "Derpy智能AI",
            text: "給我一瓶酒再給我一支煙",
            time: this.$_getCurrentTime(),
            from: "other"
          };
          break;
        case 4:
          msg = {
            user: "Derpy智能AI",
            text: "Windmill windmill falling down",
            time: this.$_getCurrentTime(),
            from: "other"
          };
          break;
        case 5:
          msg = {
            user: "Derpy智能AI",
            text: "Turn forever hand in hand",
            time: this.$_getCurrentTime(),
            from: "other"
          };
          break;
        case 6:
          msg = {
            user: "Derpy智能AI",
            text:
              "I don't think I'll be here too long, no (I don't think I'll be here too long)",
            time: this.$_getCurrentTime(),
            from: "other"
          };
          break;
        default:
          msg = {
            user: "Derpy智能AI",
            text: "Please repeat the message, It's the music that we choose",
            time: this.$_getCurrentTime(),
            from: "other"
          };
          break;
      }
      this.msgList.push(msg);
    },
    $_getCurrentTime() {
      var d = new Date();
      return d.toLocaleTimeString();
    },
    $_scrollBottom() {
      console.log(
        $(".msgTable").scrollTop(),
        $(".msgTable").innerHeight(),
        $(".msgTable")[0].scrollHeight
      );

      var height = $(".msgTable")[0].scrollHeight;
      $(".msgTable").animate({ scrollTop: height }, 200);
    },
    $_sendMsg() {
      if (this.msg.length > 0) {
        this.msgList.push({
          user: this.$store.state.common.user,
          text: this.msg,
          time: this.$_getCurrentTime(),
          from: "self"
        });
        this.msg = "";
        this.$_scrollBottom();
      }
    }
  }
};
</script>
<style lang="scss" src="@/scss/router/yabo/navRight/chatRoom.scss"></style>