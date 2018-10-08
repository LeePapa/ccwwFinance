<div id="sidebar">
<el-scrollbar style="height:100%;">
  <el-menu
    :default-active="activeIndex"
    background-color="#545c64"
    text-color="#fff"
    active-text-color="#ffd04b">
    <el-menu-item v-for="(menu,index) in menus" :index="index.toString()" :key="index" @click="handleSelect(menu.route)">
      <i :class="menu.icon"></i>
      <span slot="title">@{{menu.name}}</span>
    </el-menu-item>
  </el-menu>
</el-scrollbar>
</div>
<script type="text/javascript">
  var sidebar = new Vue({
    el:'#sidebar',
    data:{
      activeIndex:'0',
      menus:[
        {
          name:'概述',
          icon:'el-icon-menu',
          route:'/view/product_exchange'
        },
        {
          name:'商品',
          icon:'el-icon-goods',
          route:'/view/product'
        },
        {
          name:'代理用户',
          icon:'el-icon-tickets',
          route:'/view/user'
        },
        {
          name:'代理级别',
          icon:'el-icon-star-on',
          route:'/view/agent'
        },
        {
          name:'品牌',
          icon:'el-icon-picture',
          route:'/view/brand'
        }

      ]
    },
    methods:{
      handleSelect(keyPath) {
        window.location.href = keyPath;
      }
    },
    mounted:function(){

    }
  });
</script>