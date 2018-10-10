@extends('layouts.master')

@section('title', '商品')

@section('content')
<el-scrollbar id="product" style="height:100%;">
    <el-card class="box-card">
        <div slot="header" class="clearfix">
          <span>商品</span>
        </div>
        <el-button type="primary" plain @click="add()" style="margin-bottom: 10px;">新增</el-button>
        <el-table
            :data="productData"
            border
            style="width: 100%">
            <el-table-column
              prop="id"
              label="ID"
              width="180">
            </el-table-column>
            <el-table-column
              prop="brand_name"
              label="品牌名称"
              width="180">
            </el-table-column>
            <el-table-column
              prop="name"
              label="名称"
              width="180">
            </el-table-column>
            <el-table-column
              prop="stock"
              label="库存">
            </el-table-column>
            <el-table-column
                v-for="agent in agents"
                :label="agentName(agent.name)"
                :key="agent.id"
                :label="agentName(agent.name)">
                <template slot-scope="scope">
                  @{{scope.row.prices[agent.id] ? scope.row.prices[agent.id] : '0'}}
                </template>
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
            @current-change="pageChange"
            layout="prev, pager, next"
            :total="total">
        </el-pagination>
    </el-card>
    <el-dialog
      title="商品信息"
      :visible.sync="dialogVisible"
      width="30%">
      <el-form ref="form" :model="form" label-width="100px">
        <el-form-item label="商品名称">
          <el-input v-model="form.name"></el-input>
        </el-form-item>
        <el-form-item label="品牌名称">
            <el-select v-model="form.brand_id" placeholder="选择分类">
            <template v-for="brand in brands">
                <el-option v-bind:key="brand.id" v-bind:label="brand.brand_name" v-bind:value="brand.id"></el-option>
            </template>
            </el-select>
        </el-form-item>
        <!-- <el-form-item label="商品库存">
            <el-input-number v-model="form.stock"></el-input-number>
        </el-form-item> -->
        <el-form-item  v-for="agent in agents" :label="agentName(agent.name)" :key="agent.id">
            <el-input-number :precision="2" v-model="form.prices[agent.id]"></el-input-number>
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
    var product = new Vue({
        el:'#product',
        data:{
            productData:[],
            form:{
                id:'',
                name:'',
                // stock:0,
                prices:{}
            },
            dialogVisible:false,
            action:'add',
            total:0,
            agents:[],
            brands:[]
        },
        methods:{
            initInfo:function (page) {
                this.$axios({
                    method:'get',
                    url:'/product',
                    params:{
                        page:page
                    }
                })
                .then( res => {
                    this.productData = res.data.data.data;
                    this.total = res.data.data.total
                })
            },
            pageChange:function(page){
                this.initInfo(page)
            },
            initAgents:function(){
                this.$axios({
                    method:'get',
                    url:'/agent?type=select'
                })
                .then(res => {
                    this.agents = res.data.data;
                })
            },
            brandInfo:function(){
                this.$axios({
                    method:'get',
                    url:'/brand?type=select'
                })
                .then(res => {
                    this.brands = res.data.data;
                })
            },
            agentName:function(name){
                return name+' 价格';
            },
            add:function(){
                this.dialogVisible = true;
                this.action = 'add';
                this.form = {
                    id:'',
                    name:'',
                    // stock:0,
                    prices:{}
                };
            },
            edit:function(item){
                this.form = item;
                for(index in item.prices){
                    this.$set(this.form.prices, index, item.prices[index])
                }
                this.dialogVisible = true;
                this.action = 'edit';
            },
            remove:function(item) {
                this.$remove('/product/'+item.id,res=>{
                    this.initInfo();
                });
            },
            makeSure:function(){
                if(this.action == 'edit'){
                    var url = '/product/'+this.form.id;
                    var method = 'put';
                }else{
                    var url = '/product';
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
            this.brandInfo();
        }
    });
</script>
@endsection
