import moment from 'moment'
export default {
    methods: {
        commonGetFullDate(date) {
            const fullDt = this.commonParseDate(date);
            return `${fullDt.year}/${fullDt.date} ${fullDt.time}`
        },
        commonParseDate(date) {
            var d = new Date(date);
            var year = moment(d)
                .utcOffset(-4)
                .format("YYYY");
            var date = moment(d)
                .utcOffset(-4)
                .format("MM/DD");
            var time = moment(d)
                .utcOffset(-4)
                .format("HH:mm");
            return {
                year: year,
                date: date,
                time: time
            }
        },
        commonParseSpecifiers(oddsSet) {
            if (
                oddsSet.BetTypeId === 1 ||
                oddsSet.BetTypeId === 3 ||
                oddsSet.BetTypeId === 4
            ) {
                if (
                    oddsSet.BetTypeSelectionId === 1 ||
                    oddsSet.BetTypeSelectionId === 5 ||
                    oddsSet.BetTypeSelectionId === 8
                ) {
                    return oddsSet.HomeTeam;
                }
                if (
                    oddsSet.BetTypeSelectionId === 2 ||
                    oddsSet.BetTypeSelectionId === 6 ||
                    oddsSet.BetTypeSelectionId === 9
                ) {
                    return oddsSet.AwayTeam;
                }
                return this.$t("TEXT_DRAW");
            }
            if (oddsSet.BetTypeId == 81) {
                const score = oddsSet.Specifiers.split("=")[1];
                return `${oddsSet.SelectionName}先得${score}分`;
            }
            if (oddsSet.BetTypeId === 11) {
                return oddsSet.SelectionName;
            }
            if (oddsSet.BetTypeId === 35) {
                const num = (oddsSet.Specifiers || "").split("=")[1];
                const [scoreX, scoreY] = num.split(":");
                return oddsSet.SelectionName.replace("{x-y}", scoreX - scoreY).replace(
                    "{y-x}",
                    `${scoreY - scoreX > 0 ? "+" : ""}${scoreY - scoreX}`
                );
            }
            const specifiers = oddsSet.Specifiers;
            if (!specifiers || specifiers === "") {
                return oddsSet.SelectionName;
            }
            let selectionName = oddsSet.SelectionName;
            const regex = /[^&=?]+=[^&#]*/g;
            const target = specifiers.match(regex);
            if (target) {
                target.forEach((item, index) => {
                    const [key, value] = item.split("=");
                    selectionName = selectionName.replace(`{${key}}`, value);
                });
            }
            return selectionName;
        },
        commonParseBetTypeName(oddsSet) {
            const specifiers = oddsSet.Specifiers;
            if (!specifiers || specifiers === "") {
                return oddsSet.BetTypeName;
            }
            if (oddsSet.BetTypeId === 81) {
                return `${oddsSet.BetTypeName}`.replace("{pointnr}", "几");
            }
            let betTypeName = "";
            const regex = /[^&=?]+=[^&#]*/g;
            const target = specifiers.match(regex);
            if (target) {
                target.forEach((item, index) => {
                    const [key, value] = item.split("=");
                    if (index === 0) {
                        betTypeName = oddsSet.BetTypeName.replace(`{${key}}`, value);
                    } else {
                        betTypeName = betTypeName.replace(`{${key}}`, value);
                    }
                });
            }
            return betTypeName;
        },
    }
}
