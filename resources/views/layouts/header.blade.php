<div id="header">
    <span style="padding-left: 30px;">
        简跃商务管理系统
    </span>
    <el-button type="primary" plain @click="openDialog">记一笔账</el-button>
    <el-button type="primary" plain @click="productIn">商品入库</el-button>
    <!-- 账单弹窗 -->
    <el-dialog
      title="详情"
      :visible.sync="dialogVisible"
      width="60%">
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
        </el-form>
        <span slot="footer" class="dialog-footer">
        <el-button @click="dialogVisible = false">取 消</el-button>
        <el-button type="primary" @click="add()">确 定</el-button>
      </span>
    </el-dialog>
    <!-- 入库弹窗 -->
    <el-dialog
      title="详情"
      :visible.sync="productDialog"
      width="50%">
        <el-form ref="form" label-width="80px">
            <el-form-item label="商品">
                <el-select
                    v-model="productForm.brand_id"
                    placeholder="选择品牌"
                    @change="productBrandChange">
                <template v-for="brand in brands">
                    <el-option v-bind:key="brand.id" v-bind:label="brand.brand_name" v-bind:value="brand.id"></el-option>
                </template>
                </el-select>
                <el-select v-model="productForm.product_id" placeholder="选择商品">
                <template v-for="product in products">
                    <el-option v-bind:key="product.id" v-bind:label="product.name" v-bind:value="product.id"></el-option>
                </template>
                </el-select>
                <el-input-number v-model="productForm.stock"></el-input-number>
            </el-form-item>
        </el-form>
        <span slot="footer" class="dialog-footer">
        <el-button @click="productDialog = false">取 消</el-button>
        <el-button type="primary" @click="inbound()">确 定</el-button>
      </span>
    </el-dialog>
</div>

<script type="text/javascript">
    var headerVue = new Vue({
        el:'#header',
        data:{
            index:'',
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
            productForm:{
                brand_id:'',
                product_id:'',
                stock:'',
            },
            agent_name:'',
            dialogVisible:false,
            productDialog:false,
            agents:[],
            brands:[],
            products:[],
            users:[]
        },
        methods:{
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
            openDialog:function(){
                this.form = {
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
                };
                this.initAgents();
                this.brandInfo();
                this.usersInfo();
                this.dialogVisible = true;
            },
            productIn:function(){
                this.productForm = {
                    brand_id:'',
                    product_id:'',
                    stock:'',
                };
                this.brandInfo();
                this.productDialog = true;
            },
            inbound:function(){
                this.$axios({
                    method:'put',
                    url:'/product/'+this.productForm.product_id+'/inbound',
                    data:{
                        stock:this.productForm.stock
                    }
                })
                .then(res => {
                    this.productDialog = false;
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
            productBrandChange:function(value){
                this.$axios({
                    method:'get',
                    url:'/product',
                    params:{
                        type:'select',
                        brand_id:value,
                    }
                }).
                then( res=>{
                    this.productForm.product_id = '';
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
                    this.form = {
                        products:[{
                            brand_id:'',
                            product_id:'',
                            product_num:0
                        }],
                        receive_id:''
                    };
                    dialogVisible = false;
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
        }
    });
</script>