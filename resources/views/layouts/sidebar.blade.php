<el-scrollbar id="sidebar" style="height:100%;">
  <el-menu
    :default-active="activeIndex()"
    background-color="#545c64"
    text-color="#fff"
    active-text-color="#ffd04b">
    <el-menu-item v-for="(menu,index) in menus" :index="index.toString()" :key="index" @click="handleSelect(menu.route)">
      <i :class="menu.icon"></i>
      <span slot="title">@{{menu.name}}</span>
    </el-menu-item>
  </el-menu>
</el-scrollbar>
<script type="text/javascript">
  var sidebar = new Vue({
    el:'#sidebar',
    data:{
      menus:[
        {
          name:'统计信息',
          icon:'iconfont icon-tubiao',
          route:'/view/echart'
        },
        {
          name:'账单记录',
          icon:'el-icon-tickets',
          route:'/view/product_exchange'
        },
        {
          name:'商品信息',
          icon:'el-icon-goods',
          route:'/view/product'
        },
        {
          name:'代理用户',
          icon:'iconfont icon-icon_zhanghao',
          route:'/view/user'
        },
        {
          name:'代理级别',
          icon:'iconfont icon-liuliangyunpingtaitubiao03',
          route:'/view/agent'
        },
        {
          name:'品牌信息',
          icon:'iconfont icon-kujialeqiyezhan_pinpaixilieguanli',
          route:'/view/brand'
        }

      ]
    },
    methods:{
      handleSelect(keyPath) {
        window.location.href = keyPath;
      },
      activeIndex(){
        var url = window.location.pathname
        for (var i = 0; i < this.menus.length; i++) {
          if(this.menus[i].route == url) return i.toString()
        }
      }
    },
    mounted:function(){

    }
  });
</script>