import { axios } from '@/utils/request'

export function getUserInfo () {
    return axios({
        url: 'webchat/user/getuserinfo',
        method: 'get'
    })
}

export function edit (params) {
    return axios({
        url: 'webchat/user/edit',
        method: 'post',
        data: params
    })
}

export function findUser (params) {
    return axios({
        url: 'webchat/user/finduser',
        method: 'post',
        data: params
    })
}

export function getFriendList (params) {
    return axios({
        url: 'webchat/chat/getfriendlist',
        method: 'post',
        data: params
    })
}

export function sendApply (params) {
    return axios({
        url: 'webchat/user/sendapply',
        method: 'post',
        data: params
    })
}
export function checkApply () {
    return axios({
        url: 'webchat/user/checkapply',
        method: 'get'
    })
}

export function handleApply (params) {
    return axios({
        url: 'webchat/user/handleapply',
        method: 'post',
        data: params
    })
}
