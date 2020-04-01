<template>
    <div id="app">
        <vue-draggable-resizable
            class="draggable-stream"
            :key="selectedStreamEvent.Url"
            v-if="selectedStreamEvent.Url"
            :lock-aspect-ratio="true"
            :w="487"
            :parent="true"
            :h="273"
        >
            <div class="closeIcon" @click="selectedStreamEvent = {}">×</div>
            <miniPlayer
                class="streamPlayer"
                :m3u8Url="selectedStreamEvent.Url"
            />
        </vue-draggable-resizable>
        <div class="header"></div>
        <div class="live-body">
            <div class="sidebar">
                <div
                    class="sportIcon"
                    :class="{ selected: selectedSport == 1 }"
                    @click="selectSport(1)"
                >
                    <span>足球</span>
                    <sportIcon :iconKey="'Soccer'" />
                </div>
                <div
                    class="sportIcon"
                    :class="{ selected: selectedSport == 2 }"
                    @click="selectSport(2)"
                >
                    <span>籃球</span>
                    <sportIcon :iconKey="'Basketball'" />
                </div>
                <div class="fakeBg" />
            </div>
            <div class="content">
                <div class="table">
                    <div class="tableTitle">
                        直播賽事清單
                        <div class="updateButton" @click="updateFunc">
                            <span :class="{ rotating: updating === true }"
                                >↻</span
                            >
                            <update-timer
                                ref="updateTimer"
                                :updateTime="120"
                                @updateFunc="updateFunc"
                            ></update-timer>
                        </div>
                    </div>
                    <div class="tableHead">
                        <div class="recentlyChanged" />
                        <div class="eventDate">ID／時間</div>
                        <div class="league">
                            <template>
                                <input
                                    spellcheck="false"
                                    placeholder="聯盟"
                                    v-model="leagueFilter['live']"/>
                                <i class="el-icon-search"
                            /></template>
                        </div>
                        <div class="teams">隊伍</div>
                        <div class="stream">實況</div>
                        <div
                            class="correspond"
                            @click="
                                openDropList['live'] = !openDropList['live']
                            "
                        >
                            <span> {{ selectedFilter["live"]["title"] }}</span>
                            <span
                                class="triangle"
                                :class="{ rotate: openDropList['live'] }"
                                >▾</span
                            >
                            <div class="dropList" v-show="openDropList['live']">
                                <div
                                    class="dropItem"
                                    v-for="key in filterList"
                                    :key="key.key"
                                    @click="selectedFilter['live'] = key"
                                >
                                    {{ key.title }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tableBody" id="liveTable">
                        <div class="tableInside">
                            <div
                                v-show="sortedLiveList.length === 0"
                                class="empty"
                            >
                                暫無該球種資料
                            </div>
                            <div
                                class="tableItem"
                                @click="$_selectEvent(event, 'live')"
                                :class="{
                                    highlight: matchTimes(
                                        event.eventDate,
                                        'live'
                                    ),
                                    currentEvent:
                                        currentMappingEvent.liveEventId ===
                                        event.liveEventId
                                }"
                                v-for="event in sortedLiveList"
                                :key="event.liveEventId"
                            >
                                <div
                                    v-show="$_updateInFourHr(event.created)"
                                    class="recentlyChanged"
                                    :title="
                                        `賽事新增時間：台灣時間${event.created}`
                                    "
                                >
                                    !
                                </div>
                                <div class="eventDate">
                                    <div class="eventId">
                                        {{ event.liveEventId }}
                                    </div>
                                    <div>
                                        {{ $_parseDate(event.eventDate) }}
                                    </div>
                                </div>
                                <div class="league">{{ event.league.cn }}</div>
                                <div class="teams">
                                    <div class="home">
                                        <text-highlight
                                            :queries="targetVocList['im']"
                                            >{{ event.home.cn }}-{{
                                                event.home.en
                                            }}</text-highlight
                                        >
                                    </div>
                                    <div class="away">
                                        <text-highlight
                                            :queries="targetVocList['im']"
                                        >
                                            {{ event.away.cn }}-{{
                                                event.away.en
                                            }}</text-highlight
                                        >
                                    </div>
                                </div>
                                <div class="stream">
                                    <div
                                        class="streamLink"
                                        v-for="(link,
                                        index) in event.liveStreamMobile"
                                        :key="link.Url"
                                    >
                                        <div
                                            class="button"
                                            :class="{
                                                block:!link.Status,
                                                invalid:
                                                    selectedStreamEvent.Url ===
                                                    link.Url
                                            }"
                                            @click="
                                                $_openStream(
                                                    event.liveEventId,
                                                    link
                                                )
                                            "
                                        >
                                            訊源{{ index + 1 }}
                                        </div>
                                    </div>
                                </div>
                                <div class="correspond">
                                    <div
                                        v-if="event.isCorrespond === false"
                                        class="button"
                                        @click.stop="
                                            $_correspond(
                                                currentMappingEvent.imEventId,
                                                event.liveEventId
                                            )
                                        "
                                    >
                                        <span>對應</span>
                                    </div>

                                    <div
                                        v-else
                                        @click.stop="
                                            $_confirmCancel({
                                                eventId: event.imEventId,
                                                type: 1
                                            })
                                        "
                                        class="button invalid"
                                    >
                                        <span>{{ event.imEventId }}</span>
                                        <span class="closeIcon">×</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table">
                    <div class="tableTitle">
                        <span>IM 賽事清單</span>
                    </div>

                    <div class="tableHead">
                        <div class="recentlyChanged" />
                        <div class="eventDate">ID／時間</div>
                        <div class="league">
                            <template>
                                <input
                                    spellcheck="false"
                                    placeholder="聯盟"
                                    v-model="leagueFilter['im']"/>
                                <i class="el-icon-search"
                            /></template>
                        </div>
                        <div class="teams">隊伍</div>
                        <div
                            class="correspond"
                            @click="openDropList['im'] = !openDropList['im']"
                        >
                            <span> {{ selectedFilter["im"]["title"] }}</span>
                            <span
                                class="triangle"
                                :class="{ rotate: openDropList['im'] }"
                                >▾</span
                            >

                            <div class="dropList" v-show="openDropList['im']">
                                <div
                                    class="dropItem"
                                    v-for="key in filterList"
                                    :key="key.key"
                                    @click="selectedFilter['im'] = key"
                                >
                                    {{ key.title }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="divider" />
                    <div class="tableBody" id="imTable">
                        <div class="tableInside">
                            <div
                                v-show="sortedIMList.length === 0"
                                class="empty"
                            >
                                暫無該球種資料
                            </div>
                            <div
                                class="tableItem"
                                @click="$_selectEvent(event, 'im')"
                                :class="{
                                    highlight: matchTimes(
                                        event.eventDate,
                                        'im'
                                    ),
                                    currentEvent:
                                        currentMappingEvent.imEventId ===
                                        event.imEventId
                                }"
                                v-for="event in sortedIMList"
                                :key="event.imEventId"
                            >
                                <div
                                    v-show="$_updateInFourHr(event.created)"
                                    class="recentlyChanged"
                                    :title="
                                        `賽事新增時間：台灣時間${event.created}`
                                    "
                                >
                                    !
                                </div>
                                <div class="eventDate">
                                    <div class="eventId">
                                        {{ event.imEventId }}
                                    </div>
                                    <div>
                                        {{ $_parseDate(event.eventDate) }}
                                    </div>
                                </div>
                                <div class="league">
                                    {{ event.league.cn }}
                                </div>
                                <div class="teams">
                                    <div class="home">
                                        <text-highlight
                                            :queries="targetVocList['live']"
                                            >{{ event.home.cn }}-{{
                                                event.home.en
                                            }}</text-highlight
                                        >
                                    </div>
                                    <div class="away">
                                        <text-highlight
                                            :queries="targetVocList['live']"
                                        >
                                            {{ event.away.cn }}-{{
                                                event.away.en
                                            }}</text-highlight
                                        >
                                    </div>
                                </div>
                                <div class="correspond">
                                    <div
                                        class="button"
                                        v-if="event.isCorrespond === false"
                                        @click.stop="
                                            $_correspond(
                                                currentMappingEvent.liveEventId,
                                                event.imEventId
                                            )
                                        "
                                    >
                                        對應
                                    </div>
                                    <div
                                        v-else
                                        @click.stop="
                                            $_confirmCancel({
                                                eventId: event.liveEventId,
                                                type: 2
                                            })
                                        "
                                        class="button invalid"
                                    >
                                        <span>{{ event.liveEventId }}</span>
                                        <span class="closeIcon">×</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import $ from "jquery";
import miniPlayer from "@/router/yabolive/stream";
import moment from "moment";
import swal from "sweetalert2";
import sportIcon from "@/components/common/sportIcon";
import updateTimer from "@/components/common/updateTimer";
import axios from "axios";
import TextHighlight from "vue-text-highlight";
import Vue from "vue";
Vue.component("text-highlight", TextHighlight);
import config from "@/config/config";
export default {
    data() {
        return {
            matrixList: {},
            liveList: {},
            selectedSport: 1,
            //現在選擇的mapping賽事
            currentMappingEvent: {},
            //現在選擇的mapping賽事的種類
            selectedMappingType: "",
            updating: false,
            filterList: [
                {
                    title: "全部",
                    key: 0
                },
                {
                    title: "未對應",
                    key: 1
                },
                {
                    title: "已對應",
                    key: 2
                }
            ],
            openDropList: {
                im: false,
                live: false
            },
            selectedFilter: {
                im: {
                    title: "全部",
                    key: 0
                },
                live: {
                    title: "全部",
                    key: 0
                }
            },
            leagueFilter: {
                im: "",
                live: ""
            },
            selectedStreamEvent: {} //現在選擇的實況賽事
        };
    },
    watch: {
        currentMappingEvent() {
            const self = this;
            setTimeout(function() {
                self.$_scrollToMatchEvent();
            }, 500);
        }
    },
    components: {
        updateTimer,
        sportIcon,
        miniPlayer
    },
    computed: {
        //球種資料設定檔
        sportList() {
            return config.sportList;
        },
        targetVocList() {
            const { currentMappingEvent, selectedMappingType } = this;
            if (selectedMappingType === "") {
                return { im: [], live: [] };
            }
            let arr1 = currentMappingEvent.home
                ? currentMappingEvent.home.en
                    ? currentMappingEvent.home.en.split(" ")
                    : []
                : [];
            let arr2 = currentMappingEvent.away
                ? currentMappingEvent.away.en
                    ? currentMappingEvent.away.en.split(" ")
                    : []
                : [];
            if (selectedMappingType === "im") {
                return {
                    im: arr1.concat(arr2),
                    live: []
                };
            }
            if (selectedMappingType === "live") {
                return {
                    im: [],
                    live: arr1.concat(arr2)
                };
            }
            return {
                im: [],
                live: []
            };
        },
        sortedIMList() {
            const {
                matrixList,
                selectedSport,
                selectedFilter,
                leagueFilter
            } = this;
            let _filter = selectedFilter["im"];
            let _leagueFilter = leagueFilter["im"];
            if (!matrixList[selectedSport]) return [];
            return (
                matrixList[selectedSport]
                    //  .sort((a, b) => moment(a.eventDate).diff(moment(b.eventDate)))
                    .filter(event => {
                        if (!event.league.cn.includes(_leagueFilter))
                            return false;
                        if (_filter.key === 0) {
                            return true;
                        }
                        if (_filter.key === 1 && event.isCorrespond == false) {
                            return true;
                        }
                        if (_filter.key === 2 && event.isCorrespond == true) {
                            return true;
                        }
                        return false;
                    })
            );
        },
        sortedLiveList() {
            const {
                liveList,
                selectedSport,
                selectedFilter,
                leagueFilter
            } = this;
            let _filter = selectedFilter["live"];
            let _leagueFilter = leagueFilter["live"];
            if (!liveList[selectedSport]) return [];
            return (
                liveList[selectedSport]
                    //  .sort((a, b) => moment(a.eventDate).diff(moment(b.eventDate)))
                    .filter(event => {
                        if (!event.league.cn.includes(_leagueFilter))
                            return false;
                        if (_filter.key === 0) {
                            return true;
                        }
                        if (_filter.key === 1 && event.isCorrespond == false) {
                            return true;
                        }
                        if (_filter.key === 2 && event.isCorrespond == true) {
                            return true;
                        }
                        return false;
                    })
            );
        }
    },
    methods: {
        $_selectEvent(event, type) {
            this.currentMappingEvent = event;
            this.selectedMappingType = type;
        },
        $_openStream(liveEventId, link) {
            if(!link.Status) return;
            this.selectedStreamEvent = link;
        },
        $_updateInFourHr(date) {
            const currentTime = moment().utcOffset("+8:00"); //設為台灣時區
            //console.log(currentTime.format("YYYY/MM/DD HH:mm:ss"), date);
            const diffTime = currentTime.diff(date, "h");
            if (diffTime <= 4) return true;
            return false;
        },
        $_scrollToMatchEvent() {
            const { currentMappingEvent, selectedMappingType } = this;
            if (
                !currentMappingEvent.imEventId &&
                !currentMappingEvent.liveEventId
            )
                return;
            let type = selectedMappingType === "im" ? "live" : "im";
            // 如果正在mapping，就需要自動滑至賽事
            const $tableBody = $(`#${type}Table`);
            const _preScrollTop = $tableBody.scrollTop();

            if (!$(`.highlight`).offset()) {
                return;
            }
            var _scrollTop = $(`.highlight`).offset().top - 148;
            if (_scrollTop) {
                $(`#${type}Table`).animate(
                    { scrollTop: _preScrollTop + _scrollTop },
                    "slow"
                );
            }
        },
        selectSport(id) {
            this.currentMappingEvent = {};
            this.selectedSport = id;
            $(".tableBody").animate({ scrollTop: 0 }, "slow");
        },
        matchTimes(time, type) {
            const {
                currentMappingEvent,
                $_parseDate,
                selectedMappingType
            } = this;
            if (
                !currentMappingEvent.liveEventId &&
                !currentMappingEvent.imEventId
            )
                return false;
            if (selectedMappingType === type) return false;
            let eventDate = currentMappingEvent.eventDate;

            if (
                $_parseDate(eventDate).substring(0, 8) ==
                $_parseDate(time).substring(0, 8)
            )
                return true;
            else return false;
        },
        $_parseDate(time) {
            return moment(time).utcOffset("-04:00").format("MM/DD HH:mm");
        },
        updateFunc() {
            this.updating = true;
            const getMatrix = this.$_getMatrixList();
            const getStream = this.$_getStreamList();
            Promise.all([getMatrix, getStream]).then(() => {
                const _updateTimer = this.$refs.updateTimer;
                this.updating = false;
                if (_updateTimer) {
                    _updateTimer.reCounter();
                }
            });
        },
        //取得matrix賽事清單
        $_getMatrixList() {
            axios.get("/exsport/live/api/get-event-list").then(res => {
                if (res.data.code === 200) {
                    this.matrixList = res.data.data.sport;
                }
            });
        },
        //取得直播清單
        $_getStreamList() {
            axios.get("/exsport/live/api/get-live-list").then(res => {
                if (res.data.code === 200) {
                    this.liveList = res.data.data.sport;
                }
            });
        },
        //對應
        $_correspond(targetId, eventId) {
            if (!targetId) return;
            const { selectedMappingType } = this;
            axios
                .put("/exsport/live/api/correspond", {
                    imEventId:
                        selectedMappingType === "im" ? targetId : eventId,
                    liveEventId:
                        selectedMappingType === "im" ? eventId : targetId
                })
                .then(res => {
                    swal.fire({
                        icon: "success",
                        timer: 1500,
                        showConfirmButton: false,
                        title: "對應成功"
                    });
                    this.updateFunc();
                });
            this.currentMappingEvent = {};
        },
        $_confirmCancel(updateObj) {
            swal.fire({
                title: "確定要取消對應嗎？",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "確定",
                cancelButtonText: "取消"
            }).then(result => {
                const self = this;
                if (result.value) {
                    this.$_cancelCorrespond(updateObj);
                }
            });
        },
        //解除對應
        $_cancelCorrespond({ eventId, type }) {
            axios
                .put("/exsport/live/api/cancel-correspond", {
                    eventId: eventId,
                    type: type //1 是 im 2 是 live
                })
                .then(res => {
                    swal.fire({
                        icon: "success",
                        timer: 1500,
                        showConfirmButton: false,
                        title: "取消成功"
                    });
                    this.updateFunc();
                });
        }
    },
    mounted() {
        this.updateFunc();
    }
};
</script>

<style lang="scss" src="@/scss/router/yabolive/yabolive.scss"></style>
