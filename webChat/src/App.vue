<template>
  <div id="app">
    <router-view/>
  </div>
</template>

<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
body {
  /*background: -webkit-linear-gradient(#001A3F, #00427C); !* Safari 5.1 - 6.0 *!*/
  /*background: -o-linear-gradient(#001A3F, #00427C); !* Opera 11.1 - 12.0 *!*/
  /*background: -moz-linear-gradient(#001A3F, #00427C); !* Firefox 3.6 - 15 *!*/
  background: radial-gradient(ellipse farthest-corner at 25px 25px,#01204E 0%, #00427C 25%,#35BBE0 50%,#E3F6FA 75%,#C2AEE3);
}
html {
  height: 100%;
}
</style>
