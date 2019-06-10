import Vue from 'vue'
import axios from 'axios'
import qs from 'qs'
import {VueAxios} from './axios'
import {ACCESS_TOKEN, ADMINID, SHOPNO} from '@/store/mutation-types'
import {out} from '@/api/system'

// 创建 axios 实例
// const service = axios.create({
//   baseURL: '/api', // api base_url
//   timeout: 6000 // 请求超时时间
// })

const service = axios.create({
  timeout: 6000 // 请求超时时间
})

const err = (error) => {
  if (error.response) {
    const data = error.response.data
    const token = Vue.ls.get('Access-Token')
    if (error.response.status === 403) {
      // notification.error({ message: 'Forbidden', description: data.message })
      console.log('error: '+data.message)
    }
    if (error.response.status === 401) {
      // notification.error({ message: 'Unauthorized', description: 'Authorization verification failed' })
      console.log('error: Authorization verification failed')
      Vue.ls.set('status',0)
      window.location.href="/"
    }
  }
  return Promise.reject(error)
}

// request interceptor
service.interceptors.request.use(config => {
  const token = Vue.ls.get('token')
  config.headers['Content-Type'] = 'application/x-www-form-urlencoded'
  if (token) {
    // 让每个请求携带自定义 token 请根据实际情况自行修改
    config.headers[ 'Access-userID' ] = Vue.ls.get('userID')
    config.headers[ 'Access-Token' ] = token
  }
  if (config.method === 'post') {
    config.data = qs.stringify({
      ...config.data
    })

    if (config.data[0] === '&') {
      config.data = config.data.substring(1)
    }
  }
  return config
}, err)

// response interceptor
service.interceptors.response.use((response) => {
  let res = response.data
  if (res.code !== 1) {
    return Promise.reject(response.data)
  } else {
    return response.data
  }
}, err)

const installer = {
  vm: {},
  install (Vue, router = {}) {
    Vue.use(VueAxios, router, service)
  }
}

export {
  installer as VueAxios,
  service as axios
}
