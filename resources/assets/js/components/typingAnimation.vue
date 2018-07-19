<template>
  <div class="dots-container">
    <div class="c-three-dots-loader"></div>
  </div>
</template>

<style>

.c-three-dots-loader {
  position: relative;
  display: inline-block;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  margin: -20px 24px 20px;
  animation-fill-mode: both;
  animation: three-dots-loader-animation 2s infinite ease-in-out;
  animation-delay: -0.16s;
  color: #efefef;
}
.c-three-dots-loader:before,
.c-three-dots-loader:after {
  content: '';
  position: absolute;
  width: 10px;
  height: 10px;
  top: 0;
  animation: three-dots-loader-animation 2s infinite ease-in-out;
  border-radius: 50%;
}
.c-three-dots-loader:before {
  left: -20px;
  animation-delay: -0.32s;
}
.c-three-dots-loader:after {
  left: 20px;
}
@keyframes three-dots-loader-animation {
  0%,
  80%,
  100% {
    box-shadow: 0 20px 0 -24px;
  }
  40% {
    box-shadow: 0 20px 0 0;
  }
}

</style>

<script>
  export default{
    name: 'typingAnimation'
  }
</script>