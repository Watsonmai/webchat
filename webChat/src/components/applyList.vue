<template>
    <div style="width: 100%;height: 100%">
        <ul style="padding: 0 10% 0 10%;height: 100%">
            <li style="list-style:none;height:15%;margin-top: 25px" v-for="item in applyList">
                <el-card style="width: 100%;height: 100%;line-height:25px;padding: 5px">
                    <el-row>
                        <el-col :span="12">
                            用户ID：{{item.userNo}}
                        </el-col>
                        <el-col :span="12">
                            用户昵称：{{item.nickName}}
                        </el-col>
                        <br>
                        <el-col :span="24">
                            <el-button style="width: 100%" @click="handleApply(item.id)">同意</el-button>
                        </el-col>
                    </el-row>
                </el-card>
            </li>
        </ul>
    </div>
</template>

<script>
    import {checkApply ,handleApply} from '@/api/user'
    export default {
        name: "applyList",
        data(){
            return{
                applyList:[]
            }
        },
        methods:{
            getApplyList(){
                checkApply().then(res=>{
                    this.applyList = res.data
                }).catch(e=>{
                    this.$message.error(e.error)
                })
            },
            handleApply(applyID){
                //this.$emit("addSuccess",'1')
                handleApply({applyID:applyID}).then(res=>{
                    this.$message.success("添加成功！")
                    this.$emit("addSuccess",'1')
                }).catch(e=>{
                    this.$message.error(e.error)
                })
            }
        },
        mounted() {
            this.getApplyList()
        }
    }
</script>

<style scoped>

</style>
