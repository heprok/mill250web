<template>
  <v-container id="report_timber_dashboard" fluid tag="section">
    <v-row>
      <v-col cols="12">
        <shift-date-picker urlReport="report/timber"> </shift-date-picker>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "report_timber_dashboard"
};
</script>