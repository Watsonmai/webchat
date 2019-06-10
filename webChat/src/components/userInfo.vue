<template>
    <div>
        <div style="margin: 25px 30% 0 30%">
            <el-form :model="editForm" :rules="rules" ref="editForm" label-width="100px" >
                <el-form-item label="用户ID：">
                    <span>{{userInfoForm.userNo}}</span>
                </el-form-item>
                <el-form-item label="用户昵称：" prop="nickName">
                    <el-input v-model="userInfoForm.nickName" :disabled="inputDisabled" style="width: 100px;margin-right: 15px"></el-input>
                    <el-button type="primary" icon="el-icon-edit" circle size="small" @click="openEdit" v-if="inputDisabled === true"></el-button>
                    <el-button type="success" icon="el-icon-check" circle size="small" @click="enterEdit('editForm')" v-if="inputDisabled === false"></el-button>
                </el-form-item>
            </el-form>
        </div>
    </div>
</template>

<script>
    import {edit} from '@/api/user'
    export default {
        name: "userInfo",
        data(){
            return {
                editForm:{
                    userNo:'',
                    nickName:'',
                },
                rules:{
                    nickName: [
                        {required: true, message: '昵称不能为空'},
                    ],
                },
                inputDisabled:true
            }
        },
        props:["userInfoForm"],
        watch:{
            userInfoForm(value){
                this.editForm = value
            },
        },
        methods:{
            openEdit(){
                this.inputDisabled = false
            },
            enterEdit(editForm){
                //edit(this.editForm)
                this.$refs[editForm].validate((valid) => {
                    if (valid) {
                        edit(this.editForm).then(res=>{
                            this.$message.success('修改成功')
                            this.inputDisabled = true
                        }).catch(e=>{
                            this.$message.error(e.msg)
                        })
                    } else {
                        return false;
                    }
                });
            }
        }
    }
</script>

<style scoped>

</style>
