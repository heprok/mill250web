<template>
  <base-material-stats-card
    v-bind="$attrs"
    v-on="$listeners"
    :smallValue="value"
  >
  </base-material-stats-card>
</template>

<script>
import Axios from 'axios';

export default {
  name: "InfoCard",

  data() {
    return {
      value: ''
    };
  },
  watch: {  
  },
  computed: {
  },
  props: {
    urlApi: {
      type: String,
      require: true
    }
  },
  mounted() {
    this.update();
  },
  methods: {
    async update() {
      let request = await Axios.get(this.urlApi);
      console.log(request.data);
      this.value = request.data.value;
    }
  }
};
</script>

<style>


</style>
