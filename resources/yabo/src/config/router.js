import Vue from "vue";
import Router from "vue-router";
import yabo from '../router/yabo/yabo.vue'
import betHistory from '../router/singlePage/betHistory.vue'
import settledBet from '../router/singlePage/settledBet.vue'
import matchResult from '../router/singlePage/matchResult.vue'
import tutorials from '../router/singlePage/tutorials.vue'
import marquee from '../router/singlePage/marquee.vue'
Vue.use(Router);

let router;
router = new Router({
    routes: [{
            path: "/",
            name: "",
            component: yabo,
            meta: {
                single: false
            }
        }, {
            path: "/betHistory",
            name: "betHistory",
            component: betHistory,
            meta: {
                single: true
            }
        },

        {
            path: "/settledBet",
            name: "settledBet",
            component: settledBet,
            meta: {
                single: true
            }
        }, {
            path: "/matchResult",
            name: "matchResult",
            component: matchResult,
            meta: {
                single: true
            }
        }, {
            path: "/tutorials",
            name: "tutorials",
            component: tutorials,
            meta: {
                single: true
            }
        }, {
            path: "/marquee",
            name: "marquee",
            component: marquee,
            meta: {
                single: true
            }
        },
    ]

});
export default router;
