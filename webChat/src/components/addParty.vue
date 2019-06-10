<template>
    <div>
        <el-form  label-width="80px" class="form-css" >

            <el-form-item label="群聊名称: " style="width: 40%;margin: 0 30% 0 30%">
                <el-input v-model="partForm.name"></el-input>
            </el-form-item>
            <el-transfer v-model="partForm.users" :data="friendList" style="margin: 5% 2% 5% 2%" :titles="titleList"></el-transfer>
            <div style="width: 100%;text-align: center">
                <el-button style="width: 35%" @click="sendParty">确定</el-button>
            </div>
        </el-form>

    </div>
</template>

<script>
    import {getFriendList} from '@/api/user'
    import {addParty} from '@/api/party'
    import Vue from 'vue'
    export default {
        name: "addParty",
        data(){
            return{
                friendList:[],
                partForm:{
                    name:'',
                    users:[]
                },
                titleList:['好友列表','群组成员']
            }
        },
        methods:{
            sendParty(){
                if(this.partForm.name === ''){
                    this.$message.warning('群聊名称不能为空！')
                    return
                }

                if(this.partForm.users.length === 0){
                    this.$message.warning('群聊成员不能为空！')
                    return
                }
                //
                addParty(this.partForm).then(res => {
                    this.$message.success("新增群聊成功")
                }).catch(e =>{
                    this.$message.error(e.error)
                })

                console.log(this.partForm.users)
            }
        },
        mounted() {
            getFriendList().then(res => {
                this.friendList = res.data
                var user = {
                    key:Vue.ls.get('userID'),
                    label:Vue.ls.get('nickName'),
                    disabled:true
                }
                this.friendList.push(user)
                this.partForm.users = [Vue.ls.get('userID')]
            }).catch(e => {
                this.$message.error(e.error)
            })
        }
    }
</script>

<style>
    .form-css{
        text-align: center;
        margin-top: 25px;
    }
 .el-transfer .el-transfer-panel__body{
     text-align: left;
 }
</style>
