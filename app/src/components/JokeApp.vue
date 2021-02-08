<template>
  <div>
    <h1>Data</h1>
    <div v-if="errored">Sorry something happened. </div>
    <div v-else>

      <div v-if="loading">Loading... </div>
      <div v-else
           v-for="currency in info"
           :key="currency.id"
           class="currency">
           {{ currency.description }}
           <!-- <span class="lighten">
             <span v-html="currency.symbol"></span>{{ currency.rate_float | currencydecimal }}
           </span> -->
      </div>

    </div>

    <!-- <p v-if="responseAvailable">JOKE: {{ result.joke }}</p> -->
  </div>
</template>

<script>
// https://www.techiediaries.com/vue-3-fetch-data-rest-api/

import axios from 'axios';

export default {
  name: "JokeApp",
  data: function() {
    return {
      info: null,
      loading: true,
      errored: false
    };
  },
  // filters: {
  //   currencydecimal (value) {
  //     return value.toFixed(2);
  //   }
  // },
  mounted() {
    const url = 'https://api.coindesk.com/v1/bpi/currentprice.json';

    axios
      .get(url)
      .then(response => {
        this.info = response.data.bpi;
      })
      .catch(error => {
        console.log(error);
        this.error = true;
      })
      .finally(() => this.loading = false)
  }
};

// methods: {
//     fetchAPIData() {
//       this.responseAvailable = false;

//       // const category = "Programming";
//       // const language = "";
//       // const flag = "blacklistFlags=nsfw,racist";
//       // const jokeType = "";
//       console.log("in fetchAPI Data");

//       // https://v2.jokeapi.dev/joke/Programming,Dark
//       fetch(`https://v2.jokeapi.dev/joke/Programming,Dark?blacklistFlags=nsfw,racist`)
//       // fetch(`https://v2.jokeapi.dev/joke/${category}?${flag}`)
//         .then(response => {
//           console.log("In then response")
//           if (response.ok) {
//             return response.json();
//           } else {
//             alert(
//               "Server returned " + response.status + " : " + response.statusText
//             );
//           }
//         })
//         .then(response => {
//           console.log("fetch was successful");
//           this.result = response.body;
//           this.responseAvailable = true;
//         })
//         .catch(err => {
//           console.log(err);
//         });
//     },
//   },
//   mounted: function() {
//     console.log("HI THERE");
//     this.fetchAPIData();
//   }
</script>

<style>
</style>
