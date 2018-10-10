<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- 引入样式 -->
    <link rel="stylesheet" href="https://unpkg.com/element-ui@2.4.7/lib/theme-chalk/index.css">
    <link rel="stylesheet" type="text/css" href="/icon/iconfont.css">
    <link rel="stylesheet" href="/css/common.css">
    <script src="https://unpkg.com/vue@2.5.17/dist/vue.js"></script>
    <!-- 引入组件库 -->
    <script src="https://unpkg.com/element-ui@2.4.7/lib/index.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://unpkg.com/echarts@4.2.0-rc.1/dist/echarts.min.js"></script>
    <script src="/js/common.js"></script>
    <title>财务系统 - @yield('title')</title>
</head>
<body>
<div class="layout-header">
    @include('layouts.header')
</div>
<div class="layout-sidebar">
    @include('layouts.sidebar')
</div>
<div class="layout-content">
    @yield('content')
</div>
<div class="layout-footer">
    @include('layouts.footer')
</div>
@yield('js')
</body>
</html>