//語系mapping
const LanguageCode = {
    'en': 'ENG',
    'tw': 'CHT',
    'cn': 'CHS'
}
const betStatusConfig = {
    1: {
        id: 1,
        textKey: "TEXT_AWAIT"
    },
    2: {
        id: 2,
        textKey: "TEXT_CONFIRMED"
    },
    3: {
        id: 3,
        textKey: "TEXT_REJECT"
    },
}
const OddsTypeConfig = {
    1: {
        id: 1,
        textKey: "TEXT_MY_ODD"
    },
    2: {
        id: 2,
        textKey: "TEXT_HK_ODD"
    },
    3: {
        id: 3,
        textKey: "TEXT_EUROPE_ODD"
    },
    4: {
        id: 4,
        textKey: "TEXT_IN_ODD"
    },
}
const oddsDropArr = {
    // 1: {
    //     id: 1,
    //     textKey: "TEXT_MY_ODD"
    // },
    2: {
        id: 2,
        textKey: "TEXT_HK_ODD"
    },
    3: {
        id: 3,
        textKey: "TEXT_EUROPE_ODD"
    },
    // 4: {
    //     id: 4,
    //     textKey: "TEXT_IN_ODD"
    // },
}
const LangList = [
    // {
    //     name: "繁體中文",
    //     key: "tw"
    // },
    {
        name: "简体中文",
        key: "CHS"
    },
    {
        name: "English",
        key: "ENG"
    },
    // {
    //     name: "Tiếng việt nam",
    //     key: "VN"
    // }
]

//玩法id
const BetTypeId = {
    1: {
        id: 1,
        textKey: "TEXT_HANDICAP",
        typeName: "Handicap",
    },
    2: {
        id: 2,
        textKey: "TEXT_OVERUNDER",
        typeName: "OverUnder"
    },
    3: {
        id: 3,
        textKey: "TEXT_1X2",
        typeName: "Solowin"
    },
    5: {
        id: 5,
        textKey: "TEXT_ODDEVEN",
        typeName: "OddEven"
    },
    6: {
        id: 6,
        textKey: "TEXT_CORRECT_SCORE",
        typeName: "Bodan"
    },
    7: {
        id: 7,
        textKey: "TEXT_TOTAL",
        typeName: "TotalScores"
    },
    9: {
        id: 9,
        textKey: "TEXT_HALF_ALL",
        typeName: "HalfAll"
    },
    10: {
        id: 10,
        textKey: "TEXT_FIRST_TO_SCORE",
        typeName: "FirstScore"
    },
    11: {
        id: 11,
        textKey: "TEXT_CHAMPION",
        typeName: "Champion"
    },
    62: {
        id: 62,
        textKey: "TEXT_MULTISCORES",
        typeName: "Multiscores"
    }
}
const orderList = [{
    id: 1,
    textKey: "TEXT_ORDER_BY_TIME"
}, {
    id: 2,
    textKey: "TEXT_ORDER_BY_LEAGUE"
}, ]


//球種id
const sportList = {
    0: {
        id: 0,
        iconKey: "Sports",
        textKey: "TEXT_ALL_SPORTS"
    },
    1: {
        id: 1,
        iconKey: "Soccer",
        textKey: "TEXT_SOCCER",
        betTypes: [1, 3, 5, 6, 7, 9, 11]
    },
    2: {
        id: 2,
        iconKey: "Basketball",
        textKey: "TEXT_BASKETBALL",
        betTypes: [1, 11]
    },
    3: {
        id: 3,
        iconKey: "Tennis",
        textKey: "TEXT_TENNIS",
        betTypes: [1, 11]
    },
    5: {
        id: 5,
        iconKey: "AsianSnooker",
        textKey: "TEXT_ASIAN_SNOOKER",
        betTypes: [1, 11]
    },
    6: {
        id: 6,
        iconKey: "Athletics",
        textKey: "TEXT_ATHELETICS",
        betTypes: [1, 11]
    },

    7: {
        id: 7,
        iconKey: "Badminton",
        textKey: "TEXT_BADMINTON",
        betTypes: [1, 11]
    },
    8: {
        id: 8,
        iconKey: "Baseball",
        textKey: "TEXT_BASEBALL",
        betTypes: [1, 11]
    },
    11: {
        id: 11,
        iconKey: "Boxing",
        textKey: "TEXT_BOXING",
        betTypes: [1, 11]
    },
    13: {
        id: 13,
        iconKey: "Cricket",
        textKey: "TEXT_CRICKET",
        betTypes: [1, 11]
    },

    15: {
        id: 15,
        iconKey: "Dart",
        textKey: "TEXT_DART",
        betTypes: [1, 11]
    },
    18: {
        id: 18,
        iconKey: "GrassHockey",
        textKey: "TEXT_GRASSHOCKEY",
        betTypes: [1, 11]
    },

    19: {
        id: 19,
        iconKey: "Amfb",
        textKey: "TEXT_AMFB",
        betTypes: [1, 11]
    },
    21: {
        id: 21,
        iconKey: "Golf",
        textKey: "TEXT_GOLF",
        betTypes: [1, 11]
    },
    23: {
        id: 23,
        iconKey: "Handball",
        textKey: "TEXT_HANDBALL",
        betTypes: [1, 11]
    },
    25: {
        id: 25,
        iconKey: "Hockey",
        textKey: "TEXT_HOCKEY",
        betTypes: [1, 11]
    },
    28: {
        id: 28,
        iconKey: "Racecar",
        textKey: "TEXT_RACECAR",
        betTypes: [1, 11]
    },
    29: {
        id: 29,
        iconKey: "Racecar",
        textKey: "TEXT_RACECAR_SPORT",
        betTypes: [1, 11]
    },
    31: {
        id: 31,
        iconKey: "Football",
        textKey: "TEXT_FOOTBALL",
        betTypes: [1, 11]
    },
    32: {
        id: 32,
        iconKey: "Boat",
        textKey: "TEXT_BOAT",
        betTypes: [1, 11]
    },
    34: {
        id: 34,
        iconKey: "Snooker",
        textKey: "TEXT_SNOOKER",
        betTypes: [1, 11]
    },
    39: {
        id: 39,
        iconKey: "VirtualSoccer",
        textKey: "TEXT_VIRTUAL_SOCCER",
        betTypes: [1, 11]
    },
    40: {
        id: 40,
        iconKey: "Volleyball",
        textKey: "TEXT_VOLLEYBALL",
        betTypes: [1, 11]
    },
    41: {
        id: 41,
        iconKey: "WaterBall",
        textKey: "TEXT_WATERBALL",
        betTypes: [1, 11]
    },
    43: {
        id: 43,
        iconKey: "VirtualBasketball",
        textKey: "TEXT_VIRTUAL_BASKETBALL",
        betTypes: [1, 11]
    },
    44: {
        id: 44,
        iconKey: "VirtualWorldCup",
        textKey: "TEXT_VIRTUAL_WORLD_CUP",
        betTypes: [1, 11]
    },
    45: {
        id: 45,
        iconKey: "Entertainment",
        textKey: "TEXT_ENTERTAINMENT",
        betTypes: [1, 11]
    },
    46: {
        id: 46,
        iconKey: "VirtualNationalCup",
        textKey: "TEXT_VIRTUAL_NATIONAL_CUP",
        betTypes: [1, 11]
    },
    98: {
        id: 98,
        iconKey: "Sports",
        textKey: "TEXT_ALL_SPORTS"
    },
}
const EventGroupTypeID = {
    //不屬於任一賽事組別
    0: {
        id: 0,
        textKey: "TEXT_NOT_BELONG_ANY_TYPE"
    },
    //主要
    1: {
        id: 1,
        textKey: "TEXT_MAJOR"
    },
    //角球
    2: {
        id: 2,
        textKey: "TEXT_CORNER"
    },
    //普通
    3: {
        id: 3,
        textKey: "TEXT_NORMAL"
    },
    //第一節
    4: {
        id: 4,
        textKey: "TEXT_FIRST_PERIOD"
    },
    //第二節
    5: {
        id: 5,
        textKey: "TEXT_SECOND_PERIOD"
    },
    //第三節
    6: {
        id: 6,
        textKey: "TEXT_THIRD_PERIOD"
    },
    //第四節
    7: {
        id: 7,
        textKey: "TEXT_FORTH_PERIOD"
    }
}
const periodId = {
    1: {
        id: 1,
        textKey: "TEXT_FULL_TIME"
    },
    2: {
        id: 2,
        textKey: "TEXT_HALF_TIME"
    },
    3: {
        id: 3,
        textKey: "TEXT_2ND_HALF"
    },
}
export default {
    LanguageCode,
    EventGroupTypeID,
    sportList,
    BetTypeId,
    LangList,
    OddsTypeConfig,
    orderList,
    oddsDropArr,
    betStatusConfig,
    periodId
}
