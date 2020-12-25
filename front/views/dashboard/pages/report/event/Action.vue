<template>
  <v-container id="report_event_action_dashboard" fluid tag="section">
    <v-row>
      <v-col cols="12">
        <shift-date-picker urlReport="report/event/action_operator">
        </shift-date-picker>
      </v-col>
      <v-col cols="12">
        <crud-table
          title="Действия оператора за сегодняшний день"
          url-api="api/events"
          :query="query"
          icon="mdi-gesture-double-tap"
          :headers="headers"
        />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "report_event_action_dashboard",
  data() {
    return {
      headers: [
        { text: "Время", value: "startTime" },
        { text: "Действие", value: "text" },
      ],
    };
  },
  methods: {
    today() {
      return new Date().toISOString().substr(0, 10);
    },
  },
  computed: {
    query() {
      let start = this.today() + "T00:00:00";
      let end = this.today() + "T23:59:59";

      return { drecTimestampKey: start + "..." + end, type: "a", source: "o" };
    },
  },
};
</script>