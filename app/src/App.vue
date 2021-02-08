<template>
  <div>
    <!-- <AuthorBio /> -->
    <JokeApp />
  </div>
</template>

<script>
// import AuthorBio from "./components/AuthorBio.vue";
import JokeApp from "./components/JokeApp.vue";

export default {
  name: "App",
  components: {
    // AuthorBio,
    JokeApp
  }, 
  mounted: function() {
    console.log("APP VUE LOADED");
  }
};
</script>

<style></style>
