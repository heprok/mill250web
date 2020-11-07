<template>
  <v-container id="report_downtimes_dashboard" fluid tag="section">
    <v-row>
      <v-col cols="12">
        <shift-date-picker urlReport="report/downtimes"> </shift-date-picker>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12">
        <crud-table />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
<<<<<<< HEAD
  name: "report_downtimes_dashboard",

  data() {
    return {
      headers: [
        { text: 'Причина', value: "cause.name"},
        { text: 'Место', value: "place.name"},
        { text: 'Начало', value: "start"},
        { text: 'Окончание', value: "finish"},
        
      ]
    };
  },
  mounted() {},
  methods: {},
=======
  name: "report_downtimes_dashboard"
>>>>>>> 6d890012dbaf7e0d3859c9943c0172cd20449b07
};
</script>