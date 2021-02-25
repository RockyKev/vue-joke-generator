<template>
  <div>
    <div v-if="errored">Sorry. API call failed for some reason.</div>

    <div v-else>
      <div v-if="loading">Loading...</div>

      <div v-else>
        <h2>It's YUK YUK TIME.</h2>

        <p v-if="info.error">
          {{ info.message }}
        </p>

        <p v-else-if="info.type == 'twopart'">
          SETUP: {{ info.setup }} 
          <br /><br /><br />
          DELIVERY: {{ info.delivery }}
        </p>

        <p v-else>
          {{ info.joke }}
        </p>

        <div v-show="!info.error">
          <hr />
          METADATA from api:
          <ul>
            <li>ID: {{ info.id }}</li>
            <li>language: {{ info.lang}}</li>
            <li>type: {{ info.type}}</li>
            <li>category: {{ info.category}}</li>            
          </ul>
          Flags: 
          <ul>
            <li v-for="(value, name) in info.flags" :key="name">{{ name }} : {{ value }}</li>
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
      info: null,
      loading: true,
      errored: false,
    };
  },
  mounted() {
    this.fetchAPIData();
  },
  methods: {
    fetchAPIData() {
      console.log("in fetch");

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

      const url =
        "https://v2.jokeapi.dev/joke/" +
        categoriesOutput +
        blacklistOutput +
        jokeTypeOutput +
        rangeOutput;

      console.log("url sent", url);

      fetch(url)
        .then((response) => response.json())
        .then((response) => {
          this.info = response;
        })
        .catch((error) => {
          console.error(error);
          this.error = true;
        })
        .finally(() => {
          console.log(this.info);

          this.loading = false;
        });
    },
  },
};
</script>

<style>
</style>
