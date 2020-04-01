<template>
  <div class="container">
    <div class="page">
      <div>
        <input id="identifier-1" class="toggle-box" type="checkbox">
        <label for="identifier-1">
          <div class="title">
            <img src="@/assets/betball/basketball.png" alt="">
            <span>英格藍冠軍聯賽</span>
          </div>
        </label>
        <div>
          <router-link to="/sportDetail">
            <ul>
              <li>
                <img src="@/assets/betball/basketball.png" alt="">
              </li>
              <li class="detail">
                <div class="time">12-31 03:45</div>
                <div class="team">小熊維你 <strong> vs </strong> 你維小熊</div>
                <div class="state">
                  <span>未開始</span>
                  <span><i class="fa fa-television" aria-hidden="true" /></span>
                </div>
              </li>
              <li>
                <img src="@/assets/betball/basketball.png" alt="">
              </li>
            </ul>
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import { getToday } from '@/utils/getBallData'
import { getEventInfoByPage } from '@/api/betball'

export default {
  name: 'SportList',
  props: {
    // getCurSportId: {
    //   type: Number,
    //   default: 1
    // }
  },
  data() {
    return {
      activeNames: ['1']
    }
  },
  computed: {
    ...mapGetters({
      getCurSportId: 'm6app/getCurSportId'
    //   preferPlayGroup: 'betball/getPreferPlayGroup'
    }),
    list() {
      getToday(1).then((res) => {
        // console.log(res, 'getToday res');
      })
    }
  },
  mouted() {
    //   console.log(this.getCurSportId, 'getToday')
  },
  methods: {
    handleChange(val) {
      // console.log(val);
    },
    detail() {
      //   console.log(this.$router.options, 'this.$router')
      //   this.$router.push({ path: 'sportDetail' });
    }
  }
}
</script>

<style lang="scss" scoped>
.container {
    .page {
        margin-top: 10px;
        color: #808080;
        div {
            .toggle-box {
                display: none;
            }
            .toggle-box + label {
                cursor: pointer;
                display: block;
                font-weight: bold;
                line-height: 21px;
                font-size: 12px;
                margin-bottom: 5px;
                .title {
                    width: 80vw;
                    min-width: 160px;
                    padding: 0px 20px;
                    background: linear-gradient(135deg, #e4e7edde 86%, transparent 0);
                    img {
                        width: 20px;
                        margin: 0px;
                        vertical-align: middle;
                    }
                }
            }
            .toggle-box + label + div {
                display: none;
                // background-color: palegoldenrod;
                margin-bottom: 10px;
            }
            .toggle-box:checked + label + div {
                display: block;
                padding: 10px 20px;
                ul {
                    list-style: none;
                    margin: 0;
                    padding: 0;
                    li {
                        display: inline-block;
                        img {
                            width: 30px;
                        }

                    }
                    .detail {
                        text-align: center;
                        width: 60vw;
                        .time {
                            font-size: 12px;
                        }
                        .team {
                            font-weight: bold;
                            strong {
                                margin: 0px 15px;
                            }
                        }
                        .state {
                            font-size: 12px;
                            span {
                                &:first-child {
                                    border-radius: 10px 0px 0px 10px;
                                    width: 70vw;
                                    padding: 0px 10px;
                                    background: linear-gradient(135deg, #737477 86%, transparent 0);
                                    color: #fff;
                                }
                                    border-radius: 0px 10px 10px 0px;
                                    padding: 0px 10px;
                                    background: linear-gradient(-45deg, rgba(228,231,237,0.870588) 75%, transparent 0);
                            }
                        }
                    }
                }
            }
            .toggle-box + label:before {
                color: gray;
                content: "^";
                display: block;
                float: right;
                font-weight: bold;
                height: 20px;
                line-height: 20px;
                margin-right: 5px;
                text-align: center;
                width: 30px;
            }
            .toggle-box:checked + label:before {
                content: "-";
            }
        }
    }
}
</style>
