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
        <shift-date-picker urlReport="report/timber"> </shift-date-picker>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "report_timber_dashboard",
  data() {
    return {
      infoCards: [
        {
          nameCard: "Кол-во брёвен",
          color: "info",
          icon: "mdi-poll",
          urlApi: "/api/infocard/countTimber",
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
              title: "за 7 дней",
            },
            {
              url: "/mountly",
              title: "за 30 дней",
            },
          ],
        },
        {
          nameCard: "Объем брёвен",
          color: "info",
          icon: "mdi-poll",
          urlApi: "/api/infocard/volumeTimber",
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
              title: "за 7 дней",
            },
            {
              url: "/mountly",
              title: "за 30 дней",
            },
          ],
        },
      ],
    };
  },
};
</script>