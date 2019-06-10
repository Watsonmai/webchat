import { axios } from '@/utils/request'

export function login (params) {
    return axios({
        url: 'webchat/auth/in',
        method: 'post',
        data: params
    })
}

export function add (params) {
    return axios({
        url: 'webchat/auth/add',
        method: 'post',
        data: params
    })
}


export function out (params) {
    return axios({
        url: 'webchat/auth/out',
        method: 'post',
        data: params
    })
}

