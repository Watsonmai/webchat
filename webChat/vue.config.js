module.exports = {
    devServer: {
        //proxy: 'http://112.74.50.162:8081/webchat'
        proxy: {
            '/webchat': {
                target: 'http://112.74.50.162:8081',
                ws: true,
                changeOrigin: true
            },
            '/echo': {
                target: 'http://112.74.50.162:8081',
                ws: true,
                changeOrigin: true
            }
        }
    }
}

