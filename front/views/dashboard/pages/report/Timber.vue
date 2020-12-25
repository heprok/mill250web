<template>
  <v-container id="report_timber_dashboard" fluid tag="section">
    <v-row>
      <v-col cols="12" sm="6" lg="3">
        <info-card
          color="info"
          icon="mdi-poll"
          title="Кол-во брёвен за смену"
          urlApi="/api/infocard/countTimber/currentShift"
        />
      </v-col>
      <v-col cols="12" sm="6" lg="3">
        <info-card
          color="info"
          icon="mdi-poll"
          title="Кол-во брёвен за сегодня"
          urlApi="/api/infocard/countTimber/today"
        />
      </v-col>
      <v-col cols="12" sm="6" lg="3">
        <info-card
          color="info"
          icon="mdi-poll"
          title="Объем брёвен за сегодня"
          urlApi="/api/infocard/volumeTimber/today"
        />
      </v-col>
      <v-col cols="12" sm="6" lg="3">
        <info-card
          color="info"
          icon="mdi-poll"
          title="Объем брёвен за смену"
          urlApi="/api/infocard/volumeTimber/currentShift"
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
};
</script>