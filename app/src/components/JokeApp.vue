<template>
  <div>
    <div v-if="errored">Sorry. API call failed for some reason.</div>

    <div v-else>
      <div v-if="loading">Loading...</div>

      <div v-else>
        <h2>It's YUK YUK TIME.</h2>

        <p v-html="content"></p>

        <!-- Random extra content because why not -->
        <div v-show="!response.error">
          <hr />
          METADATA from api:
          <ul>
            <li>ID: {{ response.id }}</li>
            <li>language: {{ response.lang}}</li>
            <li>type: {{ response.type}}</li>
            <li>category: {{ response.category}}</li>            
          </ul>
          Flags: 
          <ul>
            <li v-for="(value, name) in response.flags" :key="name">{{ name }} : {{ value }}</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "JokeApp",
  props: {
    params: Object,
  },
  data: function () {
    return {
      response: null,
      loading: true,
      errored: false,
      content: "",
    };
  },
  mounted() {
    this.fetchAPI();
  },
  methods: {
    fetchAPI() {
      console.log("in fetch");

      const url =
        "https://v2.jokeapi.dev/joke/" + this.addFetchParams();

      console.log("url sent", url);

      fetch(url)
        .then((response) => response.json())
        .then((response) => {
          this.response = response;
        })
        .catch((error) => {
          console.error(error);
          this.error = true;
        })
        .finally(() => {
          console.log(this.response);
          this.showResults();

          this.loading = false;
        });
    },
    addFetchParams() {
      // get category
      const categories = Object.keys(this.params.category)
                             .filter((key) => this.params.category[key]);
      const categoriesOutput = categories?.length ? categories.toString() + "?" : "Any?";

      // get blacklist
      const blacklist = Object.keys(this.params.blacklist)
                            .filter((key) => this.params.blacklist[key]);
      const blacklistOutput = blacklist?.length ? "blacklistFlags=" + blacklist.toString() + "&" : "";

      // get jokeType
      const jokeType = this.params.jokeType;
      const jokeTypeOutput = jokeType !== "any" ? "type=" + jokeType + "&" : "";

      // get range -- if any of them are blank, ignore
      const rangeFrom = this.params.range.from;
      const rangeTo = this.params.range.to;
      const rangeOutput =
        (rangeFrom !== null) && (rangeFrom !== undefined) && rangeTo
          ? "idRange=" + rangeFrom + "-" + rangeTo : "";

        return categoriesOutput + blacklistOutput + jokeTypeOutput + rangeOutput;
    },
    showResults() {

      // if it's an error, show it. Else show the joke type
      if (this.response.error) {
        this.content = this.response.message;
      } else if (this.response.type === "twopart") {
        this.content = `
          SETUP: ${this.response.setup} <br /><br /><br />
          DELIVERY: ${ this.response.delivery }
        `
      } else if (this.response.type === "single") {
        this.content = this.response.joke;
      } else {
        this.content = "Something happened and I'm not quite sure.";
      }

    }
  },
};
</script>

<style>
</style>
