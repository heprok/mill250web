<template>
  <base-material-stats-card
    :loading="loading"
    v-bind="$attrs"
    v-on="$listeners"
    :smallValue="value"
    :sub-text="subtitle"
    :color="color"
  >
  </base-material-stats-card>
</template>

<script>
import Axios from "axios";

export default {
  name: "InfoCard",

  data() {
    return {
      value: "",
      subtitle: "",
      loading: false,
      color: '',
    };
  },
  watch: {},
  computed: {},
  props: {
    urlApi: {
      type: String,
      require: true,
    },
  },
  mounted() {
    this.update();
  },
  methods: {
    async update() {
      this.loading = true;
      let request = null;
      try {
        request = await Axios.get(this.urlApi);
        let data = request.data;
        this.value = data.value;
        this.subtitle = data.subtitle;
        this.color = data.color;
      } catch (err) {
        let data = err.response.data;
        this.color = data.color;
        this.value = data.value;
      } finally {
        this.loading = false;
        return request;
      }
    },
  },
};
</script>

<style>
</style>
