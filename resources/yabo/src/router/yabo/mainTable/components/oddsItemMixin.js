import {
    mapState,
    mapActions,
    mapMutations
} from "vuex";
export default {
    props: {
        oddsSet: {
            type: Object,
            default: () => {}
        }
    },
    methods: {
        ...mapActions(["apiGetBetInfo"]),
        $_selectedWager(oddsSet) {
            if (!oddsSet) return false;
            const {
                isCombo,
                wager,
                wagerList
            } = this;
            if (!isCombo) {
                //單著模式
                if (wager.WagerSelectionId == oddsSet.WagerSelectionId) return true;
                else return false;
            } else {
                let collision = oddsSet.EventGroupId == 0 ? "EventId" : "EventGroupId";
                let existedWager = wagerList.find(ele => ele[collision] == oddsSet[collision])

                //串觀摩是
                if (existedWager) {
                    //如果這個event的注單存在

                    if (
                        existedWager.WagerSelectionId ==
                        oddsSet.WagerSelectionId
                    ) {
                        return true;
                    } else return false;
                }
                return false;
            }
        },
        $_getBetInfo(wager, oddsInfo) {
            const {
                isCombo
            } = this;
            this.apiGetBetInfo({
                    WagerType: isCombo ? 2 : 1, //單注,2是連串
                    WagerSelectionInfos: wager
                })
                .then(res => {
                    if (res.data.sys_code == 200) {
                        this.$store.commit("$_setPlacingBet", true);
                        //如果抓取成功
                        if (!isCombo) {
                            this.$store.commit("$_setWager", {
                                ...wager[0],
                                ...res.data.WagerSelectionInfos[0],
                                ...res.data.BetSetting[0],
                                HomeTeam: oddsInfo.HomeTeam,
                                SelectionId: oddsInfo.SelectionId,
                                EventDate: oddsInfo.EventDate,
                                WagerSelectionId: oddsInfo.WagerSelectionId,
                                AwayTeam: oddsInfo.AwayTeam,
                                BetTypeId: oddsInfo.BetTypeId,
                                PeriodId: oddsInfo.PeriodId,
                                BetTypeName: oddsInfo.BetTypeName,
                                OutrightTeamId: 0,
                                SportId: oddsInfo.SportId,
                                SelectionName: oddsInfo.SelectionName,
                                Competition: oddsInfo.Competition
                            });
                            this.$store.commit(
                                "$_setComboBetSetting",
                                res.data.BetSetting
                            );
                        } else {
                            for (let i = 0; i < wager.length; i++) {
                                for (let j = 0; j < res.data.WagerSelectionInfos.length; j++) {
                                    if (wager[i].WagerSelectionId == res.data.WagerSelectionInfos[j].WagerSelectionId) {
                                        wager[i] = {
                                            ...wager[i],
                                            ...res.data.WagerSelectionInfos[j]
                                        }
                                        continue;
                                    }
                                }
                            }
                            this.$store.commit("$_setWagerList", wager);
                            this.$store.commit(
                                "$_setComboBetSetting",
                                res.data.BetSetting
                            );
                        }
                    } else {
                        this.$store.commit("$_commonHintMsg", {
                            type: "error",
                            message: res.data.sys_result
                        });
                    }
                });
        },
        $_placeBets(oddsSet) {
            //偵測是否已經存在bet在store裡
            //如果已經有了，１負蓋２同樣的就是移除
            //偵測現在的isCombo
            let {
                isCombo,
                wager,
                betPlaced,
                wagerList
            } = this;
            if (betPlaced == true) {
                this.$store.commit("$_setBetPlaced", false); //更新為還沒下注
            }
            if (!isCombo) {
                //單著模式

                if (wager.WagerSelectionId == oddsSet.WagerSelectionId) {
                    this.$store.commit("$_clearWager");
                }
                //一樣則清空
                else {
                    //不同則覆蓋
                    let wagerSet = {
                        ...oddsSet,
                        BetTypeSelectionId: oddsSet.SelectionId,
                        AwayScore: oddsSet.AwayScore ? oddsSet.AwayScore : 0,
                        HomeScore: oddsSet.HomeScore ? oddsSet.HomeScore : 0,
                        OutrightTeamId: 0,
                        ReturnNearestHandicap: true,
                        Handicap: oddsSet.Handicap ? oddsSet.Handicap : 0,
                    };
                    this.$_getBetInfo([wagerSet], oddsSet);
                }
            } else {
                //串觀摩是

                let wagerListTemp = wagerList;
                let collision = oddsSet.EventGroupId == 0 ? "EventId" : "EventGroupId";
                let existedWager = wagerListTemp.find(ele => ele[collision] == oddsSet[collision])
                if (existedWager) {
                    //如果已存在
                    //重複就清空
                    //不同則就覆蓋
                    let index = wagerListTemp.indexOf(existedWager)
                    if (existedWager.WagerSelectionId == oddsSet.WagerSelectionId) {
                        wagerListTemp.splice(index, 1) //清空
                    } else {
                        wagerListTemp.splice(index, 1) //清空
                        wagerListTemp.push(oddsSet) //插入
                    }
                } else {
                    if (wagerListTemp.length >= 10) {
                        return this.$store.commit("$_commonHintMsg", {
                            type: "error",
                            message: `TEXT_ERROR_STATUS_1337`
                        });
                    }
                    wagerListTemp.push(oddsSet)
                }

                for (let i = 0; i < wagerListTemp.length; i++) {
                    let infoObj = {
                        ReturnNearestHandicap: true,
                        BetTypeSelectionId: wagerListTemp[i].SelectionId,
                        Handicap: wagerListTemp[i].Handicap ?
                            wagerListTemp[i].Handicap : 0,
                    }
                    wagerListTemp[i] = Object.assign({}, wagerListTemp[i], infoObj)
                }
                if (wagerListTemp.length != 0) this.$_getBetInfo(wagerListTemp);
            }
        }
    },
    computed: {
        ...mapState({
            wager: state => state.wager.wager,
            wagerList: state => state.wager.wagerList,
            betPlaced: state => state.wager.betPlaced,
            isCombo: state => state.status.isCombo
        }),
    }
}
