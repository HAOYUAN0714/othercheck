import {
    mapMutations,
    mapActions,
    mapState
} from 'vuex'
import config from '@/config/config'
import moment from 'moment'
export default {
    data() {
        return {
            collapseTable: {
                0: false, //滾球
                1: false //今日
            },
            odds: {},
            firstGet: true, //第一次進來才展開所有ｄａｔａ！
            sportList: config.sportList,
            //關閉的賽事清單
            collapsedCompetitionList: []
        };
    },
    props: {
        show: {
            type: Boolean,
            default: () => {
                return true;
            }
        },
        list: {
            type: Object,
            default: () => {}
        },
        liveList: {
            type: Object,
            default: () => {}
        },
        currentPage: {
            type: Number,
            default: () => 1
        },
        orderBy: {
            type: Number,
            default: () => 2
        },
        totalPage: {
            type: Number,
            default: () => 1
        },
        sportId: {
            type: Number,
            default: () => 1
        },
        oddsType: {
            type: Number,
            default: () => 1
        },
        betType: {
            type: Number,
            defualt: () => 1
        },
        showNonLive: {
            type: Boolean,
            default () {
                return true
            }
        },
        showLive: {
            type: Boolean,
            default () {
                return true
            }
        },
    },
    computed: {
        ...mapState({
            currentMarket: state => state.status.Market,
            isCombo: state => state.status.isCombo
        }),
        parsedLiveEvent() {
            const {
                showLive
            } = this;
            if (!showLive) {
                return {}
            }
            var {
                liveList,
                orderBy
            } = this;
            if (liveList == undefined) {
                return {
                    Events: []
                }
            }
            var list = liveList.Events ? liveList : {
                Events: []
            };

            let oddsTable = {};
            var arr = []
            let events = list.Events;
            for (var i = 0; i < events.length; i++) {

                var maxMarketLevel = 1; //給讓球用的
                var oddsList = {
                    0: {
                        //period id
                        1: {}, //讓球
                        2: {}, //大小
                        3: {}, //標準,
                        4: {}, //獨贏
                        5: {}
                    },
                    1: {
                        //period id
                        1: {}, //讓球
                        2: {}, //大小
                        3: {}, //標準,
                        4: {}, //獨贏
                        5: {}
                    },
                    2: {
                        //period id
                        1: {}, //讓球
                        2: {}, //大小
                        3: {}, //標準,
                        4: {}, //獨贏
                        5: {}
                    },
                    3: {
                        //period id
                        1: {}, //讓球
                        2: {}, //大小
                        3: {}, //標準,
                        4: {}, //獨贏
                        5: {}
                    }
                }; //賠率清單

                oddsTable[events[i]["EventId"]] = {
                    ...oddsTable[events[i]["EventId"]]
                };


                for (var j = 0; j < events[i].MarketLines.length; j++) {
                    if (maxMarketLevel < events[i].MarketLines[j].MarketLineLevel) {
                        maxMarketLevel = events[i].MarketLines[j].MarketLineLevel;
                    } { //如果是讓球的情況下，要加上玩法id

                        oddsList['type'] = 'periodId'
                        if (oddsList[events[i].MarketLines[j].PeriodId] == undefined)
                            oddsList[events[i].MarketLines[j].PeriodId] = {};
                        oddsList[events[i].MarketLines[j].PeriodId]['type'] = 'betType'
                        if (oddsList[events[i].MarketLines[j].PeriodId][events[i].MarketLines[j].BetTypeId] == undefined)
                            oddsList[events[i].MarketLines[j].PeriodId][events[i].MarketLines[j].BetTypeId] = {};
                        oddsList[events[i].MarketLines[j].PeriodId][events[i].MarketLines[j].BetTypeId]
                            ['type'] = "marketLineLevel"
                        for (var p = 0; p < events[i].MarketLines[j].WagerSelections.length; p++) {
                            let infoObj = {
                                MarketlineId: events[i].MarketLines[j].MarketlineId,
                                BetTypeId: events[i].MarketLines[j].BetTypeId,
                                PeriodId: events[i].MarketLines[j].PeriodId,
                                MarketlineStatusId: events[i].MarketLines[j].MarketlineStatusId,
                                PeriodName: events[i].MarketLines[j].PeriodName,
                                BetTypeName: events[i].MarketLines[j].BetTypeName,
                                IsLocked: events[i].MarketLines[j].IsLocked,
                                AwayScore: events[i].AwayScore,
                                HomeScore: events[i].HomeScore,
                                Competition: events[i].Competition,
                                EventDate: events[i].EventDate,
                                EventGroupId: events[i].EventGroupId,
                                EventGroupTypeId: events[i].EventGroupTypeId,
                                HomeTeam: events[i].HomeTeam,
                                AwayTeam: events[i].AwayTeam,
                                Market: events[i].Market,
                                OpenParlay: events[i].OpenParlay,
                                TotalMarketLineCount: events[i].TotalMarketLineCount,
                                SportId: this.sportId,
                                EventId: events[i].EventId,
                            }
                            events[i].MarketLines[j].WagerSelections[p] = Object.assign({}, events[i].MarketLines[j].WagerSelections[p], infoObj)

                        }
                        oddsList[events[i].MarketLines[j].PeriodId][events[i].MarketLines[j].BetTypeId][events[i].MarketLines[j].MarketLineLevel] = events[i].MarketLines[j].WagerSelections;

                    }
                }
                if (oddsList['1']['1']) //如果有全場讓球
                {
                    if (oddsList['1']['1']['1']) {
                        if (oddsList['1']['1']['1'][0].Handicap > 0)
                            events[i].StrongTeam = "HomeTeam"
                        if (oddsList['1']['1']['1'][0].Handicap < 0)
                            events[i].StrongTeam = "AwayTeam"
                    }
                }
                let obj = {
                    'eventInfo': events[i],
                    'oddsList': oddsList,
                    'maxMarketLevel': maxMarketLevel
                }
                arr.push(obj);

                oddsTable[events[i]["EventId"]]['eventInfo'] = events[i]
                oddsTable[events[i]["EventId"]]["oddsList"] = oddsList;
                oddsTable[events[i]["EventId"]]['maxMarketLevel'] = maxMarketLevel
            }
            return arr;
        },
        parsedData() {
            var {
                list,
                betType,
                orderBy
            } = this;
            let oddsTable = [];
            if (list == undefined) {
                return oddsTable;
            }
            let events = list.Events;
            if (events) {
                for (var i = 0; i < events.length; i++) {
                    let maxMarketLevel = 1;
                    var oddsList = {
                        0: {
                            //period id
                            1: {}, //讓球
                            2: {}, //大小
                            3: {}, //標準,
                            4: {}, //獨贏
                            5: {}
                        },
                        1: {
                            //period id
                            1: {}, //讓球
                            2: {}, //大小
                            3: {}, //標準,
                            4: {}, //獨贏
                            5: {},
                        },
                        2: {
                            //period id
                            1: {}, //讓球
                            2: {}, //大小
                            3: {}, //標準,
                            4: {}, //獨贏
                            5: {}
                        },
                        3: {
                            //period id
                            1: {}, //讓球
                            2: {}, //大小
                            3: {}, //標準,
                            4: {}, //獨贏
                            5: {}
                        }
                    };

                    for (var j = 0; j < events[i].MarketLines.length; j++) {
                        if (maxMarketLevel < events[i].MarketLines[j].MarketLineLevel) {
                            maxMarketLevel = events[i].MarketLines[j].MarketLineLevel;
                        }
                        for (var p = 0; p < events[i].MarketLines[j].WagerSelections.length; p++) {
                            let infoObj = {
                                MarketlineId: events[i].MarketLines[j].MarketlineId,
                                BetTypeId: events[i].MarketLines[j].BetTypeId,
                                PeriodId: events[i].MarketLines[j].PeriodId,
                                MarketlineStatusId: events[i].MarketLines[j].MarketlineStatusId,
                                PeriodName: events[i].MarketLines[j].PeriodName,
                                BetTypeName: events[i].MarketLines[j].BetTypeName,
                                IsLocked: events[i].MarketLines[j].IsLocked,
                                AwayScore: events[i].AwayScore,
                                HomeScore: events[i].HomeScore,
                                Competition: events[i].Competition,
                                EventDate: events[i].EventDate,
                                EventGroupId: events[i].EventGroupId,
                                EventGroupTypeId: events[i].EventGroupTypeId,
                                HomeTeam: events[i].HomeTeam,
                                AwayTeam: events[i].AwayTeam,
                                Market: events[i].Market,
                                OpenParlay: events[i].OpenParlay,
                                TotalMarketLineCount: events[i].TotalMarketLineCount,
                                SportId: this.sportId,
                                EventId: events[i].EventId,
                            }
                            events[i].MarketLines[j].WagerSelections[p] = Object.assign({}, events[i].MarketLines[j].WagerSelections[p], infoObj)
                        } { //如果是讓球的情況下，要加上 - >玩法id -> marketline level 
                            if (oddsList[events[i].MarketLines[j].PeriodId] == undefined) {
                                oddsList[events[i].MarketLines[j].PeriodId] = {};
                            }
                            oddsList[events[i].MarketLines[j].PeriodId]['type'] = 'betType'

                            oddsList['type'] = 'periodId'
                            if (oddsList[events[i].MarketLines[j].PeriodId][events[i].MarketLines[j].BetTypeId] == undefined) {
                                oddsList[events[i].MarketLines[j].PeriodId][events[i].MarketLines[j].BetTypeId] = {};
                            }
                            oddsList[events[i].MarketLines[j].PeriodId][events[i].MarketLines[j].BetTypeId]
                                ['type'] = "marketLineLevel"

                            oddsList[events[i].MarketLines[j].PeriodId][events[i].MarketLines[j].BetTypeId][events[i].MarketLines[j].MarketLineLevel] = events[i].MarketLines[j].WagerSelections;
                        }
                    }
                    if (oddsList['1']['1']) //如果有全場讓球
                    {
                        if (oddsList['1']['1']['1']) //如果有全場讓球
                        {
                            if (oddsList['1']['1']['1'][0].Handicap > 0)
                                events[i].StrongTeam = "HomeTeam"
                            if (oddsList['1']['1']['1'][0].Handicap < 0)
                                events[i].StrongTeam = "AwayTeam"
                        }
                    }
                    let obj = {
                        'eventInfo': events[i],
                        'oddsList': oddsList,
                        'maxMarketLevel': maxMarketLevel
                    }
                    oddsTable.push(obj);
                }
            }
            return oddsTable;
        },
        leagueList() {
            const {
                layoutData
            } = this;
            let Leagues = []
            for (var event in layoutData[0]) {
                if (layoutData[0].hasOwnProperty(event)) {
                    if (!Leagues.includes(layoutData[0][event].eventInfo.Competition.CompetitionId))
                        Leagues.push(layoutData[0][event].eventInfo.Competition)
                }
            }
            for (var event in layoutData[1]) {
                if (layoutData[1].hasOwnProperty(event)) {
                    if (!Leagues.includes(layoutData[1][event].eventInfo.Competition.CompetitionId))
                        Leagues.push(layoutData[1][event].eventInfo.Competition)
                }
            }

            return Leagues
        },

        layoutData() {
            return {
                0: this.parsedLiveEvent, //滾球
                1: this.parsedData //非滾球
            }
        },
    },
    methods: {
        ...mapMutations([
            "$_setMorePlayEvent",
            "$_commonLoadingMask",
        ]),
        ...mapActions(['apiGetSelectedEventInfo']),
        $_sameLeague(currentEvent, prevEvent, index) {
            if (index == 0) {
                return true;
            }
            if (prevEvent == undefined) {
                return true;
            }
            if (currentEvent.eventInfo.Competition.CompetitionId == prevEvent.eventInfo.Competition.CompetitionId)
                return false;
            return true;
        },
        $_showMorePlays(event) {
            event.sportId = this.sportId
            const {
                oddsType,
                sportId,
                isCombo
            } = this;
            this.$_commonLoadingMask(true)
            this.apiGetSelectedEventInfo({
                SportId: sportId,
                EventIds: [event.EventId],
                OddsType: oddsType,
                IsCombo: isCombo,
                IncludeGroupEvents: false

            }).then(res => {
                this.$_commonLoadingMask(false)
                this.$_setMorePlayEvent({
                    ...res.data.Sports[0].Events[0],
                    sportId: this.sportId
                })
            }).catch(err => {
                this.$_commonLoadingMask(false)
            })

        },

        $_getOddsBySelectionId(betList, selectionId) {
            for (var i = 0; i < betList.length; i++) {
                if (betList[i].SelectionId == selectionId) return betList[i];
            }
            return {
                Odds: ""
            };
        },

        $_isEmpty(obj) {
            for (var key in obj) {
                if (obj.hasOwnProperty(key))
                    return false;
            }
            return true;
        },
        $_parseDate(date) {
            var d = new Date(date);
            var year = moment(d)
                .utcOffset(-4)
                .format("YYYY");
            var md = moment(d)
                .utcOffset(-4)
                .format("MM/DD");
            var hr = moment(d)
                .utcOffset(-4)
                .format("HH:mm");
            return {
                yr: year,
                md: md,
                hm: hr
            };
        },

        $_selectPage(page) {
            this.$emit("goPage", page);
        },
        $_scrollTop() {
            this.$emit("goTop");
        },
        $_collapseTable(index) {
            this.collapseTable[index] = !this.collapseTable[index]
        },
        $_collapseEvent(CompetitionId) {
            if (!this.collapsedCompetitionList.includes(CompetitionId))
                this.collapsedCompetitionList.push(CompetitionId)
            else {
                const index = this.collapsedCompetitionList.indexOf(CompetitionId)
                this.collapsedCompetitionList.splice(index, 1)
            }
        },
        $_selectChatRoom(event) {
            this.$store.commit('$_selectChatRoom', event)
        },

    },

    mounted() {
        // this.$_getLiveEventInfo()
    }
};
