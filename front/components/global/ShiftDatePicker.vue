<template>
  <v-stepper v-model="el">
    <v-stepper-header>
      <v-stepper-step :complete="el > 1" step="1" editable>
        Выбрать тип отчёта
      </v-stepper-step>
      <v-divider />
      <v-stepper-step :editable="el > 2" :complete="el > 3" step="2"
        >Фильтры <small>Необезательно</small></v-stepper-step
      >
      <v-divider />

      <v-stepper-step :complete="el > 3" step="3"> Параметры </v-stepper-step>
    </v-stepper-header>

    <v-stepper-items>
      <v-stepper-content step="1">
        <!-- <v-divider class="my-4"></v-divider> -->
        <v-row justify="space-around">
          <v-btn
            color="primary"
            class="my-4"
            x-large
            @click="selectTypeReport('shift')"
          >
            За смену
          </v-btn>
          <v-btn
            v-if="isShowBatch"
            color="primary"
            class="my-4"
            x-large
            @click="selectTypeReport('batch')"
          >
            По партии
          </v-btn>
          <v-btn
            color="primary"
            class="my-4"
            x-large
            @click="selectTypeReport('period')"
          >
            За период
          </v-btn>
        </v-row>
        <!-- <v-divider class="my-4"></v-divider> -->
      </v-stepper-content>
      <v-stepper-content step="2">
        <v-row>
          <v-col cols="12">
            <default-tlc-query-builder
              ref="qb"
              :filters="filterSqlWhere"
              v-model="query"
            >
            </default-tlc-query-builder>
          </v-col>
          <v-col cols="12">
            <v-btn color="primary" @click="el = 3" x-large> Далее </v-btn>
          </v-col>
        </v-row>
      </v-stepper-content>
      <v-stepper-content step="3">
        <div v-if="typeReport == 'shift'">
          <v-row>
            <v-col cols="4">
              <v-date-picker
                locale="ru-ru"
                :max="today.start"
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
                  :headers="shift.headers"
                  :loading="shift.loading"
                  loading-text="Загрузка... Ждите"
                  no-data-text="За данный период нет смен"
                  :items="shift.items"
                  v-model="shift.selected"
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
                :disabled="shift.selected.length == 0"
                @click="openReport"
                x-large
              >
                Составить
              </v-btn>
            </v-col>
          </v-row>
        </div>
        <div v-else-if="typeReport == 'period'">
          <v-row>
            <v-col cols="5">
              <v-date-picker
                :max="today.end"
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
                <v-col cols="12">
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
        <div v-else-if="typeReport == 'batch'">
          <v-row>
            <v-col cols="4">
              <v-date-picker
                locale="ru-ru"
                :max="today.start"
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
                  'Партии на ' + new Date(date).toLocaleDateString() ||
                  'выберите день...'
                "
                class="px-5 py-3"
              >
                <v-spacer></v-spacer>
                <v-data-table
                  :headers="batch.headers"
                  :loading="batch.loading"
                  loading-text="Загрузка... Ждите"
                  no-data-text="За данный период нет партий"
                  :items="batch.items"
                  v-model="batch.selected"
                  item-key="id"
                  show-select
                  single-select
                  @click:row="clickRowBatch"
                  @dblclick:row="dbClickBatch"
                  class="elevation-1"
                >
                </v-data-table>
              </base-material-card>
            </v-col>
            <v-col cols="12">
              <v-btn
                color="primary"
                :disabled="batch.selected.length == 0"
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
import defaultTlcQueryBuilder from "./QueryBuilder";
export default {
  components: { menuTimePicker, defaultTlcQueryBuilder },
  name: "shiftDatePicker",
  data() {
    return {
      typeReport: "",
      query: {
        operator: "AND",
        children: [],
      },
      el: 1,
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
      shift: {
        headers: [
          { text: "ФИО", value: "people.fio" },
          { text: "Номер", value: "number" },
          { text: "Начало", value: "startTime" },
          { text: "Конец", value: "endTime" },
        ],
        items: [],
        loading: true,
        selected: [],
      },
      batch: {
        headers: [
          { text: "№", value: "id" },
          { text: "Накладная", value: "waybill" },
          { text: "Поставщик", value: "provider.name" },
          { text: "Начало", value: "firstDateTimberFormat" },
          { text: "Окончание", value: "lastDateTimberFormat" },
        ],
        items: [],
        loading: true,
        selected: [],
      },
    };
  },
  props: {
    isShowBatch: {
      type: Boolean,
      default: false,
    },
    urlReport: {
      type: String,
      require: true,
    },
    filterSqlWhere: {
      type: Array,
      require: true,
    },
  },
  watch: {
    dates() {
      if (this.dates[0] > this.dates[1]) {
        const tmp = this.dates[0];
        this.dates[0] = this.dates[1];
        this.dates[1] = tmp;
      }
    },
    async date(value) {
      let start = value + "T00:00:00";
      let end = value + "T23:59:59";
      let config = {
        params: {
          period: start + "..." + end,
        },
      };
      let request;
      switch (this.typeReport) {
        case "shift":
          request = await Axios.get(
            this.$store.state.apiEntryPoint + "/shifts",
            config
          );
          //todo напиисать алерт при кол-ве 0
          this.shift.items = request.data["hydra:member"];
          this.shift.selected = [];
          this.shift.loading = false;
          break;
        case "batch":
          request = await Axios.get(
            this.$store.state.apiEntryPoint + "/batches",
            config
          );
          //todo напиисать алерт при кол-ве 0
          this.batch.items = request.data["hydra:member"];
          this.batch.selected = [];
          this.batch.loading = false;

          break;
        default:
          break;
      }

      return request;
    },
  },
  beforeMount() {
    this.date = this.today.start;
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
      let qb = this.$store.getters.TIME_FOR_THE_DAY();
      qb = {
        start: qb.start.substring(0, qb.start.length - 9),
        end: qb.end.substring(0, qb.end.length - 9),
      };
      return qb;
    },
  },
  methods: {
    openReport() {
      let url = new URL(
        this.urlReport + "/pdf",
        window.location.protocol + window.location.hostname
      );
      let result = {
        people: this.shift.selected.map((shift) => shift.people.id),
        period: {
          start: this.dates[0] + "T" + this.time.start,
          end: this.dates[1] + "T" + this.time.end,
        },
        sqlWhere: this.$refs.qb.getQuery(),
        batch: this.batch.selected.map((batch) => batch.id),
      };
      if (this.typeReport == "shift") {
        const datesStartShift = this.shift.selected.map(
          (shift) => new Date(shift.start)
        );
        const datesStopShift = this.shift.selected.map((shift) =>
          !shift.stop ? new Date() : new Date(shift.stop)
        );

        result.period.start = this.$moment(
          Math.min.apply(null, datesStartShift)
        ).format();

        result.period.end = this.$moment(
          Math.max.apply(null, datesStopShift)
        ).format();
      } else if (this.typeReport == "batch") {
        // const datesStartBatch = this.batch.selected.map((batch) =>
        //   this.$moment(batch.firstDateTimberFormat, "DD-MM-YYYY hh:mm:ss")
        // );
        // const datesStopBatch = this.batch.selected.map((batch) =>
        //   !batch.lastDateTimberFormat
        //     ? this.$moment()
        //     : this.$moment(batch.lastDateTimberFormat, "DD-MM-YYYY hh:mm:ss")
        // );
        result.period = {
          start: this.$moment(this.batch.selected[0].firstDateTimberFormat, "DD-MM-YYYY hh:mm:ss").format(),
          end: this.$moment(this.batch.selected[0].lastDateTimberFormat, "DD-MM-YYYY hh:mm:ss").format(),
        }
        // result.period.start = this.$moment(
        //   Math.min.apply(null, datesStartBatch)
        // ).format();

        // result.period.end = this.$moment(
        //   Math.max.apply(null, datesStopBatch)
        // ).format();
        result.sqlWhere.push({
          id: "batch",
          logicalOperator: "AND",
          nameTable: "t.",
          operator: "=",
          value: this.batch.selected[0].id
          // value:
          //   "(" +
          //   result.batch.reduce(
          //     (stringIds, currentId) => currentId.toString() + "," + stringIds
          //   ) +
          //   ")",
        });
      }
      url.searchParams.append("period[start]", result.period.start);
      url.searchParams.append("period[end]", result.period.end);
      result.people.forEach((id) => {
        url.searchParams.append("people[]", id);
      });
      url.searchParams.append("sqlWhere", JSON.stringify(result.sqlWhere));
      window.open(url);
    },
    clickRowShift(item) {
      this.shift.selected.indexOf(item) == -1
        ? this.shift.selected.push(item)
        : this.shift.selected.splice(this.shift.selected.indexOf(item), 1);
    },
    dbClickShift(object, item) {
      this.shift.selected = [];
      this.shift.selected.push(item.item);
      this.openReport();
    },
    clickRowBatch(item) {
      if (this.batch.selected.indexOf(item) == -1) {
        this.batch.selected = [];
        this.batch.selected.push(item);
      } else this.batch.selected.splice(this.batch.selected.indexOf(item), 1);
    },
    dbClickBatch(object, item) {
      this.batch.selected = [];
      this.batch.selected.push(item.item);
      this.openReport();
    },
    selectTypeReport(type) {
      switch (type) {
        case 'batch':
          this.shift.selected = [];
          break;
        case 'shift':
          this.batch.selected = [];
          break;
        case 'period':
          this.batch.selected = [];
          this.shift.selected = [];
          break;
      }
      this.typeReport = type;
      this.el = 3;
    },
  },
};
</script>

<style>
</style>