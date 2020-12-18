<template>
  <v-container id="report_downtimes_dashboard" fluid tag="section">
    <v-row>
      <v-col cols="12" sm="6" lg="4">
        <info-card
          color="orange"
          icon="mdi-sofa"
          title="Последний простой"
          urlApi="/api/infocard/lastDowntime"
          sub-icon="mdi-clock"
        />
      </v-col>
      <v-col cols="12" sm="6" lg="4">
        <info-card
          color="orange"
          icon="mdi-sofa"
          title="Сумарный простой за сегодня"
          urlApi="/api/infocard/total/today"
          sub-icon="mdi-clock"
        />
      </v-col>
      <v-col cols="12" sm="6" lg="4">
        <info-card
          color="orange"
          icon="mdi-sofa"
          title="Сумарный простой за неделю"
          urlApi="/api/infocard/total/week"
          sub-icon="mdi-clock"
        />
      </v-col>
      <v-col cols="12">
        <shift-date-picker urlReport="report/downtimes"> </shift-date-picker>
      </v-col>
      <v-col cols="12">
        <crud-table
          title="Простои за сегодняшний день"
          url-api="api/downtimes"
          :query="query"
          icon="mdi-pine-tree"
          :headers="headers"
        />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "report_downtimes_dashboard",
  data() {
    return {
      headers: [
        { text: "Причина", value: "cause.name" },
        { text: "Место", value: "place.name" },
        { text: "Начало", value: "startTime" },
        { text: "Конец", value: "endTime" },
        { text: "Продолжительность", value: "durationTime" },
      ],
    };
  },
  computed: {
    query() {
      let start = this.today() + "T00:00:00";
      let end = this.today() + "T23:59:59";
      return { drecTimestampKey: start + "..." + end };
    },
  },
  methods: {
    today() {
      return new Date().toISOString().substr(0, 10);
    },
  },
};
</script>