<template>
  <v-container id="dashboard" fluid tag="section">
    <v-row>
      <v-col
        cols="12"
        sm="6"
        lg="3"
        v-for="infoCard in infoCards"
        :key="infoCard.nameCard"
      >
        <info-card
          :color="infoCard.color"
          :icon="infoCard.icon"
          :sub-icon="infoCard.subIcon"
          :subText="infoCard.subText"
          :title="infoCard.nameCard"
          :urlApi="infoCard.urlApi"
          :suffix="infoCard.suffix"
          :durations="infoCard.duration"
        />
      </v-col>
    </v-row>
    <!-- <v-row>
      <v-col cols="6" lg="6" sm="12">

          <base-material-chart-card
          color="#000"
          data="[2, 3, 4]"
          hover-reveal
          type="Line"
          url="api/dashboard/report/volumeboard/chart"
        >
          <template v-slot:reveal-actions>
            <v-tooltip bottom>
              <template v-slot:activator="{ attrs, on }">
                <v-btn v-bind="attrs" color="info" icon v-on="on">
                  <v-icon color="info">mdi-refresh</v-icon>
                </v-btn>
              </template>

              <span>Refresh</span>
            </v-tooltip>

            <v-tooltip bottom>
              <template v-slot:activator="{ attrs, on }">
                <v-btn v-bind="attrs" light icon v-on="on">
                  <v-icon>mdi-pencil</v-icon>
                </v-btn>
              </template>

              <span>Change Date</span>
            </v-tooltip>
          </template>

          <h4 class="card-title font-weight-light mt-2 ml-2">Объем досок</h4>

          <p class="d-inline-flex font-weight-light ml-2 mt-1">За последние 30 дней</p>
          <template v-slot:actions>
            <v-icon
              class="mr-1"
              small
            >
              mdi-clock-outline
            </v-icon>
            <span class="caption grey--text font-weight-light">updated 10 minutes ago</span>
          </template>
        </base-material-chart-card>
      </v-col>

      <v-col cols="6" lg="6" sm="12">
        <base-material-chart-card
          color="#000"
          hover-reveal
          type="Bar"
          url="api/dashboard/report/diffboardontimber/chart"
        >
          <template v-slot:reveal-actions>
            <v-tooltip bottom>
              <template v-slot:activator="{ attrs, on }">
                <v-btn v-bind="attrs" color="info" icon v-on="on">
                  <v-icon color="info">mdi-refresh</v-icon>
                </v-btn>
              </template>

              <span>Refresh</span>
            </v-tooltip>

            <v-tooltip bottom>
              <template v-slot:activator="{ attrs, on }">
                <v-btn v-bind="attrs" light icon v-on="on">
                  <v-icon>mdi-pencil</v-icon>
                </v-btn>
              </template>

              <span>Change Date</span>
            </v-tooltip>
          </template>

          <h4
            class="card-title font-weight-light mt-2 ml-2"
          >Соотношение между выпеленных досок с сравнением с бревном</h4>
        </base-material-chart-card>
      </v-col>
    </v-row> -->
  </v-container>
</template>

<script>
// import LineChart from '../../components/base/chart/report/dashboard/VolumeBoardChart.js';
// import LineChartCard from '../../components/base/ChartJsCard.vue';
export default {
  name: "DashboardDashboard",
  data() {
    return {
      loader: false,
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
          nameCard: "Объем брёвна",
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
        {
          nameCard: "Кол-во пил-мат",
          color: "info",
          icon: "mdi-poll",
          urlApi: "/infocard/countBoard",
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
          nameCard: "Объем пил-мат",
          color: "info",
          icon: "mdi-poll",
          urlApi: "/infocard/volumeBoard",
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
          nameCard: "Скорость загрузки",
          color: "info",
          icon: "mdi-speedometer",
          suffix: 'м/мин',
          urlApi: "/infocard/vars/LoadinSpeed",
        },
        {
          nameCard: "Дистанция на загрузке",
          color: "info",
          icon: "mdi-ray-start-end",
          suffix: 'м',
          urlApi: "/infocard/vars/LoadingDistance"
        },
        {
          nameCard: "Скорость линии",
          color: "info",
          icon: "mdi-speedometer",
          suffix: 'м/мин',
          urlApi: "/infocard/vars/LineSpeed",
        },
        {
          nameCard: "Дистанция на линии",
          color: "info",
          icon: "mdi-ray-start-end",
          suffix: 'м',
          urlApi: "/infocard/vars/LineDistance",
        },
        {
          nameCard: "Текущая смена",
          color: "info",
          subIcon: "mdi-tag",
          icon: "mdi-account-hard-hat",
          urlApi: "/infocard/currentShift",
        },
        {
          nameCard: "Последний простой",
          color: "orange",
          icon: "mdi-clock-fast",
          subIcon: "mdi-clock",
          urlApi: "/infocard/lastDowntime",
        },
      ],
    };
  },
  mounted() {},
  methods: {
    complete(index) {
      this.list[index] = !this.list[index];
    },
  },
};
</script>
