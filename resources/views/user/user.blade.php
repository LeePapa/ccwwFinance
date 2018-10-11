@extends('layouts.master')

@section('title', '代理用户')

@section('content')
<el-scrollbar id="user" style="height:100%;">
    <el-card class="box-card">
        <div slot="header" class="clearfix">
          <span>代理用户</span>
        </div>
        <el-form :inline="true" :model="form">
            <el-form-item label="代理">
                <el-select
                    style="width: 150px"
                    v-model="search.agent_id"
                    placeholder="选择级别">
                    <template v-for="agent in agents">
                        <el-option v-bind:key="agent.id" v-bind:label="agent.name" v-bind:value="agent.id"></el-option>
                    </template>
                </el-select>
            </el-form-item>
            <el-form-item label="微信">
                <el-input style="width: 150px" v-model="search.name"></el-input>
            </el-form-item>

          <el-form-item>
            <el-button icon="el-icon-search" circle type="success" @click="initInfo()"></el-button>
          </el-form-item>
        </el-form>

        <el-button type="primary" plain @click="add()" style="margin-bottom: 10px;">新增</el-button>
        <el-table
            :data="userData"
            border
            style="width: 100%">
            <el-table-column
              prop="id"
              label="ID"
              width="50">
            </el-table-column>
            <el-table-column
              prop="username"
              label="用户名">
            </el-table-column>
            <el-table-column
              prop="phone"
              label="手机号">
            </el-table-column>
            <el-table-column
              prop="weixin"
              label="微信">
            </el-table-column>
            <el-table-column
              prop="agent_name"
              label="代理级别">
            </el-table-column>
            <el-table-column
              prop="expend"
              label="支出">
            </el-table-column>
            <el-table-column
              prop="income"
              label="收入">
            </el-table-column>
            <el-table-column
              prop="profit"
              label="结余">
            </el-table-column>
            <el-table-column
                label="操作">
                <template slot-scope="scope">
                  <el-button type="text" size="small" @click="edit(scope.row)">编辑</el-button>
                  <el-button type="text" size="small" @click="remove(scope.row)" :disabled="!scope.row.can_del">删除</el-button>
                </template>
            </el-table-column>
        </el-table>
        <el-pagination style="float: right;"
            @current-change="pageChange"
            layout="prev, pager, next"
            :total="total">
        </el-pagination>
    </el-card>
    <el-dialog
      title="代理级别"
      :visible.sync="dialogVisible"
      width="30%">
      <el-form ref="form" :model="form" label-width="80px">
        <el-form-item label="用户名">
          <el-input v-model="form.username"></el-input>
        </el-form-item>
        <el-form-item label="手机号">
          <el-input v-model="form.phone"></el-input>
        </el-form-item>
        <el-form-item label="微信">
          <el-input v-model="form.weixin"></el-input>
        </el-form-item>
        <el-form-item label="代理级别">
            <el-select v-model="form.agent_id" placeholder="选择分类">
            <template v-for="agent in agents">
                <el-option v-bind:key="agent.id" v-bind:label="agent.name" v-bind:value="agent.id"></el-option>
            </template>
            </el-select>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="dialogVisible = false">取 消</el-button>
        <el-button type="primary" @click="makeSure()">确 定</el-button>
      </span>
    </el-dialog>
</el-scrollbar>
@endsection

@section('js')
<script type="text/javascript">
    var user = new Vue({
        el:'#user',
        data:{
            userData:[],
            agents:[],
            form:{
                id:'',
                username:'',
                phone:'',
                weixin:'',
                name:''
            },
            search:{
                agent_id:'',
                weixin:'',
                page:1
            },
            dialogVisible:false,
            action:'add',
            total:0
        },
        methods:{
            initInfo:function () {
                this.$axios({
                    method:'get',
                    url:'/user',
                    params:this.search
                })
                .then( res => {
                    this.userData = res.data.data.data;
                    this.total = res.data.data.total
                })
            },
            pageChange:function(page){
                this.search.page = page;
                initInfo();
            },
            initAgents:function() {
                this.$axios({
                    method:'get',
                    url:'/agent?type=select'
                })
                .then(res => {
                    this.agents = res.data.data;
                })
            },
            add:function(){
                this.dialogVisible = true;
                this.action = 'add';
                this.form = {
                    id:'',
                    name:''
                }
            },
            edit:function(item){
                this.form = item;
                this.dialogVisible = true;
                this.action = 'edit';
            },
            remove:function(item) {
                this.$remove('/user/'+item.id,res=>{
                    this.initInfo();
                });
            },
            makeSure:function(){
                if(this.action == 'edit'){
                    var url = '/user/'+this.form.id;
                    var method = 'put';
                }else{
                    var url = '/user';
                    var method = 'post';
                }
                this.$axios({
                    url:url,
                    method:method,
                    data:this.form
                })
                .then(res => {
                    this.initInfo();
                    this.dialogVisible = false
                })
            }
        },
        mounted:function(){
            this.initInfo();
            this.initAgents();
        }
    });
</script>
@endsection
