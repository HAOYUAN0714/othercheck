import VueCookie from 'vue-cookie'
const regex = /[^&=?]+=[^&#]*/g
const url = (location.href)
const target = url.match(regex)
const tran = {
    hall: 'abs-hall',
    token: 'abs-token',
    'LanguageCode': 'abs-lang',
    'memberCode': 'abs-mem'
}

if (target) {
    target.forEach(val => {
        const [key, value] = val.split('=')
        if (tran[key]) {
            VueCookie.set(tran[key], value)
            history.replaceState(null, null, '/exsport/')
            //http://localhost:8881/#/exsport/?hall=500000&token=96E443AABC3CE251E646D056C07CEC1065462E421825BC13BA3F11CD0EE35DCAEE8213555D7D3041A902196FB44840C4&LanguageCode=CHS&member_code=csc20973#/yabo
        }
    })
} else {}
