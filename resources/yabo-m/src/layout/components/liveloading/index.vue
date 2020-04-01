<template>
  <div class="loadingMask">
    <div class="loadingIcon">
      <div class="loading" />
    </div>
  </div>
</template>

<style lang="scss" scoped>
.loadingMask {
  display: -ms-flexbox;
  display: flex;
  -ms-flex-pack: center;
  justify-content: center;
  -ms-flex-align: center;
  align-items: center;

  width: 100%;
  z-index: 1002;
  text-align: center;
  position: absolute;
  background: rgba(255, 255, 255, 0.96) center center no-repeat;
  height: 100%;
  .loading {
  height: 0;
  width: 0;
  padding: 15px;
  border: 6px solid #ccc;
  border-right-color: #888;
  border-radius: 22px;
  -webkit-animation: rotate 1s infinite linear;
  /* left, top and position just for the demo! */
  position: absolute;
  left: 45%;
  top: 30%;
}

@-webkit-keyframes rotate {
  /* 100% keyframe for  clockwise.
     use 0% instead for anticlockwise */
  100% {
    -webkit-transform: rotate(360deg);
  }
}
}
</style>
