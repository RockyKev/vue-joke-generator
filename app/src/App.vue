<template>
  <div>
    <JokeApp :params="phpData" />
  </div>
</template>

<script>
import JokeApp from "./components/JokeApp.vue";

export default {
  name: "App",
  components: {
    JokeApp
  }, 
    props: {
    phpData: Object,
  },
  // mounted() {
  //   console.log(this.phpData);
  // },
};
</script>
