(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2ee360f1"],{"24b8":function(t,e,a){"use strict";a.r(e);var o=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"dashboard-container"},[a("loading",{directives:[{name:"show",rawName:"v-show",value:t.loadingStatus,expression:"loadingStatus"}]}),t._v(" "),a("homeHeader"),t._v(" "),a("navbar"),t._v(" "),a("div",{staticClass:"dashboard-choose"},t._l(t.allEventCount.Favourite&&t.allEventCount.Favourite.Sport,(function(e,o){return a("div",{key:o,staticClass:"dashboard-choose-item"},[a("div",{class:{active:t.sportId==e.SportId},on:{click:function(a){return t.getSportData(e.SportId,1,!0)}}},[a("div",{staticClass:"dashboard-choose-item-text"},[t._v(t._s(e.FavCount)+" "+t._s(e.SportName))])])])})),0),t._v(" "),t.sortedEvent&&0===t.sortedEvent.length&&t.init?a("div",[a("div",{staticClass:"dashboard-scoreListArea"},[a("div",{staticClass:"scoreList"},[a("div",{staticClass:"item sorryArea"},[t._v("\n          "+t._s(t.$t("S_SORRY"))+"\n        ")])])])]):t._e(),t._v(" "),a("matchInfo",{attrs:{"sorted-event":t.sortedEvent,"sport-id":t.sportId}})],1)},n=[],i=(a("6762"),a("2fdb"),a("7514"),a("55dd"),a("75fc")),r=a("db72"),s=a("2f62"),c=a("f616"),d=a("c1df"),u=a.n(d),h=a("824a"),p=a("7ec7"),l=a("14f0"),v=a("935c"),f=a("db0a"),I={name:"Dashboard",components:{loading:c["a"],matchInfo:l["a"],homeHeader:v["a"],navbar:f["a"]},data:function(){return{total:0,pagesize:1,currentPage:1,icon:h["a"],sportId:"",sportData:{},showcode:{},showWay:1,timeout:"",loadingStatus:!1,init:!1}},computed:Object(r["a"])({},Object(s["c"])({navHeader:"m6app/getNavHeader",allEventCount:"getAllEventCount",favoriteMatch:"getFavoriteMatch"}),{ballData:function(){var t=this;return this.sportData.Sports?this.sportData.Sports.filter((function(e){return e.SportId===t.sportId}))[0]:this.sportData},sortedEvent:function(){if(!this.ballData||!this.ballData.Events)return[];var t=Object(i["a"])(this.ballData.Events);return t.sort((function(t,e){return e.Competition.CompetitionId-t.Competition.CompetitionId})),t}}),watch:{allEventCount:function(){var t=this;if(this.firstUpdate(),this.init){this.sportId=this.allEventCount.Favourite.Sport[0].SportId;var e=this.allEventCount.Favourite.Sport.find((function(e){return e.SportId===t.sportId}));e||(this.sportId=this.allEventCount.Favourite.Sport[0].SportId),this.updateSportData(this.sportId,1)}}},mounted:function(){this.firstUpdate()},beforeDestroy:function(){clearInterval(this.timeout)},methods:Object(r["a"])({},Object(s["b"])({setBet:"ball/actionBet"}),{firstUpdate:function(){var t=this;if(!this.allEventCount||!this.allEventCount.Favourite)return{};!1===this.init&&(this.sportId=this.allEventCount.Favourite.Sport[0].SportId,this.getSportData(this.sportId,1),this.timeout=setInterval((function(){t.updateSportData(t.sportId,t.currentPage)}),3e4),this.init=!0)},checkFirst:function(t){if(t-1<0)return!0;var e=this.sortedEvent[t].Competition.CompetitionId,a=this.sortedEvent[t-1].Competition.CompetitionId;return e!==a},clickOdds:function(t,e,a,o){this.setBet({bet:this.getBetTypeData(a.MarketLines,t,e),playType:this.getMarketLineId(a.MarketLines,t),games:a,sportId:+o})},showMode:function(t){var e={1:1,2:2,3:0};return e[t]?e[t]:0},changedPage:function(t){this.currentPage=t,this.sportData={},this.getSportData(this.sportId,this.currentPage)},getIcon:function(t){return this.icon.includes(t)?"icon-".concat(t):"icon-1"},updateSportData:function(t,e){var a=this;Object(p["h"])(t,e).then((function(e){a.sportData=e.data,a.sportId=t,a.total=e.PageSize,a.showWay=1}))},getSportData:function(t,e){var a=this,o=arguments.length>2&&void 0!==arguments[2]&&arguments[2];o&&(this.currentPage=1),this.sportData={},this.loadingStatus=!0,Object(p["h"])(t).then((function(e){a.loadingStatus=!1,a.sportData=e.data,a.sportId=t,a.total=e.PageSize,a.showWay=1})).catch((function(t){a.loadingStatus=!1}))},getBetTypeData:function(t,e,a){var o=t.filter((function(t){return t.BetTypeId===e&&1===t.MarketLineLevel}))[0];return o?o.WagerSelections.filter((function(t){return t.SelectionId===a}))[0]:{}},getMarketLineId:function(t,e){return t.filter((function(t){return t.BetTypeId===e&&1===t.MarketLineLevel}))[0]},show:function(t){this.showcode[t]?delete this.showcode[t]:this.showcode[t]=!0,this.$forceUpdate()},disply:function(t){this.showWay=t},moreGame:function(t){0!==+t.TotalMarketLineCount&&this.$router.push("/detail/".concat(this.sportId,"/").concat(t.EventId))},back:function(){this.$router.go(-1)},formateTime:function(t){return u()(t).utcOffset(-4).format("DD/MM hh:mm:ss")}})},m=I,g=(a("aacc"),a("2877")),S=Object(g["a"])(m,o,n,!1,null,"36407273",null);e["default"]=S.exports},"7ca5":function(t,e,a){},aacc:function(t,e,a){"use strict";var o=a("7ca5"),n=a.n(o);n.a}}]);