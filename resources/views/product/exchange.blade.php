@extends('layouts.master')

@section('title', '账单')

@section('content')
<div id="exchange">
    <el-card class="box-card">
        <div slot="header" class="clearfix">
          <span>账单</span>
        </div>
        <el-form ref="form" label-width="100px">
            <el-form-item v-for="(record,index) in form.products" label="商品"
                :key="index.toString()"
                v-on:mouseover.native="activeIndex(index)">
                <el-select
                    v-model="record.brand_id"
                    placeholder="选择品牌"
                    @change="brandChange">
                <template v-for="brand in brands">
                    <el-option v-bind:key="brand.id" v-bind:label="brand.brand_name" v-bind:value="brand.id"></el-option>
                </template>
                </el-select>
                <el-select v-model="record.product_id" placeholder="选择商品" @change="productChange">
                <template v-for="product in products">
                    <el-option v-bind:key="product.id" v-bind:label="product.name" v-bind:value="product.id"></el-option>
                </template>
                </el-select>
                <el-input-number v-model="record.product_num"></el-input-number>
                <el-tag v-if="record.product_id">
                    库存剩余:@{{record.stock}},单价:@{{currentPrice(record.allPrices,index)}}
                </el-tag>
                <el-button icon="el-icon-delete" v-if="showdel(index)" @click="removeParam(index)"></el-button>
            </el-form-item>
            <el-form-item label="接收人">
                <el-select v-model="form.receive_id" filterable placeholder="请选择" @change="userChange">
                    <el-option
                      v-for="user in users"
                      :key="user.id"
                      :label="formatUser(user)"
                      :value="user.id"
                      >
                    </el-option>
                </el-select>
                <el-tag v-if="form.receive_id" type="danger">代理等级:@{{agent_name}},@{{totalPrice()}}</el-tag>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" plain @click="add()" style="margin-bottom: 10px;">记一笔账</el-button>
            </el-form-item>
        </el-form>
        <el-table
            :data="exchangeData"
            border
            style="width: 100%">
            <el-table-column type="expand"
              prop="id"
              width="50">
            </el-table-column>
            <el-table-column
              prop="id"
              label="ID"
              width="50">
            </el-table-column>
            <el-table-column
              prop="flowid"
              label="流水号">
            </el-table-column>
            <el-table-column
              prop="total"
              label="总价">
            </el-table-column>
            <el-table-column
              prop="profit"
              label="盈利">
            </el-table-column>
            <el-table-column
              prop="username"
              label="接收人">
            </el-table-column>
            <el-table-column
              prop="phone"
              label="手机号">
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
            index:'',
            exchangeData:[],
            form:{
                products:[{
                    brand_id:'',
                    product_id:'',
                    product_num:0,
                    allPrices:[],
                    stock:0,
                    price:0,
                    userPrice:0
                }],
                receive_id:''
            },
            agent_name:'',
            dialogVisible:false,
            action:'add',
            total:0,
            agents:[],
            brands:[],
            products:[],
            users:[]
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
            usersInfo:function(){
                this.$axios({
                    method:'get',
                    url:'/user?type=select'
                })
                .then(res => {
                    this.users = res.data.data;
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
            currentPrice:function(prices,index){
                if(prices[this.form.receive_id]){
                    this.form.products[index].price = prices[this.form.receive_id].price;
                    return prices[this.form.receive_id].price;
                }
                return '0.00';

            },
            productChange:function(value){
                this.$axios({
                    method:'get',
                    url:'/product/'+value
                })
                .then(res => {
                    this.form.products[this.index].allPrices = res.data.data.prices;
                    this.form.products[this.index].stock = res.data.data.stock;
                    this.form.products[this.index].userPrice = res.data.data.userPrice;
                })
            },
            brandChange:function(value){
                this.$axios({
                    method:'get',
                    url:'/product?type=select&brand_id=' + value
                })
                .then(res => {
                    this.form.products[this.index].product_id = '';
                    this.products = res.data.data;
                })
            },
            activeIndex:function(index){
                this.index = index;
            },
            agentName:function(name){
                return name + ' 价格';
            },
            add:function(){
                this.$axios({
                    method:'post',
                    url:'/product_exchange',
                    data:this.form
                })
                .then(res => {
                    this.initInfo();
                    this.form = {
                        products:[{
                            brand_id:'',
                            product_id:'',
                            product_num:0
                        }],
                        receive_id:''
                    };
                })
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
            },
            showdel:function(i){
                var info = this.form.products[i];
                return info.brand_id || info.product_id || info.product_num;
            },
            removeParam:function(i){
                if(this.form.products.length == 1) return true;
                this.form.products.splice(i, 1);
            },
            formatUser:function(user){
                return user.username + '-' + user.phone
            },
            userChange:function(id){
                for (var i = this.users.length - 1; i >= 0; i--) {
                    if(this.users[i].id == id){
                        this.agent_name = this.users[i].agent_name;
                        break;
                    }
                }
            },
            totalPrice:function(){
                var total = 0;
                var userTotal = 0;
                for (var i = this.form.products.length - 1; i >= 0; i--) {
                    if(!this.form.products[i].product_id) continue;
                    total += this.form.products[i].product_num*this.form.products[i].price;
                    userTotal += this.form.products[i].product_num*this.form.products[i].userPrice;
                }
                return '总价：'+ total.toString() +',盈利：' + (total-userTotal).toString();
            }
        },
        watch:{
            form:{
                handler:function(val,oldVal){
                    var info = val.products[val.products.length-1];
                    if(info.brand_id || info.product_id || info.product_num){
                        this.form.products.push({
                            brand_id:'',
                            product_id:'',
                            product_num:0,
                            allPrices:[],
                            price:0,
                            stock:0,
                            userPrice:0
                        });
                    }
                },
                deep:true
            }
        },
        mounted:function(){
            this.initInfo();
            this.initAgents();
            this.brandInfo();
            this.usersInfo();
        }
    });
</script>
@endsection
