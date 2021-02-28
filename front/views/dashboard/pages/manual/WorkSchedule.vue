<template>
  <v-container id="manual_work_schedule_dashboard" fluid tag="section">
    <v-card>
      <v-tabs v-model="tab" background-color="primary" centered icons-and-text>
        <v-tabs-slider></v-tabs-slider>

        <v-tab href="#tab-break-shift">
          Смены
          <v-icon>mdi-account-group</v-icon>
        </v-tab>

        <v-tab href="#tab-break-shedule">
          Перерывы
          <v-icon>mdi-account-box</v-icon>
        </v-tab>
      </v-tabs>

      <v-tabs-items v-model="tab">
        <v-tab-item value="tab-break-shift">
          <v-row>
            <v-col cols="12">
              <crud-table
                title="График смен"
                icon="mdi-account-group"
                url-api="/shift_shedules"
                :headers="shiftSchedule.headers"
                is-crud
                isCheckPass
              >
              </crud-table>
            </v-col>
          </v-row>
        </v-tab-item>
        <v-tab-item value="tab-break-shedule">
          <v-row>
            <v-col cols="12">
              <crud-table
                title="Расписание перерывов"
                url-api="/break_shedules"
                icon="mdi-chart-timeline"
                :headers="breakShedules.headers"
                :itemsSelect="breakShedules.itemsSelect"
                is-crud
                isCheckPass
              >
                              <!-- <template #[`item.cause`]="{ item }">
                  {{ item.cause.name }}
                </template>
                                <template #[`item.place`]="{ item }">
                  {{ item.place.name }} -->
                <!-- </template> -->
              </crud-table>
            </v-col>
          </v-row>
        </v-tab-item>
      </v-tabs-items>
    </v-card>
  </v-container>
</template>

<script>
import Axios from "axios";

export default {
  name: "manual_work_schedule_dashboard",

  data() {
    return {
      tab: null,
      breakShedules: {
        headers: [
          { text: "Начало", type: "time", value: "startTime" },
          { text: "Конец", type: "time", value: "stopTime" },
          {
            text: "Причина",
            value: "cause.name",
            type: "select",
            itemSelect: "cause",
          },
          {
            text: "Место",
            value: "place.name",
            type: "select",
            itemSelect: "place",
          },
          { text: "Действия", value: "actions", edited: false },
        ],
        loadingSelect: false,
        itemsSelect: {
          place: null,
          cause: null,
        },
      },
      shiftSchedule: {
        headers: [
          { text: "Начало", type: "time", value: "startTime" },
          { text: "Конец", type: "time", value: "stopTime" },
          { text: "Описание", value: "name" },
          { text: "Действия", value: "actions", edited: false },
        ],
        loadingSelect: false,
      },
    };
  },
  watch: {},
  computed: {},
  async mounted() {
    await this.getItemsPlace();
    await this.getItemsCause();
  },
  methods: {
    getItemsPlace() {
      this.breakShedules.itemsSelect.place = {};
      Axios.get(this.$store.state.apiEntryPoint + "/downtime_places")
        .then((response) => {
          console.log(response);
          let data = response.data["hydra:member"];
          this.breakShedules.itemsSelect.place = data.map((place) => {
            return {
              value: place["@id"],
              text: place.name,
            };
          });
          this.breakShedules.loadingSelect = true;
        })
        .catch((err) => {
          this.$snotify.error(err.data);
          this.$snotify.error("Ошибка при загрузке мест");
          console.log(err);
        });
      return null;
    },    
    getItemsCause() {
      this.breakShedules.itemsSelect.cause = {};
      Axios.get(this.$store.state.apiEntryPoint + "/downtime_causes")
        .then((response) => {
          let data = response.data["hydra:member"];
          this.breakShedules.itemsSelect.cause = data.map((cause) => {
            return {
              value: cause["@id"],
              text: cause.name,
            };
          });
          this.breakShedules.loadingSelect = true;
        })
        .catch((err) => {
          this.$snotify.error(err.data);
          this.$snotify.error("Ошибка при загрузке причин");
          console.log(err);
        });
      return null;
    },
  },
};
</script>