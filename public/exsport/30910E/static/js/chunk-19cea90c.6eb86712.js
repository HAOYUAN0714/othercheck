(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-19cea90c"],{1147:function(t,a,e){t.exports=e.p+"static/img/ic_team_logo_default.dca71da1.png"},4363:function(t,a,e){"use strict";e.r(a);var s=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",{staticClass:"dashboard-container"},[e("loading",{directives:[{name:"show",rawName:"v-show",value:t.loadingStatus,expression:"loadingStatus"}]}),t._v(" "),e("navbar"),t._v(" "),e("div",{staticClass:"dashboard-choose"},t._l(t.allEventCount.Today&&t.allEventCount.Today.Sport,(function(a,s){return e("div",{key:s,staticClass:"dashboard-choose-item"},[e("div",{class:{active:t.sportId==a.SportId},on:{click:function(e){return t.getSportData(a.SportId,1,!0)}}},[e("div",{staticClass:"dashboard-choose-item-text"},[t._v(t._s(a.TodayCount)+" "+t._s(a.SportName))])])])})),0),t._v(" "),e("div",{staticClass:"dashboard-feature"},[e("ul",{staticClass:"feature"},[e("li",[e("span",{class:[t.collapseAllMatch?"show-all-match":"hide-all-match"],on:{click:t.collapseAllLeague}})]),t._v(" "),e("li",{on:{click:t.changeFilterMatchType}},[e("span",{class:[1===t.filterMatchStatus?"time":"league"]})])])]),t._v(" "),t.sortedEvent&&0===t.sortedEvent.length&&t.init?e("div",[e("div",{staticClass:"dashboard-scoreListArea"},[e("div",{staticClass:"scoreList"},[e("div",{staticClass:"item sorryArea"},[t._v("\n          "+t._s(t.$t("S_SORRY"))+"\n        ")])])])]):t._e(),t._v(" "),t.sortedEvent&&t.sortedEvent.length>0?e("div",[e("div",{staticClass:"dashboard-scoreListArea"},[e("div",{staticClass:"scoreList"},[e("div",{staticClass:"title"},[e("el-pagination",{attrs:{background:"",layout:"prev, pager, next",total:t.total,"page-size":1,"current-page":t.currentPage},on:{"update:currentPage":function(a){t.currentPage=a},"update:current-page":function(a){t.currentPage=a},"current-change":t.changedPage}})],1),t._v(" "),t._l(t.sortedEvent,(function(a,s){return e("div",{key:s,staticClass:"text"},[e("div",{staticClass:"oddComponent"},[t.checkFirst(s)?e("div",{staticClass:"text-title",on:{click:function(e){return t.show(a.Competition.CompetitionId)}}},[e("div",{staticClass:"camp"},[e("div",{staticClass:"league-name"},[e("img",{staticClass:"league-img",attrs:{src:"/exsport/img/CompetitionImageFile/"+a.Competition.CompetitionId+".png"},on:{error:t.setDefaultLogo}}),t._v(" "),e("div",{staticClass:"name"},[t._v(t._s(a.Competition.CompetitionName))])])]),t._v(" "),e("div",{staticClass:"arraw"},[e("font-awesome-icon",{staticClass:"icon",attrs:{icon:["fas",t.showcode[a.Competition.CompetitionId]?"angle-up":"angle-down"]}})],1)]):t._e(),t._v(" "),e("div",{directives:[{name:"show",rawName:"v-show",value:!0===!t.showcode[a.Competition.CompetitionId]&&1===t.showWay,expression:"!showcode[games.Competition.CompetitionId] === true && showWay === 1 "}],staticClass:"oddHide"},[e("div",[e("div",{staticClass:"header"},[3===a.Market?e("div",{staticClass:"live"},[e("realTime",{attrs:{"sport-id":t.sportId,time:a.RBTime,"time-status":a.RBTimeStatus}})],1):e("div",{staticClass:"time"},[t._v(t._s(t.formateTime(a.EventDate)))]),t._v(" "),e("div",{staticClass:"count",on:{click:function(e){return t.moreGame(a)}}},[t._v("\n                    "+t._s(a.TotalMarketLineCount)+">\n                  ")]),t._v(" "),e("div",[t._v(t._s(t.$t("S_HANDICAP")))]),t._v(" "),e("div",[t._v(t._s(t.$t("S_OVER_UNDER")))])]),t._v(" "),e("div",{staticClass:"mean"},[e("div",{staticClass:"team"},[e("div",{staticClass:"team-left"},[e("div",{staticClass:"team"},[e("img",{staticClass:"team-img",attrs:{src:"/exsport/img/TeamImageFile/"+a.HomeTeamId+".png"},on:{error:t.setDefaultLogo}}),t._v(" "),e("div",{staticClass:"teamName"},[t._v(t._s(a.HomeTeam))])]),t._v(" "),e("div",{staticClass:"team"},[e("img",{staticClass:"team-img",attrs:{src:"/exsport/img/TeamImageFile/"+a.AwayTeamId+".png"},on:{error:t.setDefaultLogo}}),t._v(" "),e("div",{staticClass:"teamName"},[t._v(t._s(a.AwayTeam))])])]),t._v(" "),e("div",{staticClass:"team-score"},[e("div",{staticClass:"score"},[t._v(t._s(a.HomeScore))]),t._v(" "),e("div",{staticClass:"score"},[t._v(t._s(a.AwayScore))])]),t._v(" "),e("div",{staticClass:"team-right"},[e("div",{staticClass:"oddBox-area"},[e("div",{on:{click:function(e){return t.clickOdds(1,1,a,t.sportId)}}},[t.getMarketLineId(a.MarketLines,1)?e("div",{staticClass:"oddBox",class:{active:t.curBetSlip.some((function(e){return e.odds_id===t.getBetTypeData(a.MarketLines,1,1).WagerSelectionId})),disable:!t.getBetTypeData(a.MarketLines,1,1)||2===+t.getMarketLineId(a.MarketLines,1).MarketlineStatusId||0===+t.getBetTypeData(a.MarketLines,1,1).Odds,up:"up"===t.oddsStatus,down:"down"===t.oddsStatus}},[t.getBetTypeData(a.MarketLines,1,1)&&2!==+t.getMarketLineId(a.MarketLines,1).MarketlineStatusId&&0!==+t.getBetTypeData(a.MarketLines,1,1).Odds&&t.getMarketLineId(a.MarketLines,1)&&!t.getMarketLineId(a.MarketLines,1).IsLocked?e("div",{staticClass:"unlock"},[e("div",{staticClass:"cap"},[t._v("\n                                "+t._s(t.formatCap(t.getBetTypeData(a.MarketLines,1,1),t.getMarketLineId(a.MarketLines,1)))+"\n                              ")]),t._v(" "),e("div",{staticClass:"black",class:{change:"up"===t.oddsStatus||"down"===t.oddsStatus,"no-cap":!t.getBetTypeData(a.MarketLines,1,1).Handicap&&0!==t.getBetTypeData(a.MarketLines,1,1).Handicap}},[t._v("\n                                "+t._s(t.parseOdds(t.getBetTypeData(a.MarketLines,1,1)))+"\n                              ")])]):e("div",{staticClass:"lock"},[e("i",{staticClass:"fas fa-lock"})])]):e("div",{staticClass:"oddBox"},[e("div",{staticClass:"unlock"},[t._v("\n                              -\n                            ")])])]),t._v(" "),e("div",{on:{click:function(e){return t.clickOdds(1,2,a,t.sportId)}}},[t.getMarketLineId(a.MarketLines,1)?e("div",{staticClass:"oddBox",class:{active:t.curBetSlip.some((function(e){return e.odds_id===t.getBetTypeData(a.MarketLines,1,2).WagerSelectionId})),disable:!t.getBetTypeData(a.MarketLines,1,2)||2===+t.getMarketLineId(a.MarketLines,1).MarketlineStatusId||0===+t.getBetTypeData(a.MarketLines,1,2).Odds,up:"up"===t.oddsStatus,down:"down"===t.oddsStatus}},[t.getBetTypeData(a.MarketLines,1,2)&&2!==+t.getMarketLineId(a.MarketLines,1).MarketlineStatusId&&0!==+t.getBetTypeData(a.MarketLines,1,2).Odds&&t.getMarketLineId(a.MarketLines,1)&&!t.getMarketLineId(a.MarketLines,1).IsLocked?e("div",{staticClass:"unlock"},[e("div",{staticClass:"cap"},[t._v("\n                                "+t._s(t.formatCap(t.getBetTypeData(a.MarketLines,1,2),t.getMarketLineId(a.MarketLines,1)))+"\n                              ")]),t._v(" "),e("div",{staticClass:"black",class:{change:"up"===t.oddsStatus||"down"===t.oddsStatus,"no-cap":!t.getBetTypeData(a.MarketLines,1,2).Handicap&&0!==t.getBetTypeData(a.MarketLines,1,2).Handicap}},[t._v("\n                                "+t._s(t.parseOdds(t.getBetTypeData(a.MarketLines,1,2)))+"\n                              ")])]):e("div",{staticClass:"lock"},[e("i",{staticClass:"fas fa-lock"})])]):e("div",{staticClass:"oddBox"},[e("div",{staticClass:"unlock"},[t._v("\n                              -\n                            ")])])])]),t._v(" "),e("div",{staticClass:"oddBox-area"},[e("div",{on:{click:function(e){return t.clickOdds(2,3,a,t.sportId)}}},[t.getMarketLineId(a.MarketLines,2)?e("div",{staticClass:"oddBox",class:{active:t.curBetSlip.some((function(e){return e.odds_id===t.getBetTypeData(a.MarketLines,2,3).WagerSelectionId})),disable:!t.getBetTypeData(a.MarketLines,2,3)||2===+t.getMarketLineId(a.MarketLines,2).MarketlineStatusId||0===+t.getBetTypeData(a.MarketLines,2,3).Odds,up:"up"===t.oddsStatus,down:"down"===t.oddsStatus}},[t.getBetTypeData(a.MarketLines,2,3)&&2!==+t.getMarketLineId(a.MarketLines,2).MarketlineStatusId&&0!==+t.getBetTypeData(a.MarketLines,2,3).Odds&&t.getMarketLineId(a.MarketLines,2)&&!t.getMarketLineId(a.MarketLines,2).IsLocked?e("div",{staticClass:"unlock"},[e("div",{staticClass:"cap"},[t._v("\n                                "+t._s(t.formatCap(t.getBetTypeData(a.MarketLines,2,3),t.getMarketLineId(a.MarketLines,2)))+"\n                              ")]),t._v(" "),e("div",{staticClass:"black",class:{change:"up"===t.oddsStatus||"down"===t.oddsStatus,"no-cap":!t.getBetTypeData(a.MarketLines,2,3).Handicap&&0!==t.getBetTypeData(a.MarketLines,2,3).Handicap}},[t._v("\n                                "+t._s(t.parseOdds(t.getBetTypeData(a.MarketLines,2,3)))+"\n                              ")])]):e("div",{staticClass:"lock"},[e("i",{staticClass:"fas fa-lock"})])]):e("div",{staticClass:"oddBox"},[e("div",{staticClass:"unlock"},[t._v("\n                              -\n                            ")])])]),t._v(" "),e("div",{on:{click:function(e){return t.clickOdds(2,4,a,t.sportId)}}},[t.getMarketLineId(a.MarketLines,2)?e("div",{staticClass:"oddBox",class:{active:t.curBetSlip.some((function(e){return e.odds_id===t.getBetTypeData(a.MarketLines,2,4).WagerSelectionId})),disable:!t.getBetTypeData(a.MarketLines,2,4)||2===+t.getMarketLineId(a.MarketLines,2).MarketlineStatusId||0===+t.getBetTypeData(a.MarketLines,2,4).Odds,up:"up"===t.oddsStatus,down:"down"===t.oddsStatus}},[t.getBetTypeData(a.MarketLines,2,4)&&2!==+t.getMarketLineId(a.MarketLines,2).MarketlineStatusId&&0!==+t.getBetTypeData(a.MarketLines,2,4).Odds&&t.getMarketLineId(a.MarketLines,2)&&!t.getMarketLineId(a.MarketLines,2).IsLocked?e("div",{staticClass:"unlock"},[e("div",{staticClass:"cap"},[t._v("\n                                "+t._s(t.formatCap(t.getBetTypeData(a.MarketLines,2,4),t.getMarketLineId(a.MarketLines,2)))+"\n                              ")]),t._v(" "),e("div",{staticClass:"black",class:{change:"up"===t.oddsStatus||"down"===t.oddsStatus,"no-cap":!t.getBetTypeData(a.MarketLines,2,4).Handicap&&0!==t.getBetTypeData(a.MarketLines,2,4).Handicap}},[t._v("\n                                "+t._s(t.parseOdds(t.getBetTypeData(a.MarketLines,2,4)))+"\n                              ")])]):e("div",{staticClass:"lock"},[e("i",{staticClass:"fas fa-lock"})])]):e("div",{staticClass:"oddBox"},[e("div",{staticClass:"unlock"},[t._v("\n                              -\n                            ")])])])])]),t._v(" "),e("div",{staticClass:"clear"})])])])])])])}))],2)])]):t._e(),t._v(" "),e("transition",[t.isShowFuncAll?e("funcAll",{attrs:{"open-bet-slip":t.isShowFuncAll},on:{closeFuncAll:t.closeFuncAll}}):t._e()],1)],1)},i=[],n=(e("7f7f"),e("6762"),e("2fdb"),e("7514"),e("ac6a"),e("55dd"),e("75fc")),o=e("db72"),c=e("2f62"),r=e("7ec7"),d=e("f616"),l=e("c1df"),u=e.n(l),p=e("824a"),v=e("70bb"),h=e("1147"),k=e.n(h),f=e("4d3f"),g=e("0a60"),m={name:"Dashboard",components:{loading:d["a"],funcAll:v["a"],navbar:f["a"],realTime:g["a"]},data:function(){return{total:0,pagesize:1,currentPage:1,icon:p["a"],sportId:"",sportData:{},sportDetail:{title:"足球",period:this.$t("S_COMMING_MATCH")},showcode:{},showWay:1,timeout:"",loadingStatus:!1,init:!1,isShowFuncAll:!1,nav:"today",curData:[],comboStatus:0,checkRepeat:0,comboOdd:0,errorAllert_combo:"",collisionCheck:[],oddsStatus:"",liveData:{},collapseAllMatch:!1,filterMatchStatus:+this.$cookie.get("filterMatch"),finalInfo:[],liveLen:0}},computed:Object(o["a"])({},Object(c["c"])({navHeader:"m6app/getNavHeader",allEventCount:"getAllEventCount",curGameCount:"curGameCount",curBetSlip:"getBetSlip"}),{navPath:function(){return this.$route.path},ballData:function(){return this.sportData.Sports?this.sportData.Sports[0]:this.sportData},sortedEvent:function(){var t=[],a=[];return this.ballData&&this.ballData.Events?(this.liveData.Sports?0===this.liveData.Sports.length?a=Object(n["a"])(this.ballData.Events):1===this.currentPage?(t.push(this.liveData.Sports[0].Events),t.push(this.sportData.Sports[0].Events),a=[].concat(Object(n["a"])(t[0]),Object(n["a"])(t[1]))):a=Object(n["a"])(this.ballData.Events):a=Object(n["a"])(this.ballData.Events),a.sort((function(t,a){return+a.Market-+t.Market}))):[]}}),watch:{allEventCount:function(){this.firstUpdate()},odds:function(t,a){var e=this;t.Odds>a.Odds&&(this.oddsStatus="up"),t.Odds<a.Odds&&(this.oddsStatus="down"),this.timeout=setTimeout((function(){e.oddsStatus=""}),5e3)},curBetSlip:function(t){var a=this;this.finalInfo=[],this.comboStatus=0,this.errorMsg="",t.forEach((function(t){var e=t.betinfo.apiInput;0!==a.comboStatus&&1!==a.comboStatus||(3===e.WagerSelectionInfos.OddsType&&!0===t.openParlay?(a.collisionCheck.push(t.collisionCheck),a.collisionCheck=a.collisionCheck.filter((function(t,a,e){return e.indexOf(t)===a}))):(a.comboStatus=-1,a.finalInfo=[]))}))}},mounted:function(){this.firstUpdate()},beforeDestroy:function(){clearInterval(this.timeout)},methods:Object(o["a"])({},Object(c["b"])({setBet:"ball/actionBet",actionSetSportCount:"m6app/actionSetSportCount",actionSetNavHeader:"m6app/actionSetNavHeader"}),{setDefaultLogo:function(t){t.target.src=k.a},firstUpdate:function(){var t=this;if(!this.allEventCount||!this.allEventCount.Today)return{};!1===this.init&&(this.sportId=this.allEventCount.Today.Sport[0].SportId,this.getSportData(this.sportId,1),this.timeout=setInterval((function(){t.updateSportData(t.sportId,t.currentPage)}),3e4),this.init=!0)},formatCap:function(t,a){if(t.Handicap||0===t.Handicap){var e=t.Handicap%.5,s=+a.BetTypeId,i=+t.SelectionId;return 0===e?1===s&&1===i?t.Handicap>0?"-".concat(t.Handicap):"".concat(0===t.Handicap?0:+Math.abs(t.Handicap)):1===s&&2===i?t.Handicap>0?"+".concat(t.Handicap):"".concat(0===t.Handicap?0:-Math.abs(t.Handicap)):t.Handicap:1===s&&1===i?t.Handicap>0?"-".concat(Math.abs(t.Handicap-e),"/").concat(Math.abs(t.Handicap+e)):"+".concat(Math.abs(t.Handicap-e),"/").concat(Math.abs(t.Handicap+e)):1===s&&2===i?t.Handicap>0?"+".concat(Math.abs(t.Handicap-e),"/").concat(Math.abs(t.Handicap+e)):"-".concat(Math.abs(t.Handicap-e),"/").concat(Math.abs(t.Handicap+e)):2===s?"".concat(Math.abs(t.Handicap-e),"/").concat(Math.abs(t.Handicap+e)):t.Handicap}},NavRouter:function(t){this.$router.push(t),this.ItemClass=t},checkFirst:function(t){if(t-1<0)return!0;var a=this.sortedEvent[t].Competition.CompetitionId,e=this.sortedEvent[t-1].Competition.CompetitionId;return a!==e},clickOdds:function(t,a,e,s){var i=e.MarketLines.find((function(a){return a.BetTypeId===t})),n=i.WagerSelections.find((function(t){return t.SelectionId===a}));this.setBet({bet:this.getBetTypeData(e.MarketLines,t,a),playType:this.getMarketLineId(e.MarketLines,t),games:e,sportId:+s}),2===i.MarketlineStatusId||0===n.Odds||!0===i.IsLocked?this.isShowFuncAll=!1:this.isShowFuncAll=!this.isShowFuncAll},closeFuncAll:function(t){this.isShowFuncAll=t},showMode:function(t){var a={1:1,2:2,3:0};return a[t]?a[t]:0},changedPage:function(t){this.currentPage=t,this.sportData={},this.getSportData(this.sportId,this.currentPage)},getIcon:function(t){return this.icon.includes(t)?"icon-".concat(t):"icon-1"},updateSportData:function(t,a){var e=this;Object(r["p"])(t,a).then((function(a){e.sportData=a,e.sportId=t,e.total=a.PageSize,e.showWay=1})),Object(r["i"])(t,a).then((function(t){e.liveData=t}))},getSportData:function(t,a){var e=this,s=arguments.length>2&&void 0!==arguments[2]&&arguments[2];s&&(this.currentPage=1),this.sportData={},this.loadingStatus=!0,Object(r["i"])(t,a).then((function(t){e.liveData=t})).then((function(){Object(r["p"])(t,a).then((function(a){e.loadingStatus=!1,e.sportData=a,e.sportId=t,e.total=a.PageSize,e.showWay=1}))})).catch((function(t){e.loadingStatus=!1}))},getBetTypeData:function(t,a,e){var s=t.filter((function(t){return t.BetTypeId===a&&1===t.MarketLineLevel}))[0];return s?s.WagerSelections.filter((function(t){return t.SelectionId===e}))[0]:{}},getMarketLineId:function(t,a){return t.filter((function(t){return t.BetTypeId===a&&1===t.MarketLineLevel}))[0]},show:function(t){this.showcode[t]?delete this.showcode[t]:this.showcode[t]=!0,this.$forceUpdate()},disply:function(t){this.showWay=t},moreGame:function(t){0!==+t.TotalMarketLineCount&&this.$router.push("/detail/".concat(this.sportId,"/").concat(t.EventId,"/").concat(this.$route.name))},back:function(){this.$router.go(-1)},formateTime:function(t){return u()(t).utcOffset(-4).format("MM/DD HH:mm")},collapseAllLeague:function(){var t=this;if(!this.collapseAllMatch)return this.sortedEvent.forEach((function(a){t.showcode[a.Competition.CompetitionId]=!0,t.showcode=Object(o["a"])({},t.showcode,{},a.Competition.CompetitionId)})),void(this.collapseAllMatch=!0);this.showcode={},this.collapseAllMatch=!1},changeFilterMatchType:function(){if(1===+this.$cookie.get("filterMatch"))return this.$cookie.set("filterMatch",2),void location.reload();this.$cookie.set("filterMatch",1),location.reload()}})},M=m,C=(e("e752"),e("2877")),S=Object(C["a"])(M,s,i,!1,null,"2ee47f21",null);a["default"]=S.exports},"4d3f":function(t,a,e){"use strict";var s=function(){var t=this,a=t.$createElement,s=t._self._c||a;return s("div",[s("ul",{staticClass:"morenavbar-area"},[s("li",{staticClass:"morenavbar-item"},[s("div",{staticClass:"logo",on:{click:function(a){return t.back()}}},[s("font-awesome-icon",{staticClass:"icon",attrs:{icon:["fas","arrow-left"]}})],1)]),t._v(" "),s("li",{staticClass:"morenavbar-item",class:{NAVactive:"/moreSportToday"===t.navPath},on:{click:function(a){return t.navRouterReplace("/moreSportToday")}}},[s("div",[t._v(t._s("今日"))])]),t._v(" "),s("li",{staticClass:"morenavbar-item",class:{NAVactive:"/moreSportFuture"===t.navPath},on:{click:function(a){return t.navRouterReplace("/moreSportFuture")}}},[s("div",[t._v(t._s("早盤"))])]),t._v(" "),s("li",{staticClass:"morenavbar-item",class:{NAVactive:"/moreSportCombo"===t.navPath},on:{click:function(a){return t.navRouterReplace("/moreSportCombo")}}},[s("div",[t._v(t._s("串關"))])]),t._v(" "),s("li",{staticClass:"morenavbar-item",class:{NAVactive:"/moreSportFaver"===t.navPath},on:{click:function(a){return t.navRouterReplace("/moreSportFaver")}}},[s("div",[t._v(t._s("關注"))])]),t._v(" "),s("li",{staticClass:"morenavbar-item",class:{NAVactive:"/moreSportFaver"===t.navPath},on:{click:function(a){return t.navRouterReplace("/wager")}}},[s("img",{attrs:{src:e("ef42"),alt:""}})])])])},i=[],n=(e("a481"),e("db72")),o=e("2f62"),c={computed:Object(n["a"])({},Object(o["c"])(["getAllEventCount"]),{navPath:function(){return this.$route.path}}),methods:{back:function(){this.$router.go(-1)},navRouterReplace:function(t){"/moreSportFaver"===t&&0===this.getAllEventCount.Favourite.TotalCount||this.$router.replace(t)}}},r=c,d=(e("5eb7"),e("2877")),l=Object(d["a"])(r,s,i,!1,null,"56584ce9",null);a["a"]=l.exports},"5eb7":function(t,a,e){"use strict";var s=e("7bff"),i=e.n(s);i.a},"7bff":function(t,a,e){},"824a":function(t,a,e){"use strict";a["a"]=["1","2","3","6","7","8","11","13","15","18","19","21","23","25","29","31","32","34","39","40","41","43","44","45","46"]},e752:function(t,a,e){"use strict";var s=e("f8ef"),i=e.n(s);i.a},ef42:function(t,a){t.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEgAAABICAMAAABiM0N1AAAAP1BMVEX///////////////////////////////////////////////////////////////////////8AAAD3tQD////ovCKyAAAAE3RSTlNuGpv95vwctmvZ/vIM80KaTjcAkIQEmgAAAM5JREFUWMPt2NsOgyAMAFBAuSnXzf//1iX1ZahbKFmMuPapqeZEQn1o2eNHwQg6HVqQQdB1oJprJoigk6Hv3UzQNSHq7Nv8a/8D7R91AdVc/42hfd4F9HwLgnqBbtaQHkoOBzlIfQFZqGUclCG1BaShFnFQhFQXkIHaFDBQmCA1BaQkFMdQD4URMqnKKVusL0wxuxrI5bh+zyI24zqfl6aY+XbuT0OLM6T9AoE1SAM72kQk9OnmdLzS4EJiGCn4x92IMtr6GsRbbRTtj7qHXi1zdtR7FS4UAAAAAElFTkSuQmCC"},f8ef:function(t,a,e){}}]);