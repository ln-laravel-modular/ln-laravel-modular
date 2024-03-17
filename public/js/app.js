if (window.axios) {
    // 添加一个请求拦截器
    axios.interceptors.request.use(function (config) {
        // Do something before request is sent
        // console.log(`axios.interceptors.request ~ then`, {
        //   config
        // })
        return config;
    }, function (error) {
        // Do something with request error
        // console.log(`axios.interceptors.request ~ catch`, {
        //   error
        // })
        return Promise.reject(error);
    });
    // 添加一个响应拦截器
    axios.interceptors.response.use(function (response) {
        // Do something with response data
        const {
            data
        } = response
        // console.log(`axios.interceptors.response.use ~ then`, {
        //   data
        // })
        if (data.status === 200) {
            return data.data;
        } else {
            return Promise.reject(data);
        }
    }, function (error) {
        // Do something with response error
        // console.log(`axios.interceptors.response.use ~ catch`, {
        //   error
        // })
        return Promise.reject(error);
    });
}
