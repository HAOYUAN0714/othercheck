import moment from 'moment'
import oddsMixin from './oddsMixin'
import parseMixin from './parseMixin'
export default {
    mixins: [oddsMixin, parseMixin],
    methods: {
        commonToFixed(num) {
            return parseFloat(Number(num))
                .toFixed(3)
                .slice(0, -1);
        },
        commonToCurrency(num) {
            var parts = num.toString().split(".");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return parts.join(".");
        },

    }
}
