<template>
  <v-container id="report_event_alert_dashboard" fluid tag="section">
    <v-row>
      <v-col cols="12">
        <shift-date-picker urlReport="report/event/alert"> </shift-date-picker>
      </v-col>
      <v-col cols="12">
        <crud-table
          title="Аварии и сообщения сегодняшний день"
          url-api="api/events"
          :query="query"
          icon="mdi-comment-alert-outline"
          :headers="headers"
        />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "report_event_alert_dashboard",
  data() {
    return {
      headers: [
        { text: "Время", value: "startTime" },
        { text: "Сообщение", value: "text" },
        { text: "Источник", value: "source.name" },
        { text: "Тип", value: "type.name" },
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

      return {
        drecTimestampKey: start + "..." + end,
        type: ["e", "m"],
        source: ["p", "s", "o"],
      };
    },
  },
};
</script>