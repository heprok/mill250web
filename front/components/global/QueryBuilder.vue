<template>
  <tlc-query-builder
    :rules="rules"
    v-model="query"
    @input="$emit('input', $event)"
  />
</template>

<script>
import tlcQueryBuilder from "builder-query-vuetify";
import { RuleTypes } from "builder-query-vuetify/src/utilities.js"

  
export default {
  name: "defaultTlcQueryBuilder",
  components: { tlcQueryBuilder },
  props: {
    value: {
      required: true,
    },
  },
  methods: {
  },
  mounted(){
  },
  watch: {
    // query(val){
      // this.$emit('input', val)
      // this.query = cloneDeep(val);
    // },
  },
  computed: {},
  data() {
    return {
      query: this.value,
      rules: [
        {
          type: RuleTypes.NUMBER,
          id: 2,
          label: "Age",
        },
        {
          type: RuleTypes.SELECT,
          id: 3,
          label: "Color",
          options: [
            { label: "red", value: "Red" },
            { label: "orange", value: "Orange" },
            { label: "yellow", value: "Yellow" },
            { label: "green", value: "Green" },
            { label: "blue", value: "Blue" },
            { label: "indigo", value: "Indigo" },
            { label: "violet", value: "Violet" },
          ],
        },
        {
          type: RuleTypes.MULTI_SELECT,
          id: 4,
          label: "Multi Color",
          options: [
            { label: "red", value: "Red" },
            { label: "orange", value: "Orange" },
            { label: "yellow", value: "Yellow" },
            { label: "green", value: "Green" },
            { label: "blue", value: "Blue" },
            { label: "indigo", value: "Indigo" },
            { label: "violet", value: "Violet" },
          ],
        },
      ],
    };
  },
};
</script>

<style>
</style>