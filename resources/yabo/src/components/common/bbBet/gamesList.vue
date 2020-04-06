<template>
    <div class="games-wrap">
        <Loading :loaded="sportTypes.length ===  0"/>
        <div class="filters-wrap">
            <!-- <div class="arrow-left"><i class="fas fa-chevron-left"></i></div> -->
            <div class="sport-select-wrap">

                <div class="arrow-left"  @click="goLeft" :class="{'arrow-active': sportTypesCurrentPage > 1}"><i class="fas fa-chevron-left"></i></div>
                <div class="sport-select" ref="sportSelect" >
                    <div
                    class="sport-sort"
                    v-for="item in sportTypes"
                    :key="item.SportId"
                    :class="{'active': currentSportId === item.SportId }"
                    @click="changeList(item.SportId)"
                    >
                        <!--  球種 icon -->
                        <i class="fas fa-futbol" v-if="item.SportName ===  'Soccer' ||  item.SportName ===  '足球' "></i>
                        <i class="fas fa-basketball-ball"  v-else-if="item.SportName ===  'Basketball' ||  item.SportName ===  '篮球' "></i>
                        <i class="fas fa-baseball-ball" v-else-if="item.SportName ===  'Baseball' ||  item.SportName ===  '棒球' "></i>
                        <i class="fas fa-golf-ball"  v-else-if="item.SportName ===  'Golf' ||  item.SportName ===  '高尔夫球' "></i>
                        <i class="fas fa-football-ball"  v-else-if="item.SportName ===  'Rugby' ||  item.SportName ===  '美式足球' "></i>
                        <i class="fas fa-medal" v-else></i>
                        <span class="sport-sort-name">{{ item.SportName }}</span>
                    </div>

                    <!-- <div class="clearfix"></div> -->

                </div>
                <div class="arrow-right" @click="goRight" :class="{'arrow-active': sportTypesPageSize > sportTypesCurrentPage }" ><i class="fas fa-chevron-right"></i></div>
            </div>
            <!-- <div class="arrow-right"><i class="fas fa-chevron-right"></i></div> -->
            <!-- <div  v-if="currentLanguageCode==='CHS'" class="language-controller" @click="changeLanguage('ENG')">English <i class="fas fa-globe-americas"></i></div>
            <div  v-else-if="currentLanguageCode==='ENG'" class="language-controller"  @click="changeLanguage('CHS')">切换简中 <i class="fas fa-globe-americas"></i></div> -->
            <div class="list-title" v-if="currentLanguageCode==='CHS'" v-clickoutside="handleCompetitionClose"  :class="{disabled : competitionData.length === 0}">
                <div @click="competitionDropSwitch = !competitionDropSwitch"  v-if="!isLoading">联盟筛选 <i class="fas fa-filter"></i></div>
                <div class="drop-down" :class="{close : !competitionDropSwitch}">
                    <div class="drop-down-item" v-if="competitionData.length > 1"  :class="{competitionOn: isAllCompetition}"  @click="handleAllCompetition">
                        - 全部 -
                    </div>
                    <!-- <div class="drop-down-item" v-for="item in competitionData" :class="{competitionOn: item.active}"  :key="item.competition"  @click="item.active = !item.active" >
                        {{item.competition}}
                    </div> -->
                    <div class="drop-down-item" v-for="item in competitionData" :class="{competitionOn: item.active}"  :key="item.competition"  @click="handleCompetition(item)" >
                        {{item.competition}}
                    </div>
                </div>
            </div>
            <div class="list-title" v-if="currentLanguageCode==='ENG'"  v-clickoutside="handleCompetitionClose" :class="{disabled : competitionData.length === 0}">
                <div @click="competitionDropSwitch = !competitionDropSwitch" v-if="!isLoading">Competition Filter <i class="fas fa-filter"></i></div>
                <div class="drop-down" :class="{close : !competitionDropSwitch}">
                    <div class="drop-down-item"  v-if="competitionData.length > 1"   :class="{competitionOn: isAllCompetition}"  @click="handleAllCompetition">
                        - ALL -
                    </div>
                    <div class="drop-down-item" v-for="item in competitionData" :class="{competitionOn: item.active}"  :key="item.competition"  @click="handleCompetition(item)"  >
                        {{item.competition}}
                    </div>
                </div>
            </div>
        </div>
        <div class="games-list-wrap">
            <table class="games-list">
            <!-- <table class="games-list" v-scroll="checkPages"> -->
                <thead  v-if="currentLanguageCode==='CHS'">
                    <tr>
                        <!-- <th width="105"  v-if="currentMarketId===2 || currentMarketId===3">
                            <Loading :loaded="isLoading"/>
                            <span v-if="filterList.length !==  0 && !isLoading">
                                {{ filterList[0].EventDate | filtersDateCHS }}
                            </span>
                        </th> -->
                        <th width="105" class="date-title" v-clickoutside="handleDateClose">
                            <Loading :loaded="isLoading"/>
                            <div v-if="dateData.allDate.length !==  0  && !isLoading">
                                <div class="current-date-title"  @click="dropDownActive=!dropDownActive" :class="{noOtherDate : dateData.allDate.length===1}">
                                    {{ currentDate | transStampCHS }}
                                    <i class="fas fa-angle-down date-list-icon"></i>
                                </div>
                                <div class="drop-down" :class="{active : dropDownActive}">
                                    <div class="drop-down-item" v-for="item in dateData.allDate" :class="{active: item === currentDate}"  :key="item"  @click="changeCurrentDate(item)" >
                                        {{item | transStampCHS}}
                                    </div>
                                </div>
                            </div>
                        </th>
                        <th width="40">时间</th>
                        <th width="52">状态</th>
                        <!-- <th width="20"></th> -->
                        <th >主场球队</th>
                        <th width="80">比分</th>
                        <th >客场球队</th>
                        <th width="140">让球赔率</th>
                        <th width="30"></th>
                    </tr>
                </thead>

                <thead  v-else-if="currentLanguageCode==='ENG'">
                    <tr >
                        <!-- <th width="105"  v-if="currentMarketId===2 || currentMarketId===3">
                            <Loading :loaded="isLoading"/>
                            <span v-if="filterList.length !==  0  && !isLoading">
                                {{ filterList[0].EventDate | filtersDateENG }}
                            </span>
                        </th> -->
                        <th width="105" class="date-title"  v-clickoutside="handleDateClose">
                            <Loading :loaded="isLoading"/>
                            <div v-if="dateData.allDate.length !==  0  && !isLoading" >
                                <div class="current-date-title" @click="dropDownActive=!dropDownActive" :class="{noOtherDate : dateData.allDate.length===1}">
                                    {{ currentDate | transStampENG }}
                                    <i class="fas fa-angle-down date-list-icon"></i>
                                </div>
                                <div class="drop-down" :class="{active : dropDownActive}">
                                    <div class="drop-down-item" v-for="item in dateData.allDate" :class="{active: item === currentDate}" :key="item" @click="changeCurrentDate(item)">
                                        {{item | transStampENG}}
                                    </div>
                                </div>
                            </div>
                        </th>
                        <th width="40">Start</th>
                        <th width="52">Status</th>
                        <!-- <th width="20"></th> -->
                        <th>Home</th>
                        <th width="80">Score</th>
                        <th>Away</th>
                        <th width="140">HDP</th>
                        <th width="30"></th>
                    </tr>
                </thead>
                <!-- <tbody v-if="filterList && !isLoading"> -->
                <tbody >
                    <tr v-for="item in filterList"
                        :class="{ active : activeEventData.EventId === item.EventId}"
                        :key="item.EventId"
                        @click="activeEvent(item)"
                    >
                        <td
                            class="competition-name"
                            :style="{ 'backgroundColor': '#'+item.Competition.CompetitionColor }"
                        >{{ item.Competition.CompetitionName }}</td>
                        <td>{{ item.EventDate | filterTime }}</td>
                        <td>
                            <span v-if="item.RBTime === null">-</span>
                            <span v-else style="color: #FC8797">{{ transString(item.RBTime) }}</span>
                        </td>
                        <!-- <td>
                            <i class="fas fa-volume-off audio-icon-off"></i>
                            <i class="fas fa-volume-down audio-icon-on d-none"></i>
                        </td> -->
                        <td class="home-team-name">{{ item.HomeTeam }}</td>
                        <td>
                            <span :class="{'has-score': item.AwayScore || item.HomeScore}">
                                <span v-if="item.HomeScore !== null">{{ item.HomeScore}}</span>
                                <span v-if="item.HomeScore === null">-</span>
                                <span v-html="space" />:
                                <span v-html="space" />
                                <span v-if="item.AwayScore !== null">{{ item.AwayScore }}</span>
                                <span v-if="item.AwayScore === null">-</span>
                            </span>
                            <!-- <span v-else class>
                            -<span v-html="space" />:<span v-html="space" />-
                            </span> -->
                        </td>
                        <td class="away-team-name">{{ item.AwayTeam }}</td>
                        <td v-if="filterBetType(item)">
                            <span class="odds-left">{{ filterBetType(item).WagerSelections[0].Odds }}</span>|
                            <span class="odds-middle">{{ filterBetType(item).WagerSelections[0].Handicap }}</span>
                            |
                            <span class="odds-right">{{ filterBetType(item).WagerSelections[1].Odds }}</span>
                        </td>
                        <td v-else-if="filterBetType(item) === null">
                            <span class="odds-left">-</span>|
                            <span class="odds-middle">-</span>
                            |
                            <span class="odds-right">-</span>
                        </td>
                        <td>
                            <!-- <i class="far fa-pause-circle pause-icon d-none"></i> -->
                            <i class="far fa-play-circle play-icon" :class="{playNone:  !item.LiveStreamingMobile}"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- <Loading :loaded="filterList.length ===  0"/> -->
            <Loading :loaded="isLoading"/>
            <!-- <Loading :loaded="true" /> -->
        <div class="noGame"  v-if="filterList .length === 0 && !isLoading">- 目前選項沒有賽事 -</div>
        </div>


    </div>
</template>

<script>
import Loading from './loading';

export default {
    components: {
        Loading
    },
    mounted () {
        // const vm = this;
        // vm.$nextTick(() => {
        //     let parent = document.querySelector('.sport-select');
        //     let parentWidth = parent.clientWidth;
        //     vm.sportTypesPageSize = Math.ceil(parentWidth / 350);
        //     console.dir(parentWidth, vm.sportTypesPageSize );
        // })

    },
    data() {
        return {
            space: "&nbsp",
            currentLanguageCode: 'CHS', // 目前語系 ENG or CHS  , 不支援繁體
            // languageChanged: false,
            autoUpdateData: { // 自動更新用資料
                intervalTimeId: 0, // 自動更新計時器 Id
                currentIntervalTime: 30, // 目前倒數時間
                hasAutoUpdate: false, // 是否已存在計時
            },
            isLoading: false, // 讀取效果開關
            hasChangePage: false, // 判斷是否已調用分頁
            earilyPageSize: 1, // 早盤總分頁數
            earilyCurrentPage: 1, // 早盤目前頁數
            earilyUsedPage: 1, // 早盤已調用頁數
            earilyIsMax: false,
            todayPageSize: 1, // 今日總分頁數
            todayCurrentPage: 1, // 今日目前頁數
            todayUsedPage: 1, // 今日已調用頁數
            todayIsMax: false,
            dateData: { // 所有日期
                today: '', // 今日
                // earilyDate: [], // 只有早盤日期
                allDate: [], // 包含早盤今日日期
            },
            // currentMarketId: 2, // 判斷 、早盤1、今日2、 的id ，這裡不使用滾球 id
            currentSportId: 1, // 球種 id 預設足球
            currentDate: '', // 目前選定日期
            todayDate: '', // 今日日期
            currentDateChanged: false, // 用來判斷是否預設日期為第1個
            competitionData : [], // 聯盟篩選資料
            isAllCompetition: true, // 預設全部聯盟篩選
            competitionDropSwitch: false, // 聯盟篩選清單開關
            dropDownActive: false, // 日期選單是否開啟
            sportTypes: [], // 所有球種
            sportTypesPageSize: 1, // 所有球種選擇頁數
            sportTypesCurrentPage: 1, // 目前球種選擇頁數
            todaySports: [], // 今日有開盤的球種
            eairlySports: [],  // 早盤有開盤的球種
            todayEvents:[], // 今日含滾球的賽事
            earilyEvents:[], // 早盤的賽事
            listData: [],  // 顯示在列表的資料
            newListData: [],// 更新時的加入資料(早盤)
            newListDataToday: [], // 更新時的加入資料(今日和滾球)
            betTypeIds: 1,
            activeEventData:  {}, // 目前選定的賽事的資料： 使用到比分與視訊
            activeEventSportId: 1, // 目前選定賽事球種 id
            currentResultsPage: 1,
            // earilyDate: [], //  早盤下所有日期 stamp
        };
    },
    filters: {
        filterTime(fullTime) { // 取得開賽時間（美東）
            let hours = new Date(fullTime).getUTCHours() - 4;
            if (hours < 0) {
                hours = 24 + hours
            }
            if (hours < 10) {
                hours = 0 + hours
            }

            let minutes = new Date(fullTime).getUTCMinutes();
            if (minutes < 10) {
                minutes = '0'+minutes;
            }
            return hours+':'+minutes;
        },
        filtersDateCHS(fullTime) { // 利用字串取得今日日期（美東）
            // 美東時間 ＝ 台灣時間 - (12*60*60*1000)毫秒
            let usTimeStamp = new Date(fullTime).getTime()-(12*60*60*1000);
            let month = new Date(usTimeStamp).getMonth()+1;
            let date = new Date(usTimeStamp).getDate();
            if (month < 10) {
                month = '0'+month;
            }
            if (date < 10) {
                date = '0'+date;
            }
            return  month+' 月 '+date+' 日';
        },
        filtersDateENG(fullTime) { // 利用字串取得今日日期（美東）
            // 美東時間 ＝ 台灣時間 - (12*60*60*1000)毫秒
            let usTimeStamp = new Date(fullTime).getTime()-(12*60*60*1000);
            let month = new Date(usTimeStamp).getMonth()+1;
            let date = new Date(usTimeStamp).getDate();
            if (month < 10) {
                month = '0'+month;
            }
            if (date < 10) {
                date = '0'+date;
            }
            return  month+' / '+date;
        },
        transStampCHS(stamp) { // 早盤日期下拉選單
            let month = new Date(stamp).getMonth()+1;
            let date = new Date(stamp).getDate();
            if (month < 10) {
                month = '0'+month;
            }
            if (date < 10) {
                date = '0'+date;
            }
            return  month+' 月 '+date+' 日';
        },
         transStampENG(stamp) { // 早盤日期下拉選單
            let month = new Date(stamp).getMonth()+1;
            let date = new Date(stamp).getDate();
            if (month < 10) {
                month = '0'+month;
            }
            if (date < 10) {
                date = '0'+date;
            }
            return  month+' / '+date;
        },
    },
    methods: {
        // changeLanguage (language) {
        //     this.currentLanguageCode = language;
        //     this.languageChanged = true;
        //     clearInterval(this.autoUpdateData.intervalTimeId);
        //     this.autoUpdateData.currentIntervalTime = 30;
        //     this.autoUpdateData.hasAutoUpdate = false;
        //     this.currentSportId = 1;
        //     this.competitionData  = [],
        //     this.isAllCompetition = true,
        //     this.competitionDropSwitch = false,
        //     this.dropDownActive = false,
        //     this.getData();
        // },
        getReadySports() {
            // 取得已有開出今日與早盤的資料的球種用來製作球種選項
            const vm = this
            const apiHost = "/exsport/live/api/get-all-event-count";
            let apiParams = "?LanguageCode="+vm.currentLanguageCode;
            vm.$http.get(apiHost+apiParams).then(res => {
                if (res.data.data.Today.Sport && !res.data.data.Early.Sport ) {
                    vm.todaySports = res.data.data.Today.Sport;
                    vm.sportTypes = res.data.data.Today.Sport;
                } else if (res.data.data.Early.Sport && !res.data.data.Today.Sport) {
                    vm.eairlySports = res.data.data.Early.Sport;
                    vm.sportTypes = res.data.data.Early.Sport;
                } else {
                    vm.todaySports = res.data.data.Today.Sport;
                    vm.eairlySports = res.data.data.Early.Sport;
                    let allSports = [...vm.todaySports,...vm.eairlySports];
                    let noRepeatId = [];
                    let noRepeatSport = [];
                    allSports.forEach(item => {
                        if(noRepeatId.indexOf(item.SportId) === -1) {
                            noRepeatId.push(item.SportId);
                            noRepeatSport.push(item);
                        }
                    })
                    vm.sportTypes = noRepeatSport;
                    // 如果目前的選的球種並不存在於所有球種裡(資料隨時間更新)，預設球種為所有球種裡的第1個
                    let  sportType = vm.sportTypes.find(item => {
                        return item.SportId === vm.currentSportId;
                    });
                    if (!sportType) {
                        vm.currentSportId =  vm.sportTypes[0].SportId;
                    }
                }
                vm.$nextTick(() => {
                    let parentWidth = vm.$refs.sportSelect.clientWidth
                    vm.sportTypesPageSize = Math.ceil(parentWidth / 350);
                    console.dir(parentWidth, vm.sportTypesPageSize );
                })
            });
        },
        getLiveData () {
            const vm = this;
            const apiHost = "/exsport/live/api/get-live-event-info";
            let apiParams ="?SportIds[]="+vm.currentSportId+"&OddsType=2&ColorCode=30&ColorCon=200&LanguageCode="+vm.currentLanguageCode; // SportId多 s6
            vm.$http.get(apiHost + apiParams).then(res => {
                console.log('滾球api回應',res);
                if (res.data.data.Sports[0] && res.data.data.Sports[0].Events) {
                    vm.newListData.push(...res.data.data.Sports[0].Events);
                    // vm.newListData.push(...res.data.data.Sports[0].Events);
                    console.log('滾球賽事',res.data.data.Sports[0].Events);
                }

            }).then(function () {
                vm.getTodayData ();
                // vm.getByPageData();
            });
        },
        todayApi () {
            const vm = this;
            if (vm.todayPageSize > 1) {
                if (vm.todayCurrentPage < vm.todayPageSize) {
                    vm.todayCurrentPage+=1
                }
                const apiHost = "/exsport/live/api/get-event-info-by-page";
                const firstPageParams ="?SportId="+vm.currentSportId+"&Market=2&Page="+vm.todayCurrentPage+"&OddsType=2&ColorCode=30&ColorCon=200&LanguageCode="+vm.currentLanguageCode;
                vm.$http.get(apiHost + firstPageParams).then(res => {
                    if (res.data.data.Sports[0]) {
                        vm.newListData.push(...res.data.data.Sports[0].Events);
                    }
                }).then(() => {
                    if (vm.todayCurrentPage < vm.todayPageSize) {
                        vm.todayApi ();
                    }
                    else if (vm.todayCurrentPage === vm.todayPageSize) {
                        vm.getByPageData();
                    }
                })
            } else {
                vm.getByPageData();
            }
        },
        earilyApi () {
            const vm = this;
            if (vm.earilyPageSize > 1) {

                if (vm.earilyCurrentPage < vm.earilyPageSize) {
                    vm.earilyCurrentPage+=1
                }
                const apiHost = "/exsport/live/api/get-event-info-by-page";
                const apiParams ="?SportId="+vm.currentSportId+"&Market=1&Page="+vm.earilyCurrentPage+"&OddsType=2&ColorCode=30&ColorCon=200&LanguageCode="+vm.currentLanguageCode;
                // vm.earilyCurrentPage+=1;
                vm.$http.get(apiHost + apiParams).then(response => {
                    if (response.data.data.Sports[0]) {
                        vm.newListData.push(...response.data.data.Sports[0].Events);
                    }
                }).then(() => {
                    if (vm.earilyCurrentPage < vm.earilyPageSize) {
                        vm.earilyApi ();
                    }
                    else if (vm.earilyCurrentPage === vm.earilyPageSize) { // 所有 api 調用完後
                        vm.listData = vm.newListData;
                        // 預設選取的賽事
                        if (vm.activeEventData && vm.activeEventData.EventId && vm.activeEventSportId === vm.currentSportId) { // 如果已有選取賽事且球種 id 吻合 更新後繼續選取
                            vm.activeEvent (vm.activeEventData);
                        } else { // 如果沒有選好的賽事,預設為目前球種第1場
                            let initailEvent = vm.listData[0];
                            vm.activeEvent (initailEvent);
                        }
                        console.log('vm.listData ',vm.listData );
                        // 取聯盟資料
                        let competitions = vm.listData.map(item => {
                            return item.Competition.CompetitionName
                        })
                        let noRepeat = [];   // 處理沒重複的聯盟資料
                        competitions.forEach(item => {  // 取得所有沒重複的聯盟資料
                            if (noRepeat.indexOf(item) === -1) {
                                noRepeat.push(item);
                            }
                        })
                        // let noRepeat = [...(new Set(competitions))]; // 取得所有沒重複的聯盟資料
                        if (vm.isAllCompetition) { // 如果目前啟用全部篩選，所有篩選都啟用
                            vm.competitionData = noRepeat.map(item => {
                                return {
                                    competition: item,
                                    active: true,
                                }
                            })
                        } else { // 如果目前非全部篩選，把新增的聯盟篩選設為啟用，不是就設為與原本相同
                            let beforeCompetition = vm.competitionData
                            vm.competitionData = beforeCompetition.map(item => {
                                if(noRepeat.indexOf(item.competition) === -1) {
                                    return {
                                        competition: item.competition,
                                        active: true,
                                    }
                                } else {
                                    return {
                                        competition: item.competition,
                                        active: item.active,
                                    }
                                }
                            })
                        }
                        console.log('聯盟資料',vm.competitionData);
                        vm.getAllDate();
                        // vm.isLoading = false;
                        vm.autoUpdate();
                    }

                })
            } else {
                        vm.listData = vm.newListData;
                        // 預設選取的賽事
                        if (vm.activeEventData && vm.activeEventData.EventId && vm.activeEventSportId === vm.currentSportId) { // 如果已有選取賽事且球種 id 吻合 更新後繼續選取
                            vm.activeEvent (vm.activeEventData);
                        } else { // 如果沒有選好的賽事,預設為目前球種第1場
                            let initailEvent = vm.listData[0];
                            vm.activeEvent (initailEvent);
                        }
                        console.log('vm.listData ',vm.listData );
                        // 取聯盟資料
                        let competitions = vm.listData.map(item => {
                            return item.Competition.CompetitionName
                        })
                        let noRepeat = [];   // 處理沒重複的聯盟資料
                        competitions.forEach(item => {  // 取得所有沒重複的聯盟資料
                            if (noRepeat.indexOf(item) === -1) {
                                noRepeat.push(item);
                            }
                        })
                        // let noRepeat = [...(new Set(competitions))]; // 取得所有沒重複的聯盟資料
                        if (vm.isAllCompetition) { // 如果目前啟用全部篩選，所有篩選都啟用
                            vm.competitionData = noRepeat.map(item => {
                                return {
                                    competition: item,
                                    active: true,
                                }
                            })
                        } else { // 如果目前非全部篩選，把新增的聯盟篩選設為啟用，不是就設為與原本相同
                            let beforeCompetition = vm.competitionData
                            vm.competitionData = beforeCompetition.map(item => {
                                if(noRepeat.indexOf(item.competition) === -1) {
                                    return {
                                        competition: item.competition,
                                        active: true,
                                    }
                                } else {
                                    return {
                                        competition: item.competition,
                                        active: item.active,
                                    }
                                }
                            })
                        }
                        console.log('聯盟資料',vm.competitionData);
                        vm.getAllDate();
                        // vm.isLoading = false;
                        vm.autoUpdate();
            }
        },
        getTodayData () {
            const vm = this;
            const apiHost = "/exsport/live/api/get-event-info-by-page";
            const firstPageParams ="?SportId="+vm.currentSportId+"&Market=2&Page=1&OddsType=2&ColorCode=30&ColorCon=200&LanguageCode="+vm.currentLanguageCode;
            vm.$http.get(apiHost + firstPageParams).then(res => {
                if (res.data.data.PageSize) { // 取得球種總頁數
                    vm.todayPageSize = res.data.data.PageSize;
                }
                if (res.data.data.Sports[0] && res.data.data.Sports[0].Events) {
                    vm.newListData.push(...res.data.data.Sports[0].Events);
                }
                vm.todayEvents = vm.newListData;
                // 如果最頁數大於 1
                vm.todayApi ();
            })
            // .then(function () {
            //     vm.getByPageData();
            // })
        },
        getByPageData() {
            // vm.currentPage = 1; // 初始化api請求頁數
            const vm = this;
            const apiHost = "/exsport/live/api/get-event-info-by-page";
            // let apiParams ="?SportId="+vm.currentSportId+"&Market="+vm.currentMarketId+"&Page="+vm.currentPage+"&OddsType=2&ColorCode=30&ColorCon=200&LanguageCode="+vm.currentLanguageCode;
            const firstPageParams ="?SportId="+vm.currentSportId+"&Market=1&Page=1&OddsType=2&ColorCode=30&ColorCon=200&LanguageCode="+vm.currentLanguageCode;
            vm.$http.get(apiHost + firstPageParams).then(res => { // 使用 page=1 的參數
                if (res.data.data.PageSize) { // 取得球種總頁數
                    vm.earilyPageSize = res.data.data.PageSize;
                }
                if (res.data.data.Sports[0] && res.data.data.Sports[0].Events) {
                    vm.newListData.push(...res.data.data.Sports[0].Events);
                    console.log('同步更新資料',vm.newListData);
                    vm.earilyApi ();

                }
            });
        },
        // getTodayData () {
        //     const vm = this;
        //     const apiHost = "/exsport/live/api/get-event-info-by-page";
        //     const firstPageParams ="?SportId="+vm.currentSportId+"&Market=2&Page=1&OddsType=2&ColorCode=30&ColorCon=200&LanguageCode="+vm.currentLanguageCode;
        //     vm.$http.get(apiHost + firstPageParams).then(res => {
        //         if (res.data.data.PageSize) { // 取得球種總頁數
        //             vm.todayPageSize = res.data.data.PageSize;
        //         }
        //         if (res.data.data.Sports[0] && res.data.data.Sports[0].Events) {
        //             vm.newListData.push(...res.data.data.Sports[0].Events);
        //         }
        //         vm.todayEvents = vm.newListData;
        //         // 如果目前頁數大於1且小於等於最大頁數，調用其他已啟用page的資料
        //         if (vm.todayCurrentPage <= vm.todayPageSize && vm.todayCurrentPage > 1) {
        //             if (vm.todayCurrentPage > vm.todayPageSize) { // 如果目前頁數大於總頁數，讓調用過得頁數等於總頁數，不是的話調用過得頁數等於目前頁數
        //                 vm.todayUsedPage = vm.todayPageSize;
        //             } else {
        //                 vm.todayUsedPage = vm.todayCurrentPage;
        //             }
        //             // vm.todayCurrentPage = 1;
        //             // let pagesData = [];
        //             // let pagesSports = [];
        //             for(let i = 2; i <= vm.todayUsedPage; i++) {
        //                 let apiParams ="?SportId="+vm.currentSportId+"&Market=2&Page="+i+"&OddsType=2&ColorCode=30&ColorCon=200&LanguageCode="+vm.currentLanguageCode;
        //                 // vm.todayCurrentPage+=1;
        //                 vm.$http.get(apiHost + apiParams).then(response => {
        //                     if (response.data.data.Sports[0]) {
        //                         vm.newListData.push(...response.data.data.Sports[0].Events);
        //                     }
        //                 })
        //             }
        //         }
        //     }).then(function () {
        //         vm.getByPageData();
        //     })
        // },
        // getByPageData() {
        //     // vm.currentPage = 1; // 初始化api請求頁數
        //     const vm = this;
        //     const apiHost = "/exsport/live/api/get-event-info-by-page";
        //     // let apiParams ="?SportId="+vm.currentSportId+"&Market="+vm.currentMarketId+"&Page="+vm.currentPage+"&OddsType=2&ColorCode=30&ColorCon=200&LanguageCode="+vm.currentLanguageCode;
        //     const firstPageParams ="?SportId="+vm.currentSportId+"&Market=1&Page=1&OddsType=2&ColorCode=30&ColorCon=200&LanguageCode="+vm.currentLanguageCode;
        //     vm.$http.get(apiHost + firstPageParams).then(res => { // 使用 page=1 的參數
        //         if (res.data.data.PageSize) { // 取得球種總頁數
        //             vm.earilyPageSize = res.data.data.PageSize;
        //         }
        //         if (res.data.data.Sports[0] && res.data.data.Sports[0].Events) {
        //             vm.newListData.push(...res.data.data.Sports[0].Events);
        //             console.log('同步更新資料',vm.newListData);
        //             // 如果目前頁數大於1且小於等於最大頁數，調用其他已啟用page的資料
        //             if (vm.earilyCurrentPage <= vm.earilyPageSize && vm.earilyCurrentPage > 1) {
        //                 if (vm.earilyCurrentPage > vm.earilyPageSize) { // 如果目前頁數大於總頁數，讓調用過得頁數等於總頁數，不是的話調用過得頁數等於目前頁數
        //                     vm.earilyUsedPage = vm.earilyPageSize;
        //                 } else {
        //                     vm.earilyUsedPage = vm.earilyCurrentPage;
        //                 }
        //                 // vm.earilyCurrentPage = 1;
        //                 // let pagesData = [];
        //                 // let pagesSports = [];
        //                 for(let i = 2; i <= vm.usedPage; i++) {
        //                     let apiParams ="?SportId="+vm.currentSportId+"&Market=1&Page="+i+"&OddsType=2&ColorCode=30&ColorCon=200&LanguageCode="+vm.currentLanguageCode;
        //                     // vm.earilyCurrentPage+=1;
        //                     vm.$http.get(apiHost + apiParams).then(response => {
        //                         console.log(apiParams);
        //                         if (response.data.data.Sports[0]) {
        //                             vm.newListData.push(...response.data.data.Sports[0].Events);
        //                         }
        //                         console.log('暫存更新資料',vm.newListData);
        //                     })
        //                 }
        //             }

        //         }
        //         vm.listData = vm.newListData;
        //         // 預設選取的賽事
        //         if (vm.activeEventData && vm.activeEventData.EventId && vm.activeEventSportId === vm.currentSportId) { // 如果已有選取賽事且球種 id 吻合 更新後繼續選取
        //             vm.activeEvent (vm.activeEventData);
        //         } else { // 如果沒有選好的賽事,預設為目前球種第1場
        //             let initailEvent = vm.listData[0];
        //             vm.activeEvent (initailEvent);
        //         }
        //         console.log('vm.listData ',vm.listData );
        //         // 取聯盟資料
        //         let competitions = vm.listData.map(item => {
        //             return item.Competition.CompetitionName
        //         })
        //         let noRepeat = [];   // 處理沒重複的聯盟資料
        //         competitions.forEach(item => {  // 取得所有沒重複的聯盟資料
        //             if (noRepeat.indexOf(item) === -1) {
        //                 noRepeat.push(item);
        //             }
        //         })
        //         // let noRepeat = [...(new Set(competitions))]; // 取得所有沒重複的聯盟資料
        //         if (vm.isAllCompetition) { // 如果目前啟用全部篩選，所有篩選都啟用
        //             vm.competitionData = noRepeat.map(item => {
        //                 return {
        //                     competition: item,
        //                     active: true,
        //                 }
        //             })
        //         } else { // 如果目前非全部篩選，把新增的聯盟篩選設為啟用，不是就設為與原本相同
        //             let beforeCompetition = vm.competitionData
        //             vm.competitionData = beforeCompetition.map(item => {
        //                 if(noRepeat.indexOf(item.competition) === -1) {
        //                     return {
        //                         competition: item.competition,
        //                         active: true,
        //                     }
        //                 } else {
        //                     return {
        //                         competition: item.competition,
        //                         active: item.active,
        //                     }
        //                 }
        //             })
        //         }
        //         console.log('聯盟資料',vm.competitionData);
        //         vm.getAllDate();
        //         vm.isLoading = false;
        //         vm.autoUpdate();
        //     });
        // },
        getData() { // 取得球種在早盤或今日下的列表，如果日期是今日要多調一個滾球資料api
            const vm = this;
            vm.isLoading = true;
            vm.getReadySports();
            if (vm.autoUpdateData.hasAutoUpdate) {
                clearInterval(vm.autoUpdateData.intervalTimeId);
                vm.autoUpdateData.hasAutoUpdate = false;
                vm.autoUpdateData.currentIntervalTime = 30;
            }
            vm.newListData = []; // 更新資料初始化
            vm.todayCurrentPage = 1;
            vm.earilyCurrentPage = 1;
            vm.getLiveData();
            // vm.getByPageData();
            // if (vm.currentMarketId === 2) {
            //     vm.getLiveData(); // 當目前選擇日期為今日盤口日期調用有滾球 api 的方法
            // } else {
            //     vm.getByPageData();
            // }

        },
        getAllDate() {
            const vm = this;
            const apiHost = "/exsport/live/api/get-date-list";
            let apiParams ="?SportId="+vm.currentSportId;
            vm.$http.get(apiHost + apiParams).then(res => {
                console.log('日期資料',res.data.data);
                if (res.data.data.today  ) {
                    vm.dateData.today =  new Date(res.data.data.today).getTime() - (8 * 60 * 60 * 1000); // 用 getTime 轉不含時間的日期字串會多 8 小時 把 8 小時扣掉取得整點
                }
                vm.dateData.allDate = res.data.data.dateList.map(item => {
                    return new Date(item).getTime()- (8 * 60 * 60 * 1000);
                });
                // vm.dateData.earilyDate = vm.dateData.allDate.filter(item => {
                //     return item !==  vm.dateData.today;
                // })
                console.log('日期stamp',vm.dateData);
                if (vm.dateData.allDate && !vm.currentDateChanged) {
                    // vm.currentDate = vm.dateData.allDate[0]; // 預設選定日期為第1個日期
                    vm.todayDate = vm.dateData.today;
                    vm.currentDate = vm.dateData.allDate[0];
                    // 預設選定日期目前啟用賽事日期
                    // let  timstamp = new Date(vm.activeEventData.EventDate) - (12*60*60*1000);
                    // let dateStamp = timstamp - (new Date(timstamp).getHours()*60*60*1000) - (new Date(timstamp).getMinutes()*60*1000)
                    // vm.currentDate = dateStamp;
                }
                // else {
                //     vm.currentDate = vm.dateData.allDate[0];
                // }
                vm.isLoading = false;
            });
        },
        checkPages() { // 當捲軸拉到底時，假如目前的球種有超過1頁以上的資料就調用下一頁的api資料出來
            const vm = this;
            // 計算捲軸
            let scrollTop = document.documentElement.scrollTop|| document.body.scrollTop || 0;
            let windowHeight = 0;
            if(document.compatMode == "CSS1Compat") { // doctype 為標準模式時無法取得正確的 document.body.clientHeight
                windowHeight = document.documentElement.clientHeight;
            }else{
                windowHeight = document.body.clientHeight;
            }
            let scrollHeight = 0, bodyScrollHeight = 0, documentScrollHeight = 0;
            let bSH = 0, dSH = 0;
            if(document.body){
                bSH = document.body.scrollHeight;
            }
            if(document.documentElement){
                dSH = document.documentElement.scrollHeight;
            }
            scrollHeight = (bSH - dSH > 0) ? bSH : dSH ;
            if (scrollTop+windowHeight >= scrollHeight  ) {  // 當捲軸達底部 且目前頁數不超過總頁數
                if (vm.todayCurrentPage < vm.todayPageSize) {
                    vm.todayCurrentPage+= 1;
                    const apiHost = "/exsport/live/api/get-event-info-by-page";
                    let apiParams ="?SportId="+vm.currentSportId+"&Market=2&Page="+vm.todayCurrentPage+"&OddsType=3&ColorCode=30+&LanguageCode="+vm.currentLanguageCode;
                    console.log('apiParams',apiParams);
                    vm.$http.get(apiHost + apiParams).then(res => {
                        if (res.data.data.Sports[0]) {
                            vm.newListData.push(...res.data.data.Sports[0].Events);
                            console.log('更新後總資料',vm.newListData);
                        }
                        vm.listData =  vm.newListData;
                    });
                }
                if (vm.earilyCurrentPage < vm.earilyPageSize) {
                    vm.earilyCurrentPage+= 1;
                    const apiHost = "/exsport/live/api/get-event-info-by-page";
                    let apiParams ="?SportId="+vm.currentSportId+"&Market=1&Page="+vm.earilyCurrentPage+"&OddsType=3&ColorCode=30+&LanguageCode="+vm.currentLanguageCode;
                    vm.$http.get(apiHost + apiParams).then(res => {
                        if (res.data.data.Sports[0]) {
                            vm.newListData.push(...res.data.data.Sports[0].Events);
                            console.log('更新後總資料',vm.newListData);
                        }
                        vm.listData =  vm.newListData;
                    });
                }


                // const apiHost = "/exsport/live/api/get-event-info-by-page";
                // let apiParams ="?SportId="+vm.currentSportId+"&Market="+vm.currentMarketId+"&Page="+vm.currentPage+"&OddsType=3&ColorCode=30+&LanguageCode="+vm.currentLanguageCode;
                // vm.$http.get(apiHost + apiParams).then(res => {
                //     if (res.data.data.Sports[0]) {
                //         vm.newListData.push(...res.data.data.Sports[0].Events);
                //         console.log('更新後總資料',vm.newListData);
                //     }
                //     vm.listData =  vm.newListData;
                // });
            }

        },
        changeList(sportId) { // 依目前選擇球種更新列表 , 更新前清空 資料並把判斷分頁是否切換的參數改成 false
            const vm = this;
            vm.currentDateChanged = false;
            vm.isAllCompetition = true;
            vm.currentSportId = sportId;
            vm.listData = [];
            vm.newListData = [];
            vm.hasChangePage = false;
            vm.todayCurrentPage = 1; // 切換球種後初始化api請求頁數\
            vm.todayUsedPage = 1;
            vm.earilyCurrentPage = 1;
            vm.earilyUsedPage = 1;
            vm.getData();
        },
        activeEvent (eventItem) { // 選定賽事顯示上方比分和視訊，並調用 隊伍 icon api
            const vm = this;
            vm.activeEventSportId = vm.currentSportId;
            vm.activeEventData = eventItem;
            // console.log('eventItem',eventItem);
            // let currentData = vm.listData.find(item => {

            //     return item.EventId === vm.activeEventData.EventId;
            // });
            // vm.activeEventData = currentData;
            console.log('目前選定賽事',vm.activeEventData);
            vm.$emit('emitActiveEvent', vm.activeEventData);
        },
        changeCurrentDate (dateStamp) { // 變更日期 stamp ，且當日期為今日就改成今日id，早盤就改成早盤id
            this.currentDate = dateStamp;
            this.currentDateChanged = true;
            if (this.currentDate === this.dateData.today) {
                console.log('今日')
                this.currentMarketId = 2;
            } else {
                console.log('非今日')
                this.currentMarketId = 1;
            }
            this.dropDownActive = false;
        },
        handleDateClose () { // 關閉日期下拉
            this.dropDownActive = false;
        },
        handleCompetitionClose () { // 關閉聯盟篩選下拉
            this.competitionDropSwitch = false;
        },
        handleCompetition (competitionItem) { // 點擊聯盟篩選項目
            competitionItem.active = !competitionItem.active;
            // 只要有 1 個篩選關閉，就關閉全部篩選反之沒有關閉任何篩選就是全部
            let maxDataNum = this.competitionData.length;
            let checkActiveData = this.competitionData.filter(item => {
                return item.active === true;
            })
            console.log(maxDataNum,checkActiveData.length)
            if (maxDataNum === checkActiveData.length) {
                this.isAllCompetition = true;
            } else {
                this.isAllCompetition = false;
            }
        },
        handleAllCompetition () { // 點擊全部篩選
            this.isAllCompetition = !this.isAllCompetition;
            this.competitionData.forEach(item => {
                if (!this.isAllCompetition) {
                    item.active = false;
                } else {
                    item.active = true;
                }
            })
        },
        autoUpdate () { // 自動更新
            const vm = this;
            if (vm.autoUpdateData.hasAutoUpdate) { // 如果已存在自動更新計時器就先把它清除，並恢復秒數
                clearInterval (vm.autoUpdateData.intervalTimeId);
                vm.autoUpdateData.currentIntervalTime = 30;
            }
            vm.autoUpdateData.hasAutoUpdate = true; // 計時器啟動
            vm.autoUpdateData.intervalTimeId = setInterval(function () {
                // console.log(vm.autoUpdateData.currentIntervalTime);
                if (vm.autoUpdateData.currentIntervalTime === 0) { // 當秒數 0 時清除計時
                    clearInterval(vm.autoUpdateData.intervalTimeId);
                    vm.autoUpdateData.currentIntervalTime = 30;
                    vm.getData();
                    return;
                }
                vm.autoUpdateData.currentIntervalTime -= 1; // 每次扣1秒
            },1000)
        },
        goLeft () {
            if (this.sportTypesCurrentPage > 1) {
                this.sportTypesCurrentPage -= 1;
                this.$refs.sportSelect.style.transform = 'translateX('+(-250)*(this.sportTypesCurrentPage - 1)+'px)';
            }
        },
        goRight () {
            if (this.sportTypesPageSize > this.sportTypesCurrentPage) {
                this.sportTypesCurrentPage += 1;
                this.$refs.sportSelect.style.transform = 'translateX('+(-250)*(this.sportTypesCurrentPage - 1)+'px)';
            }
        },
        transString(val) { // 處理賽事狀態字串
            if (!val) {
                return
            }
            return val.replace('Q1', this.$t('S_FIRST_QUAR'))
            .replace('Q2', this.$t('S_SECOND_QUAR'))
            .replace('Q3', this.$t('S_THIRD_QUAR'))
            .replace('Q4', this.$t('S_FOURTH_QUAR'))
            .replace('1H', this.$t('S_FIRST_HALF'))
            .replace('2H', this.$t('S_SECOND_HALF'))
            .replace('!LIVE', this.$t('S_LIVE'))
            .replace('HT', this.$t('S_HT'))
        },


    },
    computed: {
        filterBetType() {
            return function(bet) {
                let fullHDP = bet.MarketLines.find(item => {
                // 以 全場id 及 讓球玩法id 抓出 第1筆符合 全場讓球玩法的資料
                    return item.BetTypeId === 1 && item.PeriodId === 1;
                });
                if (fullHDP) {
                    return fullHDP;
                } else {
                    // 如果目前球種沒有讓球玩法就回傳 null
                    return null;
                }
            };
        },
        // filterMarketSport() {
        //     // 依今日或早盤篩選球種選項，
        //     if (this.currentMarketId === 2 || this.currentMarketId === 3) {
        //         return this.todaySports;
        //     } else if (this.currentMarketId === 1) {
        //         return this.earlySports;
        //     }
        //     return currentSelect;
        // },
        filterList () {
            console.log(this.listData);
            const vm = this;
            let activeCompetitionNames = []; // 拿有啟用篩選的聯盟名稱
            if (vm.competitionData) {
                let activeCompetition = vm.competitionData.filter(item => {
                    return item.active === true;
                });
                activeCompetitionNames = activeCompetition.map(item => {
                    return item.competition;
                });
            }
            // 先篩選日期
            let dataByDate = vm.listData.filter(item => {
                    let usTimeStamp = new Date(item.EventDate).getTime()-(12*60*60*1000);
                    let dateStamp = usTimeStamp -  (new Date(usTimeStamp).getHours()*(60*60*1000) + new Date(usTimeStamp).getMinutes()*(60*1000)); // 取得整點00 : 00  的 timeStamp
                    return dateStamp === vm.currentDate;
            })
            console.log('dataByDate',dataByDate);
            // let currentResults = dataByDate.filter(item => {
            //     return activeCompetitionNames.indexOf(item.Competition.CompetitionName) !== -1;
            // });
            // if (vm.currentResultsPage <  Math.ceil(currentResults.length / 30)){
            //     currentResults.splice()

            // } else {
            //     return  currentResults
            // }

            return dataByDate.filter(item => {
                return activeCompetitionNames.indexOf(item.Competition.CompetitionName) !== -1;
            })

        },
    },
    created() {
        const vm = this;
        vm.getData();
        // vm.getReadySports();
    },
    directives: {
        clickoutside: { // 當點擊當前元素以外的區域，就觸發的指令
            // bind: 只調用一次，指令第一次綁定到元素時調用，用這個鉤子函數可以定義一個在綁定時執行一次的初始化動作
            bind: function (el, binding, vnode) {
                function documentHandler(e) {
                // 判斷點擊的區域是否在指令所載元素內部，是就跳出。el是指令所綁定的元素
                    if (el.contains(e.target)) { //contains方法用來判斷A是否包含B，包含返回true
                        return false;
                    }

                    if (binding.expression) { //判斷當前指令v-clickoutside有沒有寫表達式即  v-clickoutside="handleClose" 這段
                        // console.log(binding);
                        binding.value(e); //執行當前上下文methods中指定的函數handleClose (關閉下拉)即 執行 v-clickoutside 的 handleClose
                    }
                }

            // __vueClickOutside__ 是自己隨便起的，el.__vueClickOutside__ 引用了documentHandler,這樣就可以再後邊移除監聽事件
                el.__vueClickOutside__ = documentHandler;
                document.addEventListener('click', documentHandler);
            },
            unbind: function (el, binding) {
                document.removeEventListener('click', el.__vueClickOutside__)
            // 移除document的click事件監聽。
                delete el.__vueClickOutside__;
            }
        },
        scroll: {
            inserted: function (el, binding) {
                let f = function (evt) {
                    if (binding.value(evt, el)) {
                        window.removeEventListener('scroll', f)
                    }
                }
                window.addEventListener('scroll', f)
            }
        }
    }
};
</script>

