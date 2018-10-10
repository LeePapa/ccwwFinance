Vue.prototype.$axios = axios;
Vue.use(ELEMENT);

Vue.prototype.$axios.interceptors.response.use(
    response => {
        //请求出错
        if(response.status != 200){
            ELEMENT.Message({
                message: '服务异常',
                type: 'error'
            });
            return false;
        }else{
            //服务端回应错误
            if(response.data.code != '200'){
                ELEMENT.Message({
                    message: response.data.message,
                    type: 'error'
                });
            }else{
                //非get请求，结果提示
                if(response.config.method.toLowerCase() != 'get'){
                    ELEMENT.Message({
                        message: response.data.message,
                        type: 'success'
                    });
                }
            }
            return response;
        }
    }
)

Vue.prototype.$remove = function(url,callback){
    ELEMENT.MessageBox.confirm('此操作将会彻底删除记录，是否继续？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
    })
    .then(() => {
        Vue.prototype.$axios({
            method:'delete',
            url:url
        })
        .then(response => {
            callback(response);
        })
    })
    .catch(() => {
      ELEMENT.Message({
        type: 'info',
        message: '已取消删除'
      });
    });
}

Vue.prototype.$date = function(format,time){
    format = format || 'Y-m-d H:i:s';
    if(time){
        var date = new Date(parseInt(time)*1000);
    }else{
        var date = new Date();
    }
    var info = {};
    info['Y'] = date.getFullYear();
    info['m'] = (m = (date.getMonth() + 1)) < 10 ? '0' + m : m;
    info['d'] = (d = date.getDate()) < 10 ? '0' + d : d;
    info['H'] = (hh = date.getHours()) < 10 ? '0' + hh : hh;
    info['i'] = (mm = date.getMinutes()) < 10 ? '0' + mm : mm;
    info['s'] = (ss = date.getSeconds()) < 10 ? '0' + ss : ss;
    var strArr = format.split('');
    for (var i = 0; i < strArr.length; i++) {
        strArr[i] = info[strArr[i]] || strArr[i];
    }
    return strArr.join('');
}