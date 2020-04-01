<template>
  <div id="tutorials">
    <div class="header">
      <div>选择体育项目：</div>
      <div class="selectDrop" @click="select=!select">
        {{selectedItem.text}}
        <div class="expandIcon" :class="{active:select}">
          <i class="el-icon-arrow-down"></i>
        </div>
        <transition name="el-zoom-in-top">
          <div class="tutorialDrop" v-show="select">
            <div
              class="li"
              @mouseenter="hoveredItem=item.value"
              @mouseleave="hoveredItem=''"
              v-for="item in selectList"
              :key="item.value"
            >
            <div class="firstItem" @click="selectedItem=item">{{item.text}}</div>
              <div class="external " v-show="item.externalItem" :class="{visible:hoveredItem==item.value}">
                <div 
                  @click="selectedItem=secondItem"
                  v-for="secondItem in item.externalItem" 
                  :key="secondItem.value"
                  class="item2"
                >{{secondItem.text}}
                </div>
              </div>
            </div>
          </div>
        </transition>
      </div>
    </div>
    <components :is="selectedItem.value" />
  </div>
</template>
<script>
import mainPage from "./tutorialPages/mainPage";
import suspension from "./tutorialPages/suspension";
import during from "./tutorialPages/during";
import placechange from "./tutorialPages/placechange";
import error from "./tutorialPages/error";
import abnormal from "./tutorialPages/abnormal";
import batterOdds from "./tutorialPages/batterOdds";
import anyOdds from "./tutorialPages/anyOdds";
import oddsType from "./tutorialPages/oddsType";
import result from "./tutorialPages/result"
import normalRules from "./tutorialPages/normalRules";
import mixCombo from "./tutorialPages/mixCombo";
import cashConditions from "./tutorialPages/cashConditions";
import pcCash from "./tutorialPages/pcCash";
import moblieCash from "./tutorialPages/moblieCash"
import soccer from "./tutorialPages/soccer";
import soccerBetType from "./tutorialPages/soccerBetType";
import baseball from "./tutorialPages/baseball";
import overUnder from "./tutorialPages/overUnder";
import handicap from "./tutorialPages/handicap";
import moneyLine from "./tutorialPages/moneyLine"
import oddEven from "./tutorialPages/oddEven";
import correctScore from "./tutorialPages/correctScore";
import totallScore from "./tutorialPages/totallScore";
import doubleChance from "./tutorialPages/doubleChance";
import HTFT from "./tutorialPages/HTFT";
import basketball from "./tutorialPages/basketball";
import otherBetType from "./tutorialPages/otherBetType";
import tennis from "./tutorialPages/tennis";
import combo from "./tutorialPages/combo";
import badminton from "./tutorialPages/badminton";
import basketballBetType from "./tutorialPages/basketballBetType";
import otherSports from "./tutorialPages/football";
import football from "./tutorialPages/football";
import iceHockey from "./tutorialPages/iceHockey";
import volleyball from "./tutorialPages/volleyball";
import landHockey from "./tutorialPages/landHockey";
import snookerBiliards from "./tutorialPages/snookerBiliards";
import golf from "./tutorialPages/golf";
import gocar from "./tutorialPages/gocar";
import handball from "./tutorialPages/handball";
import waterball from "./tutorialPages/waterball";
import boxing from "./tutorialPages/boxing";
import darts from "./tutorialPages/darts";
import rugby from "./tutorialPages/rugby";
import athletices from "./tutorialPages/athetices";
import virtualWorldCup from "./tutorialPages/virtualWorldCup"
export default {
  data() {
    return {
      secondItem:'',
      selectList: [
        {
          value: "mainPage",
          text: "一般投注规则",
          externalItem:[
            {
              value: "suspension",
              text: "赛事中止与延期"
            },
            {
              value: "during",
              text: "赛事期间"
            },
            {
              value: "placechange",
              text: "场地变更"
            },
            {
              value: "error",
              text: "错误"
            },
            {
              value: "abnormal",
              text: "异常投注行为"
            },
            {
              value: "batterOdds",
              text: "接受更佳赔率"
            },
            {
              value: "anyOdds",
              text: "接受任何赔率"
            },
            {
              value: "oddsType",
              text: "赔率类型"
            },
            {
              value: "result",
              text: "比赛结果"
            },
            {
              value: "normalRules",
              text: "一般投注规则"
          }]
        },
        {
          value:"cashConditions",
          text:"提前兑现条件与条规",
          externalItem:[{
            value: "cashConditions",
            text: "提前兑现条件与条规",
          },
          {
            value:"pcCash",
            text:"怎么在电脑版兑现?"
          },
          {
            value:"moblieCash",
            text:"怎么在手机版兑现?"
          }
          ]
        },
        {
          value: "combo",
          text: "连串过关",
          externalItem:[
            {
              value: "combo",
              text: "连串过关",
            },
            {
              value: "mixCombo",
              text: "混合过关教程​",
          }]
        },
        {
          value: "soccer",
          text: "足球"
        },
        {
          value: "soccerBetType",
          text: "足球投注类型",
          externalItem:[{
              value: "overUnder",
              text: "大小盘",
            },
            {
              value: "handicap",
              text: "让球盘"
            },
            {
              value: "moneyLine",
              text: "1X2 (独赢盘)"
            },
            {
              value: "oddEven",
              text: "单/双(O/E)"
            },
            {
              value: "correctScore",
              text: "波胆"
            },
            {
              value: "totallScore",
              text: "总进球数"
            },
            {
              value: "HTFT",
              text: "半场/全场 (HT.FT)"
            },
            {
              value: "doubleChance",
              text: "双重机会"
            },
            {
              value: "otherBetType",
              text: "其他投注类型"
            }]
        },
        {
          value: "basketball",
          text: "篮球"
        },
        {
          value: "basketballBetType",
          text: "篮球投注类型"
        },
        {
          value: "baseball",
          text: "棒球"
        },
        {
          value: "tennis",
          text: "网球"
        },
        {
          value: "badminton",
          text: "羽毛球"
        },
        {
          value: "otherSports",
          text: "其他运动",
          externalItem:[{
              value: "football",
              text: "美式足球",
            },
            {
              value: "iceHockey",
              text: "冰上曲棍球",
            },
            {
              value: "volleyball",
              text: "排球及沙滩排球",
            },
            {
              value: "landHockey",
              text: "陆上曲棍球",
            },
            {
              value: "snookerBiliards",
              text: "斯诺克与台球/桌球",
            },
            {
              value: "golf",
              text: "高尔夫球",
            },
            {
              value: "gocar",
              text: "赛车",
            },
            {
              value: "handball",
              text: "手球",
            },
            {
              value: "waterball",
              text: "水球",
            },
            {
              value: "boxing",
              text: "拳击",
            },
            {
              value: "darts",
              text: "飞镖",
            },
            {
              value: "rugby",
              text: "橄榄球",
            },
            {
              value: "athletices",
              text: "田径",
            }]
        }
        //製作到一半，被通知暫時無需做虛擬體育，等之後備用
        // {
        //   value:"virtualWorldCup",
        //   text:"虚拟体育"
        // }
      ],
      select: false,
      hoveredItem:'',
      selectedItem: {
        value: "mainPage",
        text: "一般投注规则"
      }
    };
  },
  components: {
    virtualWorldCup,
    mainPage,
    suspension,
    during,
    placechange,
    error,
    abnormal,
    batterOdds,
    anyOdds,
    oddsType,
    result,
    mixCombo,
    normalRules,
    cashConditions,
    pcCash,
    moblieCash,
    soccer,
    soccerBetType,
    otherBetType,
    overUnder,
    handicap,
    moneyLine,
    totallScore,
    oddEven,
    HTFT,
    correctScore,
    doubleChance,
    baseball,
    basketball,
    tennis,
    basketballBetType,
    badminton,
    otherSports,
    football,
    iceHockey,
    volleyball,
    landHockey,
    snookerBiliards,
    golf,
    gocar,
    handball,
    waterball,
    boxing,
    darts,
    rugby,
    athletices,
    combo
  }
};
</script>

<style lang="scss" src="@/scss/router/yabo/main/tutorials.scss"></style>