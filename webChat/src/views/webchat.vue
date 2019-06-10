<template>
    <div style="margin: 5% 25% 0 25%;">
        <el-container style="height: 700px">
            <el-aside width="300px">
                <el-container style="height: 100%">
                    <el-aside width="80px" style="background-color: #2D2B2A">
                        <h4 style="margin-top: 25px;color: #8C8C8C">WebChat</h4>
                        <ul class="icon-list">
                            <li>
                                <el-badge :is-dot="comeMsg" class="item">
                                    <a href="javascript:" @click="talk">
                                        <i class="el-icon-s-comment myIcon icon-color" style="font-size: 25px;"></i>
                                    </a>
                                </el-badge>
                            </li>
                            <li class="icon-margin">
                                <el-badge :is-dot="newFriend" class="item">
                                    <a href="javascript:" @click="friendList">
                                        <i class="el-icon-user-solid myIcon icon-color" style="font-size: 25px;"></i>
                                    </a>
                                </el-badge>
                            </li>
                            <li class="icon-margin">
                                <el-badge :is-dot="apply" class="item">
                                    <a href="javascript:" @click="optionList">
                                        <i class="el-icon-s-tools myIcon icon-color" style="font-size: 25px;"></i>
                                    </a>
                                </el-badge>
                            </li>
                        </ul>
                    </el-aside>
                    <el-container style="height: 100%">
                        <el-header style="background-color: #EBEBEB;">
                            {{listHeadText}}
                        </el-header>
                        <el-divider></el-divider>
                        <el-main class="listMain" style="background-color: #EBEBEB">
                            <!--侧边列表栏，1为聊天列表，2为好友列表，3为设置列表-->
                            <!--聊天列表-->
                            <div v-if="listType === 1">
                                <div v-for="item in talkList">
                                    <a href="javascript:" @click="chat(item)">
                                        <el-card shadow="hover" style="text-align: center" class="card-color">
                                            <span>{{item.nickName}}</span>
                                        </el-card>
                                    </a>
                                    <el-divider></el-divider>
                                </div>
                            </div>

                            <!--好友列表-->
                            <div v-if="listType === 2">
                                <el-tabs v-model="activeName" style="padding: 0 15% 0 15%">
                                    <el-tab-pane label="好友列表" name="friend">
                                        <div v-for="item in userFriendList">
                                            <a href="javascript:" @click="chat(item)">
                                                <el-card shadow="hover" style="text-align: center" class="card-color">
                                                    <span>{{item.nickName}}</span>
                                                </el-card>
                                            </a>
                                            <el-divider></el-divider>
                                        </div>
                                    </el-tab-pane>
                                    <el-tab-pane label="群聊列表" name="party">
                                        <div v-for="item in partyList">
                                            <a href="javascript:" @click="partyChat(item)">
                                                <el-card shadow="hover" style="text-align: center" class="card-color">
                                                    <span>{{item.partyName}}</span>
                                                </el-card>
                                            </a>
                                            <el-divider></el-divider>
                                        </div>
                                    </el-tab-pane>
                                </el-tabs>
                            </div>

                            <!--设置列表-->
                            <div v-if="listType === 3">
                                <a href="javascript:" @click="userInfo">
                                    <el-card shadow="hover" class="card-color">
                                        <el-row :gutter="20">
                                            <el-col :span="4">
                                                <i class="el-icon-user" style="font-size: 18px;"></i>
                                            </el-col>
                                            <el-col :span="17" style="line-height:20px"><span>个人信息</span></el-col>
                                        </el-row>
                                    </el-card>
                                </a>
                                <el-divider></el-divider>
                                <a href="javascript:" @click="addFriend">
                                    <el-card shadow="hover" class="card-color">
                                        <!--<i class="el-icon-circle-plus-outline"><span>添加好友</span></i>-->
                                        <el-row :gutter="20">
                                            <el-col :span="4">
                                                <i class="el-icon-circle-plus-outline" style="font-size: 18px;"></i>
                                            </el-col>
                                            <el-col :span="17" style="line-height:20px"><span>添加好友</span></el-col>
                                        </el-row>
                                    </el-card>
                                </a>
                                <el-divider></el-divider>
                                <a href="javascript:" @click="applyList">
                                    <el-card shadow="hover" class="card-color">
                                        <!--<i class="el-icon-circle-plus-outline"><span>添加好友</span></i>-->
                                        <el-row :gutter="20">
                                            <el-col :span="4"><i class="el-icon-warning-outline"
                                                                 style="font-size: 18px;"></i></el-col>
                                            <el-col :span="20" style="line-height:20px">
                                                <el-row>
                                                    <el-col :span="20"><span>好友申请</span></el-col>
                                                    <el-col :span="4" v-if="apply">
                                                        <el-badge :value="12"/>
                                                    </el-col>
                                                </el-row>
                                            </el-col>
                                        </el-row>
                                    </el-card>
                                </a>
                                <el-divider></el-divider>
                                <a href="javascript:" @click="addParty">
                                    <el-card shadow="hover" class="card-color">
                                        <!--<i class="el-icon-circle-plus-outline"><span>添加好友</span></i>-->
                                        <el-row :gutter="20">
                                            <el-col :span="4">
                                                <i class="el-icon-share" style="font-size: 18px;"></i>
                                            </el-col>
                                            <el-col :span="17" style="line-height:20px"><span>新建群聊</span></el-col>
                                        </el-row>
                                    </el-card>
                                </a>
                                <el-divider></el-divider>
                                <a href="javascript:" @click="logout">
                                    <el-card shadow="hover" class="card-color">
                                        <!--<i class="el-icon-circle-plus-outline"><span>添加好友</span></i>-->
                                        <el-row :gutter="20">
                                            <el-col :span="4">
                                                <i class="el-icon-close" style="font-size: 18px;"></i>
                                            </el-col>
                                            <el-col :span="17" style="line-height:20px"><span>退出登录</span></el-col>
                                        </el-row>
                                    </el-card>
                                </a>
                            </div>
                        </el-main>
                    </el-container>
                </el-container>
            </el-aside>
            <el-container style="height: 100%">
                <el-header style="background-color: #F5F5F5;">
                    {{headText}}
                </el-header>
                <el-divider></el-divider>

                <el-main style="background-color: #F5F5F5;">
                    <chat-windows v-if="mainType === 2" :withID="withID" :comeData="comeData" :chatType="chatType"></chat-windows>
                    <user-info v-if="mainType === 31" :userInfoForm="userInfoForm"></user-info>
                    <add-friend v-if="mainType === 32"></add-friend>
                    <apply-list v-if="mainType === 33" @addSuccess="addSuccess"></apply-list>
                    <add-party v-if="mainType === 34"></add-party>
                </el-main>
            </el-container>
        </el-container>
    </div>
</template>

<script>
    import {out} from '@/api/system'
    import {getUserInfo, getFriendList} from '@/api/user'
    import {getlist} from '@/api/party'
    import Vue from 'vue'

    export default {
        name: "webchat",
        data() {
            return {
                headText: '',
                listType: 2,
                listHeadText: '好友列表',
                apply: false,
                mainType: 1,
                userInfoForm: {
                    userNo: '',
                    nickName: ''
                },
                userFriendList: {},
                chatNo: '',
                withID: '',
                comeData: {},
                newFriend: false,
                comeMsg: false,
                talkList: {},
                activeName: 'friend',
                partyList:{},
                chatType:0
            }
        },
        methods: {
            talk() {
                this.listType = 1
                this.listHeadText = '聊天列表'
                this.comeMsg = false
            },
            friendList() {
                //设置UI
                this.listType = 2
                this.listHeadText = '好友列表'
                this.newFriend = false
                //请求好友列表
                getFriendList().then(res => {
                    this.userFriendList = res.data
                }).catch(e => {
                    this.$message.error(e.error)
                })
                //请求群聊列表
                getlist().then(res=>{
                    this.partyList = res.data
                }).catch(e=>{
                    this.$message.error(e.error)
                })
            },
            optionList() {
                this.listType = 3
                this.listHeadText = '设置列表'
                this.apply = false
            },
            addFriend() {
                this.headText = '添加好友'
                this.mainType = 32
            },
            applyList() {
                this.headText = '好友申请'
                this.mainType = 33
            },
            addParty(){
                this.headText = '新增群聊'
                this.mainType = 34
            },
            logout() {
                out().then(res => {

                }).catch(e => {
                    this.$message.error(e.error)
                })
                //this.$router.push('/')
            },
            userInfo() {
                this.headText = '个人信息'
                this.mainType = 31
                getUserInfo().then(res => {
                    this.userInfoForm = res.data
                }).catch(e => {
                    this.$message.error(e.error)
                })
            },
            chat(item) {
                this.chatNo = item.userNo
                this.withID = item.id
                this.mainType = 2
                this.headText = item.nickName
                console.log(item.chatType)
                if(item.chatType){
                    this.chatType = item.chatType
                }else {
                    this.chatType = 1
                }
            },
            partyChat(item){
                this.withID = item.id
                this.mainType = 2
                this.headText = item.partyName
                this.chatType = 2
            },
            subscribe() {
                var that = this
                Object.defineProperty(this.ws.getMsg, 'data', {
                    get: function () {
                        return data;
                    },
                    set: function (newValue) {
                        var inData = JSON.parse(newValue);
                        if (inData['type'] === 1) {
                            if (that.listType !== 1) {
                                that.comeMsg = true
                            }
                            if (JSON.parse(localStorage.getItem(inData['sendID']))) {
                                var ls = JSON.parse(localStorage.getItem(inData['sendID']))
                                ls.push(inData)
                                localStorage.setItem(inData['sendID'], JSON.stringify(ls))
                            } else {
                                var ls = []
                                ls.push(inData)
                                localStorage.setItem(inData['sendID'], JSON.stringify(ls))
                            }
                            that.comeData = inData
                            //设置聊天列表
                            that.setTalkList(inData['sendID'], inData['nickName'], inData['chatType'])
                        } else if (inData['type'] === 2) {
                            if (that.listType !== 3) {
                                that.apply = true
                            }
                        } else if (inData['type'] === 3) {
                            var talking = false
                            for (var i = 0; i < that.talkList.length; i++) {
                                if (that.talkList[i]['id'] === inData['sendID']) {
                                    talking = true
                                }
                            }
                            if (!talking) {
                                var newMsg = {
                                    'id': inData['sendID'],
                                    'nickName': inData['nickName'],
                                    'read': true
                                }
                                that.talkList.push(newMsg)
                                localStorage.setItem('talkList', JSON.stringify(that.talkList))
                            }
                        }
                    }
                })
            },
            setTalkList(sendID, nickName,chatType) {
                this.talkList = JSON.parse(localStorage.getItem('talkList'))
                var talking = false
                console.log(this.talkList)
                for (var i = 0; i < this.talkList.length; i++) {
                    if (this.talkList[i]['id'] === sendID) {
                        this.talkList[i]['read'] = false
                        talking = true
                    }
                }
                if (talking === false) {
                    var newMsg = {
                        'id': sendID,
                        'nickName': nickName,
                        'chatType': chatType,
                        'read': false
                    }
                    this.talkList.push(newMsg)
                }
                localStorage.setItem('talkList', JSON.stringify(this.talkList))
            },
            pageClose() {
                var that = this
                window.onunload = function () {
                    that.ws.websocketsend({type: -1, userid: Vue.ls.get('userID')})
                }
            },
            addSuccess(value) {
                if (value == 1) {
                    this.newFriend = true
                }
            },

        },
        created() {
            this.ws.initWebSocket(Vue.ls.get('token'), Vue.ls.get('userID'))
            if (JSON.parse(localStorage.getItem('talkList'))) {
                this.talkList = JSON.parse(localStorage.getItem('talkList'))
            } else {
                var ls = []
                localStorage.setItem('talkList', JSON.stringify(ls))
            }
        },
        mounted() {
            this.subscribe()
            this.pageClose()
        }
    }
</script>

<style>

    .card-color {
        background-color: #EBEBEB;
    }

    .card-color:hover {
        background-color: #F5F5F5;
    }

    .el-aside {
        background-color: #D3DCE6;
        color: #333;
        text-align: center;
        line-height: 0;
    }

    .el-main {
        padding: 0 0 0 0;
        line-height: 0;
        text-align: unset;
    }

    .icon-list li:hover i {
        color: #5cb6ff;
        cursor: pointer;
    }

    .icon-list li:active {
        color: #5cb6ff;
    }

    .icon-list li:visited {
        color: #5cb6ff;
    }

    .icon-list {
        padding: 0 0 0 0;
        margin: 50% 0 0 0;
    }

    .icon-color {
        color: #8C8C8C;
    }

    .icon-margin {
        margin: 25px 0 0 0;
    }

    .el-divider--horizontal {
        display: block;
        height: 1px;
        width: 100%;
        margin: 0 0 0 0;
    }

    .friend-list {
        padding: 0 0 0 0;
    }

    a {
        text-decoration: none
    }
</style>
