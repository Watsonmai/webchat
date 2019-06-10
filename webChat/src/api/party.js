import { axios } from '@/utils/request'

export function getlist (params) {
    return axios({
        url: 'webchat/party/getlist',
        method: 'post',
        data: params
    })
}

export function addParty (params) {
    return axios({
        url: 'webchat/party/add',
        method: 'post',
        data: params
    })
}
