import VueCookie from 'vue-cookie';
var common = {
    lang: VueCookie.get('abs-lang') || 'CHS',
    user: "",
    hint: {
        flag: false,
        message: '',
        type: 'success'
    },
    loading: [], //以array，變成pool的概念
    availableBalance: 0,
    outstandingBalance: 0,
    qrCode: "",
    announcements: []
}
const status = {
    Market: 2, //早盤，今日，滾球
    liveSports: [], //這是共用的滾球中清單
    chatroom: {}, //這是聊天室
    selectedPlay: {
        Events: []
    }, //選擇的球版玩法
    selectedLivePlay: {
        Events: []
    }, //現在選擇的球版的滾球資料
    selectedPlayList: [], //選擇的球版玩法清單
    selectedPlayListIds: [], //選擇球版id清單
    selectedOddsType: 2, //盤口預設為香港
    selectedDate: "0", //選擇日期
    selectedSport: 1, //選擇球種
    isCombo: false //過關模式
}
const oddsTable = {
    selectedBetType: 1, //選擇玩法
    morePlayEvent: {}, //更多玩法
    favoriteEvents: {} //關注賽事，依照球種
}
const navList = { //賽事列表
    // 1: {
    //     //今日
    // },
    // 2: {
    //     //早盤
    // },
    // 3: {
    //     //連串過關
    // }
}
const wager = {
    wager: {}, //單一投注
    wagerList: [], //串關投注
    comboBetSetting: {
        //串關投注訊息
    },
    betPlaced: false, //成功下注
    placingBet: false, //左側選單觀測是否要進入下注頁面
    gettinBetInfo: false, //是否正在request bet info
}
const betList = {
    unsettledBetList: [],
    settledBetList: [],
}
export default {
    common,
    status,
    wager,
    navList,
    oddsTable,
    betList
};
