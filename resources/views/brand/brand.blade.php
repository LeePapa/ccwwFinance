@extends('layouts.master')

@section('title', '品牌')

@section('content')
<div id="brand">
    <el-card class="box-card">
        <div slot="header" class="clearfix">
          <span>品牌</span>
        </div>
        <el-button type="primary" plain @click="add()" style="margin-bottom: 10px;">新增</el-button>
        <el-table
            :data="brandData"
            border
            style="width: 100%">
            <el-table-column
              prop="id"
              label="ID"
              width="180">
            </el-table-column>
            <el-table-column
              prop="brand_name"
              label="名称"
              width="180">
            </el-table-column>
            <el-table-column
              prop="created_at"
              label="创建日期">
            </el-table-column>
            <el-table-column
                label="操作">
                <template slot-scope="scope">
                  <el-button type="text" size="small" @click="edit(scope.row)">编辑</el-button>
                  <el-button type="text" size="small" @click="remove(scope.row)">删除</el-button>
                </template>
            </el-table-column>
        </el-table>
        <el-pagination style="float: right;"
            layout="prev, pager, next"
            :total="total">
        </el-pagination>
    </el-card>
    <el-dialog
      title="代理级别"
      :visible.sync="dialogVisible"
      width="30%">
      <el-form ref="form" :model="form" label-width="80px">
        <el-form-item label="名称">
          <el-input v-model="form.brand_name"></el-input>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="dialogVisible = false">取 消</el-button>
        <el-button type="primary" @click="makeSure()">确 定</el-button>
      </span>
    </el-dialog>

</div>
@endsection

@section('js')
<script type="text/javascript">
    var brand = new Vue({
        el:'#brand',
        data:{
            brandData:[],
            form:{
                id:'',
                brand_name:''
            },
            dialogVisible:false,
            action:'add',
            total:0
        },
        methods:{
            initInfo:function () {
                this.$axios({
                    method:'get',
                    url:'/brand'
                })
                .then( res => {
                    this.brandData = res.data.data.data;
                    this.total = res.data.data.total
                })
            },
            add:function(){
                this.dialogVisible = true;
                this.action = 'add';
                this.form = {
                    id:'',
                    brand_name:''
                }
            },
            edit:function(item){
                this.form = item;
                this.dialogVisible = true;
                this.action = 'edit';
            },
            remove:function(item) {
                this.$remove('/brand/'+item.id,res=>{
                    this.initInfo();
                });
            },
            makeSure:function(){
                if(this.action == 'edit'){
                    var url = '/brand/'+this.form.id;
                    var method = 'put';
                }else{
                    var url = '/brand';
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
        }
    });
</script>
@endsection
