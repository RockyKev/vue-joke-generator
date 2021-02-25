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
          SETUP: {{ info.setup }} <br />
          DELIVERY: {{ info.delivery }}
        </p>

        <p v-else>
          {{ info.joke }}
        </p>

        <div v-show="!info.error">
          METADATA: This joke is not.
          <ul v-for="(value, name) in info.flags" :key="name">
            <li>{{ name }} : {{ value }}</li>
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
    // this.getProps();
  },
  methods: {
    fetchAPIData() {
      console.log("in fetch");
      // console.log(this.params.category);

      // get category
      let categories = Object.keys(this.params.category).filter(
        (key) => this.params.category[key]
      );

      const categoriesOutput = categories?.length
        ? categories.toString() + "?"
        : "Any?";
      // console.log({categories})
      console.log({ categoriesOutput });

      // get blacklist
      let blacklist = Object.keys(this.params.blacklist).filter(
        (key) => this.params.blacklist[key]
      );
      const blacklistOutput = blacklist?.length
        ? "blacklistFlags=" + blacklist.toString() + "&"
        : "";

      // get jokeType
      // let jokeType = Object.keys(this.params.jokeType).filter(
      //   (key) => this.params.jokeType[key]
      // );
      let jokeType = this.params.jokeType;
      const jokeTypeOutput = jokeType !== "any" ? "type=" + jokeType + "&" : "";

      // get range -- if any of them are blank, ignore
      // range can be zero
      const rangeFrom = this.params.range.from;
      const rangeTo = this.params.range.to;
      const rangeOutput =
        rangeFrom !== null && rangeFrom !== undefined && rangeTo
          ? "idRange=" + rangeFrom + "-" + rangeTo
          : "";
      // console.log("Range from" + rangeFrom + " to " + rangeTo);
      // console.log({rangeOutput})
      // let jokeRange2 = "idRange=50-100";

      // let range =
      // console.log({categories})

      // const url = "https://v2.jokeapi.dev/joke/Any?" + blacklist2 + jokeType2 + jokeRange2;
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
          console.log(error);
          this.error = true;
        })
        .finally(() => {
          console.log("in finally");
          console.log(this.info);

          this.loading = false;
        });
    },
  },
};
</script>

<style>
</style>
