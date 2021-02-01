<template>
  <v-stepper v-model="el">
    <v-stepper-header>
      <v-stepper-step :complete="el > 1" step="1" editable>
        Выбрать тип отчёта
      </v-stepper-step>

      <v-divider></v-divider>

      <v-stepper-step :complete="el > 2" step="2"> Параметры </v-stepper-step>
    </v-stepper-header>

    <v-stepper-items>
      <v-stepper-content step="1">
        <!-- <v-divider class="my-4"></v-divider> -->
        <v-row justify="space-around">
          <v-btn color="primary" class="my-4" x-large @click="selectShift">
            За смену
          </v-btn>
          <v-btn color="primary" class="my-4" x-large @click="selectPeriod">
            За период
          </v-btn>
        </v-row>
        <!-- <v-divider class="my-4"></v-divider> -->
      </v-stepper-content>

      <v-stepper-content step="2">
        <div v-if="isTypeReportIsShift">
          <v-row>
            <v-col cols="4">
              <v-date-picker
                locale="ru-ru"
                :max="today"
                :first-day-of-week="1"
                v-model="date"
                full-width
              ></v-date-picker>
            </v-col>
            <v-col cols="8">
              <base-material-card
                color="success"
                icon="mdi-account-group"
                :title="
                  'Смены на ' + new Date(date).toLocaleDateString() ||
                  'выберите день...'
                "
                class="px-5 py-3"
              >
                <v-spacer></v-spacer>
                <v-data-table
                  :headers="headersTableShift"
                  :loading="loadingTableShifts"
                  loading-text="Загрузка... Ждите"
                  no-data-text="За данный период нет смен"
                  :items="shifts"
                  v-model="selectedShift"
                  item-key="startTime"
                  show-select
                  @click:row="clickRowShift"
                  @dblclick:row="dbClickShift"
                  class="elevation-1"
                >
                </v-data-table>
              </base-material-card>
            </v-col>
            <v-col cols="12">
              <v-btn
                color="primary"
                :disabled="selectedShift.length == 0"
                @click="openReport"
                x-large
              >
                Составить
              </v-btn>
            </v-col>
          </v-row>
        </div>
        <div v-else>
          <v-row>
            <v-col cols="5">
              <v-date-picker
                :max="today"
                full-width
                locale="ru-ru"
                v-model="dates"
                range
                :first-day-of-week="1"
              >
              </v-date-picker>
            </v-col>
            <v-col cols="7">
              <v-row>
                <v-col cols="!2">
                  <v-text-field
                    label="Выбран интервал"
                    v-model="modelInterval"
                    readonly
                    prepend-icon="mdi-calendar-range"
                  >
                  </v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-row justify="space-around" align="center">
                    <v-col>
                      <menu-time-picker v-model="time.start" />
                    </v-col>
                    <v-col>
                      <menu-time-picker v-model="time.end" />
                    </v-col>
                  </v-row>
                </v-col>
              </v-row>
            </v-col>
            <v-col cols="12">
              <v-btn
                color="primary"
                :disabled="dates.length != 2"
                @click="openReport"
                x-large
              >
                Составить
              </v-btn>
            </v-col>
          </v-row>
        </div>
      </v-stepper-content>
    </v-stepper-items>
  </v-stepper>
</template>

<script>
import Axios from "axios";
import menuTimePicker from "./MenuTimePicker.vue";
export default {
  components: { menuTimePicker },
  name: "shiftDatePicker",
  data() {
    return {
      isTypeReportIsShift: true,
      selectIndex: {},
      selectedShift: [],
      modelInterval: "",
      el: 1,
      pickerDate: null,
      date: "",
      dates: [],
      time: {
        start: "08:00:00",
        end: "08:00:00",
      },
      menu: {
        startTime: null,
        endTime: null,
      },
      loadingTableShifts: true,
      shifts: [],
      headersTableShift: [
        { text: "ФИО", value: "people.fio" },
        { text: "Номер", value: "number" },
        { text: "Начало", value: "startTime" },
        { text: "Конец", value: "endTime" },
      ],
    };
  },
  props: {
    urlReport: {
      type: String,
      require: true,
    },
  },
  watch: {
    dates() {
      // if (this.dates.length == 0) return;

      if (this.dates[0] > this.dates[1]) {
        const tmp = this.dates[0];
        this.dates[0] = this.dates[1];
        this.dates[1] = tmp;
      }
      this.modelInterval = this.textInterval;
    },
    time: {
      handler(val) {
        this.modelInterval = this.textInterval;
      },
      deep: true,
    },
    async date(value) {
      // let periodDay = this.$store.getters.timeForTheDay(value);
      let start = value + "T00:00:00";
      let end = value + "T23:59:59";
      let config = {
        params: {
          startTimestampKey: start + "..." + end,
        },
      };

      let request = await Axios.get("api/shifts", config);
      //todo напиисать алерт при кол-ве 0
      this.shifts = request.data["hydra:member"];
      this.selectedShift = [];
      this.loadingTableShifts = false;
      return request;
    },
  },
  beforeMount() {
    this.date = this.today;
  },
  computed: {
    textInterval() {
      // if (this.dates.length == 0) return;
      let result =
        "c " +
        (this.dates[0] || "[дата не выбрана]") +
        " " +
        this.time.start +
        " до " +
        (this.dates[1] || "[дата не выбрана]") +
        " " +
        this.time.end;

      return result;
    },
    today() {
      return new Date().toISOString().substr(0, 10);
    },
  },
  methods: {
    openReport() {
      let start = "";
      let stop = "";
      if (this.isTypeReportIsShift) {
      //воозращает числа из api/people/ID и убирает повторяющиеся элементы
      let idsPeople = Array.from(
        new Set(
          this.selectedShift.map((shift) => {
            // let id = shift.people['@id'].replace(/\D+/g,"");
            return shift.people["@id"].replace(/\D+/g, "");
          })
        )
      );

      let datesStartShift = this.selectedShift.map(
        (shift) => new Date(shift.start)
      );
      let datesStopShift = this.selectedShift.map(
        (shift) => new Date(shift.stop) ?? new Date()
      );

      let maxDate = this.$moment(Math.max.apply(null, datesStopShift)).format();
      let minDate = this.$moment(
        Math.min.apply(null, datesStartShift)
      ).format();
      window.open(
        this.urlReport +
          "/" +
          minDate +
          "..." +
          maxDate +
          "/people/" +
          idsPeople.join("...") +
          "/pdf"
      );

      } else {
      start = this.dates[0] + "T" + this.time.start;
      stop = this.dates[1] + "T" + this.time.end;
      window.open(this.urlReport + "/" + start + "..." + stop + "/pdf");
      }
    },
    clickRowShift(item) {
      this.selectedShift.indexOf(item) == -1
        ? this.selectedShift.push(item)
        : this.selectedShift.splice(this.selectedShift.indexOf(item), 1);
    },
    dbClickShift(object, item) {
      this.selectedShift = [];
      this.selectedShift.push(item.item);
      this.openReport();
    },
    selectShift() {
      this.isTypeReportIsShift = true;
      this.el = 2;
    },
    selectPeriod() {
      this.isTypeReportIsShift = false;
      this.el = 2;
    },
  },
};
</script>

<style>
</style>