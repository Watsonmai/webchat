import Vue from 'vue'
var websock = null
var reconnectData = null
var lockReconnect = false    //避免重复连接，因为onerror之后会立即触发 onclose
var timeout = 10000          //10s一次心跳检测
var timeoutObj = null
var serverTimeoutObj = null
var getMsg={}

function initWebSocket(token,userID) {
    console.log('启动中')
    let wsurl = 'ws:112.74.50.162:8081/chatws?token='+token+'&userID='+userID;
    websock = new WebSocket(wsurl);
    websock.onopen = websocketonopen;          //连接成功
    websock.onmessage = websocketonmessage;    //广播成功
    websock.onerror = websocketonerror;        //连接断开，失败
    websock.onclose = websocketclose;          //连接关闭
}//初始化weosocket

function websocketonopen() {
    console.log('连接成功')
    heatBeat();
}//连接成功

function websocketonerror(data) {
    console.log('连接失败')
    //alert('连接服务器失败！请重新登录再尝试。')
    reconnect();
}//连接失败

function websocketclose() {
    console.log('断开连接');
    reconnect();
}//各种问题导致的 连接关闭

function websocketonmessage(data) {
    getMsg.data = data.data
    heatBeat();      //收到消息会刷新心跳检测，如果一直收到消息，就推迟心跳发送
    let msgData = data;
}//数据接收

function websocketsend(data) {
    websock.send(JSON.stringify(data));
}//数据发送

function reconnect() {
    if (lockReconnect) {       //这里很关键，因为连接失败之后之后会相继触发 连接关闭，不然会连接上两个 WebSocket
        return
    }
    lockReconnect = true;
    reconnectData && clearTimeout(reconnectData);
    reconnectData = setTimeout(() => {
        initWebSocket();
        lockReconnect = false;
    }, 5000)
}//socket重连

function heatBeat() {
    timeoutObj && clearTimeout(timeoutObj);
    serverTimeoutObj && clearTimeout(serverTimeoutObj);
    timeoutObj = setTimeout(() => {
        var data = {
            type:0
        }
        websocketsend(data)   //根据后台要求发送
        serverTimeoutObj = setTimeout(() => {
            websock.close();       //如果  5秒之后我们没有收到 后台返回的心跳检测数据 断开socket，断开后会启动重连机制
        }, 5000);
    }, timeout)
}//心跳检测

function exit() {
    lockReconnect = true;
    websock.close()                   //离开路由之后断开websocket连接
    clearTimeout(reconnectData);      //离开清除 timeout
    clearTimeout(timeoutObj);         //离开清除 timeout
    clearTimeout(serverTimeoutObj);   //离开清除 timeout
}

export {websocketsend, initWebSocket,exit,getMsg}
