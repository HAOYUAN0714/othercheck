<template>
  <div class="modal">
    <div v-show="!isSelectLang">
      <div class="setting-header clearfix">
        <div class="left-icon" @click="toMainPage">
          <i class="el-icon-arrow-left" />
        </div>
        <div class="right" @click="toMainPage">
          <i class="fas fa-times" />
        </div>
      </div>
      <div class="setting-list">
        <div class="balance-wrap clearfix">
          <div class="left">
            <div class="account">{{ memberCode }}</div>
            <div class="balance">RMB: {{ memberBalance }}</div>
          </div>
          <div class="right" @click="getUserBalance">
            <i class="fas fa-sync-alt" />
          </div>
        </div>
        <!--帳戶紀錄-->
        <div class="list clearfix" @click="toBetRecord">
          <div class="left">帳戶紀錄</div>
          <div class="right">
            <i class="fas fa-chevron-right" />
          </div>
        </div>
        <!--language-->
        <!--威尼斯人暫無語系切換功能-->
        <!-- <div class="list clearfix" @click="changeLang">
          <div class="left">{{ langList[curLang] }}</div>
          <div class="right">
            <i class="fas fa-chevron-right" />
          </div>
        </div> -->
        <!--logout-->
        <!-- <div class="list clearfix">
          <div class="left">登出</div>
        </div> -->
      </div>
    </div>
    <!--lang select-->
    <div v-if="isSelectLang">
      <div class="setting-header clearfix">
        <div class="left-icon" @click="toSetting">
          <i class="el-icon-arrow-left" />
        </div>
        <div class="right" @click="toSetting">
          <i class="fas fa-times" />
        </div>
      </div>
      <div
        v-for="(lang, key) in langList"
        :key="`lang-${key}`"
        class="list clearfix"
        @click="selectLang(key)"
      >
        <div class="left">{{ lang }}</div>
      </div>
    </div>
  </div>
</template>

<script>
import { getBalance, getUser } from '@/utils/getBallData'

export default {
  data() {
    return {
      curLang: this.$cookie.get('abs-lang'),
      langList: {
        'CHS': '簡體',
        'ENG': 'English'
      },
      isSelectLang: false,
      memberCode: '',
      // memberCode: this.$cookie.get('abs-mem'),
      memberBalance: ''
    }
  },
  created() {
    this.getUserBalance()
    getUser().then((res) => {
      this.memberCode = res
    })
  },
  methods: {
    toMainPage() {
      this.$emit('closeModal', false)
    },
    changeLang() {
      this.isSelectLang = true
    },
    toSetting() {
      this.isSelectLang = false
    },
    selectLang(lang) {
      if (lang === this.curLang) {
        return
      }
      this.$cookie.set('abs-lang', lang)
      location.reload()
    },
    toBetRecord() {
      this.$router.push('/betting-history/unsettled')
    },
    getUserBalance() {
      getBalance().then((res) => {
        if (res) {
          this.memberBalance = res.toFixed(2)
          return
        }
        this.memberBalance = '0.00'
      })
    }
  }
}
</script>

<style lang="scss" scoped>

.modal {
    background-color: #C1C1C1;
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 1;
}
.setting-header {
    height: 48px;
    background-color: var(--side_menu1_bg);
    color: var(--side_menu1_text);
    .left-icon {
        background-color: var(--side_menu1_btn_bg);
        float: left;
        width: 50px;
        height: 48px;
        color: var(--side_menu1_text);
        line-height: 48px;
        text-align: center;
        .el-icon-arrow-left {
            font-size: 18px;
            font-weight: bold;
        }
    }
    .right {
        float: right;
        width: 50px;
        height: 48px;
        line-height: 48px;
        text-align: center;
        .fa-times {
            font-size: 28px;
        }
    }
}
.balance-wrap,
.list {
    background-color: var(--side_menu2_bg);
    min-height: 49px;
    border-top: 1px solid #002231;
    font-size: 18px;
    padding: 0 10px;
    color: var(--side_menu2_text);
    .left {
        float: left;
        .balance {
            color: var(--side_menu2_sub_text);
        }
    }
    .right {
        float: right;
    }
}
.balance-wrap {
    padding-top: 5px;
    .right {
        padding-top: 9px;
    }
}
.list {
    line-height: 49px;
}
</style>
