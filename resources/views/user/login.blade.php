<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style type="text/css">
      body,html{
        width: 100%;
        height: 100%;
      }
    </style>
    <!-- 引入样式 -->
    <link rel="stylesheet" href="https://unpkg.com/element-ui@2.4.7/lib/theme-chalk/index.css">
    <link rel="stylesheet" href="/css/common.css">
    <script src="https://unpkg.com/vue@2.5.17/dist/vue.js"></script>
    <!-- 引入组件库 -->
    <script src="https://unpkg.com/element-ui@2.4.7/lib/index.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="/js/common.js"></script>
    <title>财务系统 - 登录</title>
</head>
<body>
<div id="login_body">
  <div id="login-form">
    <el-form ref="form" :model="form">
      <el-form-item style="text-align: center;">
        商务管理系统
      </el-form-item>
      <el-form-item>
        <el-input v-model="form.phone" placeholder="用户名"></el-input>
      </el-form-item>
      <el-form-item>
        <el-input v-model="form.password" type="password" placeholder="密码"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" style="margin-left: 280px;" v-on:click="login()">登录</el-button>
      </el-form-item>
    </el-form>
  </div>
</div>
<script type="text/javascript">
  var user = new Vue({
        el:'#login_body',
        data:{
            form: {
              phone: '',
              password: ''
            },
            dom:{
              loginForm:'login-form'
            }
        },
        methods: {
          login:function(){
            this.$axios({
              method:'post',
              url:'/admin/login',
              data:this.form
            })
            .then(res => {
              if(res.data.code == 200){
                window.location.href = '/view/product_exchange'
              }
            });
          }
      }
  })
</script>
</body>
</html>

