<template>
    <div>
        <div style="margin: 10% 35% 15% 35%">
            <div style="text-align: center">
                <h2>WebChat</h2>
            </div>
            <el-card class="box-card">
                <div slot="header">
                    <el-tabs v-model="tabsSelect" @tab-click="handleClick">
                        <el-tab-pane label="登录" name="login" style="margin: 5% 10% 0 10%">
                            <el-form ref="loginForm" :model="loginForm" :rules="rules" status-icon>
                                <el-form-item prop="userName">
                                    <el-input prefix-icon="el-icon-user" placeholder="请输入账号"
                                              v-model="loginForm.userName"></el-input>
                                </el-form-item>
                                <el-form-item prop="password">
                                    <el-input prefix-icon="el-icon-edit" placeholder="请输入密码"
                                              v-model="loginForm.password" show-password></el-input>
                                </el-form-item>
                                <el-form-item>
                                    <el-button plain type="primary" style="width: 100%"
                                               @click="submitLogin('loginForm')">登录
                                    </el-button>
                                </el-form-item>
                            </el-form>
                        </el-tab-pane>
                        <el-tab-pane label="注册" name="register" style="margin: 5% 10% 0 10%">
                            <el-form ref="registerForm" :model="registerForm" :rules="rules2" status-icon>
                                <el-form-item prop="userName">
                                    <el-input prefix-icon="el-icon-user" placeholder="请输入账号"
                                              v-model="registerForm.userName"></el-input>
                                </el-form-item>
                                <el-form-item prop="password">
                                    <el-input prefix-icon="el-icon-edit" placeholder="请输入密码"
                                              v-model="registerForm.password" show-password></el-input>
                                </el-form-item>
                                <el-form-item prop="rePassword">
                                    <el-input prefix-icon="el-icon-edit" placeholder="请重复输入密码"
                                              v-model="registerForm.rePassword" show-password></el-input>
                                </el-form-item>
                                <el-form-item>
                                    <el-button plain type="success" style="width: 100%"
                                               @click="submitRegister('registerForm')">注册
                                    </el-button>
                                </el-form-item>
                            </el-form>
                        </el-tab-pane>
                    </el-tabs>
                </div>
            </el-card>
        </div>
    </div>
</template>

<script>
    import {login, add} from '@/api/system'
    import Vue from 'vue'
    export default {
        name: "index",
        data() {
            var validatePass = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error('请输入密码'));
                } else {
                    if (this.registerForm.rePassword !== '') {
                        this.$refs.registerForm.validateField('rePassword');
                    }
                    callback();
                }
            };
            var validatePass2 = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error('请再次输入密码'));
                } else if (value !== this.registerForm.password) {
                    callback(new Error('两次输入密码不一致!'));
                } else {
                    callback();
                }
            };
            return {
                tabsSelect: 'login',
                loginForm: {
                    userName: '',
                    password: ''
                },
                registerForm: {
                    userName: '',
                    password: '',
                    rePassword: '',
                },
                rules: {
                    userName: [
                        {required: true, message: '请输入账号'},
                    ],
                    password: [
                        {required: true, message: '请输入密码'}
                    ]
                },
                rules2: {
                    userName: [
                        {required: true, message: '请输入账号'},
                    ],
                    password: [
                        {validator: validatePass, trigger: 'blur'}
                    ],
                    rePassword: [
                        {validator: validatePass2, trigger: 'blur'}
                    ]
                }
            }
        },
        methods: {
            submitLogin(loginForm) {
                this.$refs[loginForm].validate((valid) => {
                    if (valid) {
                        login(this.loginForm).then(response => {
                            Vue.ls.set('userID',response.data.userID)
                            Vue.ls.set('token',response.data.token)
                            Vue.ls.set('nickName',response.data.nickName)
                            Vue.ls.set('logStatus',1)
                            this.$router.push('/webchat')
                        }).catch((e) => {
                            if(e.msg){
                                this.$message.error(e.msg);
                            }else {
                                this.$message.error(e.error);
                            }
                        })
                    } else {
                        return false;
                    }
                });
            },
            submitRegister(submitRegister) {
                this.$refs[submitRegister].validate((valid) => {
                    if (valid) {
                        add(this.registerForm).then(res =>{
                            this.$message.success('注册成功，请前往登录！');
                        }).catch((e) =>{
                            if(e.msg){
                                this.$message.error(e.msg);
                            }else {
                                this.$message.error(e.error);
                            }
                        })
                    } else {
                        return false;
                    }
                });
            },
            handleClick(tab, event) {
                console.log(tab, event);
            }
        },
        mounted() {
            Vue.ls.set('logStatus',0)
            if(Vue.ls.get('status') === 0){
                this.$message.error('身份过期，请重新登录')
                Vue.ls.set('status',1)
            }
        }
    }
</script>

<style>
    .el-header, .el-footer {
        background-color: #B3C0D1;
        color: #333;
        text-align: center;
        line-height: 60px;
    }

    .el-aside {
        background-color: #D3DCE6;
        color: #333;
        text-align: center;
        line-height: 200px;
    }

    .el-main {
        background-color: #E9EEF3;
        color: #333;
        text-align: center;
        line-height: 160px;
    }

</style>
