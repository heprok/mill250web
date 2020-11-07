<template>
  <v-container id="report_timber_postav_dashboard" fluid tag="section">
    <v-row>
      <v-col cols="12">
        <shift-date-picker urlReport="report/timber_postav"/>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "report_timber_postav_dashboard",
};
</script>