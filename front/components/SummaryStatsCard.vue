<template>
  <v-dialog v-model="dialog" width="1000">
    <template v-slot:activator="{ on, attrs }">
      <v-btn v-bind="attrs" @click="date = today" v-on="on" fab small>
        <v-icon>mdi-calendar-today</v-icon>
        <!-- Краткая сводка за день -->
      </v-btn>
    </template>
    <base-material-card
      :loading="loading"
      :color="colorCard"
      :title="'Краткая сводка за ' + new Date(date).toLocaleDateString()"
    >
      <v-card-text>
        <v-row>
          <v-col cols="5">
            <v-date-picker
              :max="today"
              full-width
              locale="ru-ru"
              v-model="date"
              :first-day-of-week="1"
            >
            </v-date-picker>
          </v-col>
          <v-col cols="7">
            <v-simple-table>
              <tbody>
                <tr>
                  <td><p class="font-weight-regular">Смена</p></td>
                  <td align="center" v-for="shift in shifts" :key="shift.name">
                    {{ shift.name }}
                  </td>
                  <!-- <td>{{ shifts.number1.name }}</td> -->
                  <!-- <td>{{ shifts.number2.name }}</td> -->
                </tr>
                <tr>
                  <td><p class="font-weight-regular">Объём досок м3</p></td>
                  <td align="center" v-for="shift in shifts" :key="shift.name">
                    {{ shift.volumeBoards }}
                  </td>
                </tr>
                <tr>
                  <td><p class="font-weight-regular">Итоговый объем м3</p></td>
                  <td align="center" :colspan="shifts.length">
                    <p class="font-weight-bold">{{ summary.volumeBoards }} </p>
                  </td>
                </tr>
                <tr>
                  <td><p class="font-weight-regular">Простой</p></td>
                  <td align="center" v-for="shift in shifts" :key="shift.name">
                    {{ shift.downtime }}
                  </td>
                </tr>
                <tr>
                  <td><p class="font-weight-regular">Итоговый простой</p></td>
                  <td align="center" :colspan="shifts.length">
                    <p class="font-weight-bold">{{ summary.downtime }} </p>
                  </td>
                </tr>
              </tbody>
            </v-simple-table>
            <v-col
              cols="auto"
              v-for="report in urlsToReport"
              :key="report.name"
            >
              <v-btn
                :loading="loading"
                color="primary"
                :disabled="shifts.length == 0"
                target="_blank"
                elevation="2"
                block
                class="mt-n3"
                :href="report.url + '/' + period + '/' + report.type"
              >
                {{ report.name }}
              </v-btn>
            </v-col>
          </v-col>
        </v-row>
      </v-card-text>
    </base-material-card>
  </v-dialog>
</template>

<script>
import Axios from "axios";
export default {
  name: "SummaryStatsCard",
  data() {
    return {
      urlApi: "api/infocard/summaryDay",
      urlsToReport: [
        { name: "Отчёт по брёвнам", url: "report/timber", type: "pdf" },
        { name: "Отчёт по простям", url: "report/downtimes", type: "pdf" },
        {
          name: "Отчёт по брёвнам из постава",
          url: "report/timber_postav",
          type: "pdf",
        },
        {
          name: "Отчёт по доскам из постава",
          url: "report/board_postav",
          type: "pdf",
        },
        {
          name: "Отчёт по авариям и сообщениям",
          url: "report/event/alert",
          type: "pdf",
        },
        {
          name: "Отчёт по действиям оператора",
          url: "report/event/action_operator",
          type: "pdf",
        },
      ],
      dialog: false,
      loading: false,
      colorCard: "primary",
      date: "",
      summary: {
        // volumeBoards: 0.0,
        // downtime: "00:00:00",
      },
      shifts: [
        // {
        //   volumeBoards: 0,
        //   downtime: "00:00:00",
        //   name: "1 смена",
        // },
        // {
        //   volumeBoards: 0,
        //   downtime: "00:00:00",
        //   name: "2 смена",
        // },
      ],
    };
  },
  watch: {
    async date() {
      this.loading = true;
      let request = null;
      try {
        request = await Axios.get(this.urlApi + "/" + this.period);
        let data = request.data;
        this.shifts = data.shifts;
        this.colorCard = "primary";
        this.summary = data.summary;
      } catch (error) {
        // console.log(error.response);
        this.$snotify.error(error.response.data);
        this.colorCard = "error";
        this.shifts = [];
        this.summary = {};
      } finally {
        this.loading = false;
      }
      return request;
    },
  },
  methods: {},
  computed: {
    today() {
      return new Date().toISOString().substr(0, 10);
    },
    period() {
      let periodDay = this.$store.getters.timeForTheDay(this.date);
      return periodDay.start + "..." + periodDay.end;
      // let periodDay = this.$store.getters.timeForTheDay(this.date);
      // return this.date.start + "..." + this.date.end;
    },
  },
};
</script>

<style>
</style>