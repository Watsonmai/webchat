<template>
    <div style="height: 100%;width: 100%">
        <el-container style="height: 100%">
            <el-main id="chat_windows" style="background-color: #F5F5F5;">
                <div  style="width: 100%;height: 100%;padding-top: 10px">
                    <ul>
                        <li style="list-style:none;" v-for="item in displayForm">
                            <el-card class="user-car" v-if="item.userType === 1">
                                <span style="width: 100%;height:auto;word-wrap:break-word;line-height: 25px">{{item.text}}</span>
                            </el-card>
                            <el-card class="other-car" v-if="item.userType === 0">
                                <span style="width: 100%;height:auto;word-wrap:break-word;line-height: 25px">{{item.text}}</span>
                            </el-card>
                        </li>
                    </ul>
                </div>
            </el-main>

            <el-footer style="background-color: #FFFFFF;height: 25%;padding: 15px 15px 15px 0;line-height: 0;">
                <el-input type="textarea" v-model="chatForm.text" style="width: 100%;height: 75%;padding: 0 0 0 0" resize="none" ref="inputVal" @keyup.enter.native="sendText">

                </el-input>
                <el-row style="height: auto;margin-top: 1%">
                    <el-col :span="2" :offset="22">
                        <el-button style="width: 100%;padding: 8px 5px 8px 5px" @click="sendText">发送</el-button>
                    </el-col>
                </el-row>
            </el-footer>
        </el-container>
    </div>
</template>

<script>
    import Vue from 'vue'
    export default {
        name: "chatWindows",
        data(){
            return{
                chatForm:{
                    type:1,
                    fromID:Vue.ls.get('userID'),
                    targetID:'',
                    text:''
                },
                count:3,
                displayForm:[],
                recordList:[],
                nickName:''
            }
        },
        methods:{
            sendText(){
                this.ws.websocketsend(this.chatForm)
                this.recordList.push({userType:1,nickName:this.nickName,text:this.chatForm.text})
                this.chatForm.text = ''
                this.$refs.inputVal.focus()
                //console.log(this.chatForm.type)
            },
        },
        props:["withID","comeData","chatType"],
        watch:{
            withID:{
                immediate: true, // 很重要！！！
                    handler (val) {
                    this.withID = !val ? {} : val
                        this.chatForm.targetID = this.withID
                        if(localStorage.getItem(this.chatForm.targetID)){
                            this.recordList =  JSON.parse(localStorage.getItem(this.chatForm.targetID))
                        }else {
                            this.recordList = []
                        }
                },
            },
            comeData:{
                immediate: true, // 很重要！！！
                handler (val) {
                    this.nickName = val.nickName
                },
            },
            recordList(value){
                //console.log(value)
                if(value != null){
                    localStorage.setItem(this.chatForm.targetID,JSON.stringify(this.recordList))
                    this.displayForm = this.recordList
                    console.log(this.displayForm)
                }
            },
            chatType:{
                immediate: true, // 很重要！！！
                handler (val) {
                    this.chatForm.type = !val ? {} : val
                },
            },

        },
        updated:function(){
            this.$nextTick(function(){
                var div = document.getElementById('chat_windows');
                div.scrollTop = div.scrollHeight
            })
        },
        created() {
            //localStorage.removeItem(this.chatForm.targetID);
            if(localStorage.getItem(this.chatForm.targetID)){
                this.recordList =  JSON.parse(localStorage.getItem(this.chatForm.targetID))
            }else {
                this.recordList = []
            }
        }
    }
</script>

<style>
    .el-textarea__inner{
        padding: 0 0 0 0;
        margin: 0 15px 0 15px;
        width: 100%;
        height: 100%;
        border:0;
        font-size:25px;
    }
    .user-car{
        width: 45%;
        height: auto;
        background-color: aqua;
        margin: 0 0 15px 53% ;
    }
    .other-car{
        width: 45%;
        height: auto;
        background-color: #ff8670;
        margin: 0 53% 15px 10px ;
    }

</style>
