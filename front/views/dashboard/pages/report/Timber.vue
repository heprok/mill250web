<template>
  <v-container id="report_timber_dashboard" fluid tag="section">
    <v-row>
      <v-col
        cols="12"
        sm="6"
        lg="4"
        v-for="infoCard in infoCards"
        :key="infoCard.nameCard"
      >
        <info-card
          :color="infoCard.color"
          :icon="infoCard.icon"
          :sub-icon="infoCard.subIcon"
          :title="infoCard.nameCard"
          :urlApi="infoCard.urlApi"
          :durations="infoCard.duration"
        />
      </v-col>
      <v-col cols="12">
        <shift-date-picker urlReport="report/timber" :filterSqlWhere="filters">
        </shift-date-picker>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "report_timber_dashboard",
  data() {
    return {
      filters: ["diam", "length", 'species'],
      infoCards: [
        {
          nameCard: "Кол-во брёвен",
          color: "info",
          icon: "mdi-poll",
          urlApi: "/infocard/countTimber",
          duration: [
            {
              url: "/currentShift",
              title: "за смену",
            },
            {
              url: "/today",
              title: "за сутки",
            },
            {
              url: "/weekly",
              title: "за неделю",
            },
            {
              url: "/mountly",
              title: "за месяц",
            },
          ],
        },
        {
          nameCard: "Объем брёвен",
          color: "info",
          icon: "mdi-poll",
          urlApi: "/infocard/volumeTimber",
          duration: [
            {
              url: "/currentShift",
              title: "за смену",
            },
            {
              url: "/today",
              title: "за сутки",
            },
            {
              url: "/weekly",
              title: "за неделю",
            },
            {
              url: "/mountly",
              title: "за месяц",
            },
          ],
        },
      ],
    };
  },
};
</script>