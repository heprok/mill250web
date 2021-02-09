<template>
  <v-container id="report_board_postav_dashboard" fluid tag="section">
    <v-row>
      <v-col cols="12">
        <shift-date-picker
          urlReport="report/board_postav"
          :filterSqlWhere="filters"
        >
        </shift-date-picker>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "report_board_postav_dashboard",
  data() {
    return {
      filters: ["postav_board_diam", "length", 'species', "postav"],
    };
  },
};
</script>