<template>
    <div>
        <div style="text-align: center;margin-top: 5%;">
            <el-form ref="addForm" :model="addForm" :inline="true">
                <el-form-item  prop="id" :rules="[{ type: 'number', message: 'ID必须为数字值'}]">
                    <el-input type="id" v-model.number="addForm.id" placeholder="查询ID" style="width: 500px;"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" icon="el-icon-search" plain @click="find">查找</el-button>
                </el-form-item>
            </el-form>
        </div>
        <div v-if="enterFind === true" style="width: 50%;margin:50px 25% 25% 25%">
            <el-card>
                <p>用户ID：{{userInfoForm.userNo}}</p>
                <br>
                <p>用户昵称：{{userInfoForm.nickName}}</p>
                <el-button style="width: 100%" @click="sendApply(userInfoForm.userNo)">添加好友</el-button>
            </el-card>
        </div>
    </div>
</template>

<script>
    import {findUser,sendApply} from '@/api/user'
    export default {
        name: "addFriend",
        data() {
            return {
                addForm: {
                    id: ''
                },
                userInfoForm:{
                    userNo:'',
                    nickName:''
                },
                enterFind:false
            }
        },
        methods:{
            find(){
                if(this.addForm.id === ''){
                    this.$message.error('查询的ID不能为空！')
                }else {
                    findUser(this.addForm).then( res=>{
                        this.userInfoForm = res.data
                        this.enterFind = true
                    }).catch(e=>{
                        this.$message.error(e.msg)
                    })
                }
            },
            sendApply(userNo){
                sendApply({applyNo:userNo}).then(res => {
                    this.$message.success("请求已发出，请耐心等待。")
                }).catch(e=>{
                    this.$message.error(e.error)
                })
            }
        }
    }
</script>

<style scoped>

</style>
