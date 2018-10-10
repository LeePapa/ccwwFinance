@extends('layouts.master')

@section('title', '统计')

@section('content')
<el-scrollbar id="echart" style="height:100%;">
    <div id="main" style="width: 600px;height:400px;"></div>
    <el-button @click="add()">点击</el-button>
</el-scrollbar>
@endsection

@section('js')
<script type="text/javascript">
    var echartVue = new Vue({
        el:'#echart',
        data:{
            echartOption:{
                title: {
                    text: 'ECharts 入门示例'
                },
                tooltip: {},
                legend: {
                    data:['销量']
                },
                xAxis: {
                    data: ["衬衫","羊毛衫","雪纺衫","裤子","高跟鞋","袜子"]
                },
                yAxis: {},
                series: [{
                    name: '销量',
                    type: 'bar',
                    data: [5, 20, 36, 10, 10, 20]
                }]
            }
        },
        methods:{
            add:function(){
                this.echartOption.series[0].data[0]++;
            }
        },
        mounted:function(){
            var myChart = echarts.init(document.getElementById('main'));
            // 使用刚指定的配置项和数据显示图表。
            myChart.setOption(this.echartOption);
            this.$axios({
                url:'/statistics/month/2018-10',
            })
            .then( res => {
                console.log(res.data.data)
            })
        },
        watch:{
            echartOption:{
                handler:function(val,oldVal){
                    console.log(1);
                    var myChart = echarts.init(document.getElementById('main'));
                    myChart.setOption(val);
                },
                deep:true
            }
        }
    });
</script>
@endsection
