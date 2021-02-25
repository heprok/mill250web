<template>
  <v-container id="report_registry_timber_dashboard" fluid tag="section">
    <v-row>
      <v-col cols="12">
        <shift-date-picker urlReport="report/timber_registry" :filterSqlWhere="filters">
        </shift-date-picker>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "report_registry_timber_dashboard",

  data() {
    return {
      filters: ["diam", "length", 'species'],
    };
  },
  mounted() {},
  methods: {},
};
</script>