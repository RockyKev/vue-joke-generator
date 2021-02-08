<template>
  <div>
    <h1>Data</h1>
    <div v-if="errored">Sorry something happened.</div>
    <div v-else>
      <div v-if="loading">Loading...</div>
      <div v-else>
        <p>This is as of {{ info.time.updateduk }}</p>
        <p v-for="currency in info.bpi" :key="currency.id" class="currency">
          {{ currency.description }}({{ currency.code }}):
          {{ changeToDecimal(currency.rate_float, 2) }}
        </p>
      </div>
    </div>

    <!-- <p v-if="responseAvailable">JOKE: {{ result.joke }}</p> -->
  </div>
</template>

<script>
// https://www.techiediaries.com/vue-3-fetch-data-rest-api/

export default {
  name: "JokeApp",
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
      const url = "https://api.coindesk.com/v1/bpi/currentprice.json";

      fetch(url)
        .then((response) => response.json())
        .then((response) => {
          this.info = response;
        })
        .catch((error) => {
          console.log(error);
          this.error = true;
        })
        .finally(() => (this.loading = false));
    },
    changeToDecimal(value, decimal){
        return value.toFixed(decimal);
    }
  },
};

</script>

<style>
</style>
