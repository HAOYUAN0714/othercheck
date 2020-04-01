import VueSocketIO from 'vue-socket.io'
import SocketIO from 'socket.io-client'

const socket = (new VueSocketIO({
    debug: true,
    connection: SocketIO('ws://35.194.149.175', {
        query: {
            gameType: "chat"
        },
        timeout: 20000,
        path: "/gblive/fxLB",
        transports: ['websocket']
    }),

}))

export default socket
