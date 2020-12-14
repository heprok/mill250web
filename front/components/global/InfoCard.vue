<template>
  <base-material-stats-card
    v-bind="$attrs"
    v-on="$listeners"
    :smallValue="value"
    :sub-text="subtitle"
  >
  </base-material-stats-card>
</template>

<script>
import Axios from 'axios';

export default {
  name: "InfoCard",

  data() {
    return {
      value: '',
      subtitle: ''
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
      let data = request.data;
      this.value = data.value;
      this.subtitle = data.subtitle;
      return request;
    }
  }
};
</script>

<style>


</style>
