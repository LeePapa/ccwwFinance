@extends('layouts.master')

@section('title', '账单')

@section('content')
<div id="exchange">
    <el-card class="box-card">
        <div slot="header" class="clearfix">
          <span>账单</span>
        </div>
        <el-form v-for="(record,index) in form.products" ref="form" :model="record" label-width="100px" :key="index.toString()">
            <el-form-item label="品牌名称">
                <el-select
                    v-model="record.brand_id"
                    placeholder="选择品牌"
                    @change="brandChange">
                <template v-for="brand in brands">
                    <el-option v-bind:key="brand.id" v-bind:label="brand.brand_name" v-bind:value="brand.id"></el-option>
                </template>
                </el-select>
                <el-select v-model="record.product_id" placeholder="选择商品">
                <template v-for="product in products">
                    <el-option v-bind:key="product.id" v-bind:label="product.name" v-bind:value="product.id"></el-option>
                </template>
                </el-select>
                <el-input-number v-model="record.product_num"></el-input-number>
            </el-form-item>
            <el-button type="primary" plain @click="add()" style="margin-bottom: 10px;">记一笔账</el-button>
        </el-form>
        <el-table
            :data="exchangeData"
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
              prop="product_num"
              label="库存">
            </el-table-column>
            <el-table-column
                v-for="agent in agents"
                :label="agentName(agent.name)"
                :key="agent.id"
                :label="agentName(agent.name)">
                <template slot-scope="scope">
                  @{{scope.row.prices[agent.id].price}}
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
            layout="prev, pager, next"
            :total="total">
        </el-pagination>
    </el-card>
</div>
@endsection
@section('js')
<script type="text/javascript">
    var exchange = new Vue({
        el:'#exchange',
        data:{
            exchangeData:[],
            form:{
                products:[{
                    name:'',
                    brand_id:'',
                    product_id:'',
                    product_num:0
                }]
            },
            dialogVisible:false,
            action:'add',
            total:0,
            agents:[],
            brands:[],
            products:[]
        },
        methods:{
            initInfo:function () {
                this.$axios({
                    method:'get',
                    url:'/product_exchange'
                })
                .then( res => {
                    this.exchangeData = res.data.data.data;
                    this.total = res.data.data.total
                })
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
            brandChange:function(value){
                this.$axios({
                    method:'get',
                    url:'/product?type=select&brand_id=' + value
                })
                .then(res => {
                    this.products = res.data.data;
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
                    stock:0,
                    prices:{}
                };
            },
            edit:function(item){
                this.form = item;
                for(index in item.prices){
                    this.$set(this.form.prices,item.prices[index].id, item.prices[index].price)
                }
                this.dialogVisible = true;
                this.action = 'edit';
            },
            remove:function(item) {
                this.$remove('/product_exchange/'+item.id,res=>{
                    this.initInfo();
                });
            },
            makeSure:function(){
                if(this.action == 'edit'){
                    var url = '/product_exchange/'+this.form.id;
                    var method = 'put';
                }else{
                    var url = '/product_exchange';
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
