import Vue from 'vue'
import App from './App.vue'
import { VueAxios } from '@/utils/request' // axios 不建议引入到 Vue 原型链上
import './plugins/element.js'
import router from './router'
import Storage from 'vue-ls';
import addFriend from './components/addFriend'
import userInfo from './components/userInfo'
import chatWindows from './components/chatWindows'
import applyList from './components/applyList'
import addParty from './components/addParty'
import * as ws from './plugins/websock'


Vue.config.productionTip = false
Vue.use(VueAxios, {})
Vue.use(Storage);
Vue.component('addFriend',addFriend)
Vue.component('userInfo',userInfo)
Vue.component('chatWindows',chatWindows)
Vue.component('applyList',applyList)
Vue.component('addParty',addParty)
Vue.prototype.ws = ws

// Vue.use(new VueSocketio({
//   debug: true,
//   connection: 'http://112.74.50.162:8081',
//   vuex: {
//     actionPrefix: 'SOCKET_',
//     mutationPrefix: 'SOCKET_'
//   },
//   options: { path: "/echo" } //Optional options
// }))
// Vue.use(VueSocketIO, 'http://112.74.50.162:8081');
// Vue.use(VueSocketio, socketio('http://112.74.50.162:8081'), store);
new Vue({
  router,
  render: h => h(App)
}).$mount('#app')
