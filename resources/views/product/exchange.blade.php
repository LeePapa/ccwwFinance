@extends('layouts.master')

@section('title', '账单')

@section('content')
<el-scrollbar id="exchange" style="height:100%;">
    <el-card class="box-card">
        <div slot="header" class="clearfix">
          <span>账单</span>
        </div>
        <el-form :inline="true" :model="form">
          <el-form-item label="日期选择">
            <!-- <el-date-picker
              v-model="form.btime"
              type="date"
              placeholder="选择日期">
            </el-date-picker>
            至
            <el-date-picker
              v-model="form.etime"
              type="date"
              placeholder="选择日期">
            </el-date-picker> -->

            <el-date-picker
              @change="dateChange"
              v-model="form.month"
              type="month"
              value-format="yyyy-MM"
              placeholder="选择月">
            </el-date-picker>

          </el-form-item>
          <!-- <el-form-item>
            <el-button icon="el-icon-search" circle type="success" @click="initInfo()"></el-button>
          </el-form-item> -->
        </el-form>
        <span style="float: right;font-size: 14px;color: #409EFF;padding-right: 30px;">
            收入:@{{dataTotal.total}}
            支出:@{{dataTotal.user_total}}
            利润:@{{dataTotal.profit}}
        </span>
        <el-table
            :data="exchangeData"
            border
            style="width: 100%">
            <el-table-column type="expand"
              prop="id">
              <template slot-scope="props">
                <el-form label-position="left" inline v-for="detail in props.row.details" :key="detail.id">
                  <el-form-item label="品牌名称">
                    <el-tag type="danger">@{{ detail.brand_name }}</el-tag>
                  </el-form-item>
                  <el-form-item label="商品名称">
                    <el-tag type="danger">@{{ detail.product_name }}</el-tag>
                  </el-form-item>
                  <el-form-item label="商品数量">
                    <el-tag type="danger">@{{ detail.product_num }}</el-tag>
                  </el-form-item>
                  <el-form-item label="出售价格">
                    <el-tag type="danger">@{{ detail.price }}</el-tag>
                  </el-form-item>
                  <el-form-item label="商品单价">
                    <el-tag type="danger">@{{ detail.user_price }}</el-tag>
                  </el-form-item>
                  <el-form-item label="出售总价">
                    <el-tag type="danger">@{{ detail.total }}</el-tag>
                  </el-form-item>
                  <el-form-item label="商品盈利">
                    <el-tag type="danger">@{{ detail.profit }}</el-tag>
                  </el-form-item>
                </el-form>
              </template>
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
              label="收入">
            </el-table-column>
            <el-table-column
              prop="profit"
              label="盈利">
            </el-table-column>
            <el-table-column
              prop="weixin"
              label="微信">
            </el-table-column>
            <!-- <el-table-column
              prop="phone"
              label="手机号">
            </el-table-column> -->
            <el-table-column
                label="操作">
                <template slot-scope="scope">
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
</el-scrollbar>
@endsection
@section('js')
<script type="text/javascript">
    var exchange = new Vue({
        el:'#exchange',
        data:{
            form:{
                // btime:'',
                // etime:'',
                page:1,
                month:'',
                page:1
            },
            exchangeData:[],
            total:0,
            dataTotal:{
                total:0,
                profit:0,
                user_total:0
            }
        },
        methods:{
            initInfo:function () {
                this.$axios({
                    method:'get',
                    url:'/product_exchange',
                    params:this.form
                })
                .then( res => {
                    this.exchangeData = res.data.data.data;
                    this.total = res.data.data.total
                    this.dataTotal = res.data.data.dataTotal;
                })
            },
            dateChange:function(){
                this.initInfo();
            },
            pageChange:function(page){
                this.form.page = page;
                this.initInfo();
            },
            remove:function(item) {
                this.$remove('/product_exchange/'+item.id,res=>{
                    this.initInfo();
                });
            }
        },
        mounted:function(){
            // var btime = new Date();
            // btime = btime.getTime() - 29*24*60*60*1000;
            // this.form.btime = this.$date('Y-m-d', btime/1000);
            // this.form.etime = this.$date('Y-m-d');
            this.form.month = this.$date('Y-m');
            this.initInfo();
        }
    });
</script>
@endsection
