<template>
  <v-card :loading="loading">
    <v-card-text>
      <tlc-query-builder
        :rules="rules"
        v-if="visible"
        v-model="query"
        :maxDepth="1"
        @input="$emit('input', $event)"
      />
    </v-card-text>
  </v-card>
</template>

<script>
import tlcQueryBuilder from "builder-query-vuetify";
import { RuleTypes } from "builder-query-vuetify/src/utilities.js";
import Axios from "axios";

export default {
  name: "defaultTlcQueryBuilder",
  components: { tlcQueryBuilder },
  props: {
    value: {
      required: true,
    },
  },
  methods: {
    getQuery() {
      return this.query;
    },

    getRuleDowntimePlace() {
      let rule = {
        type: RuleTypes.MULTI_SELECT,
        id: "downtime_place",
        label: "Места простоя",
      };
      Axios.get(this.$store.state.apiEntryPoint + "/downtime_places")
        .then((response) => {
          let data = response.data["hydra:member"];
          rule.options = data.map((place) => {
            return {
              value: place.id,
              label: place.name,
            };
          });
          this.countLoadingRules++;
        })
        .catch((err) => {
          this.$snotify.error(err.data);
          this.$snotify.error("Ошибка при загрузке мест простоев");
          console.log(err);
        });
      return rule;
    },
    getRuleDowntimeCause() {
      let rule = {
        type: RuleTypes.MULTI_SELECT,
        id: "downtime_cause",
        label: "Причины простоя",
      };
      Axios.get(this.$store.state.apiEntryPoint + "/downtime_causes")
        .then((response) => {
          let data = response.data["hydra:member"];
          rule.options = data.map((cause) => {
            return {
              value: cause.id,
              label: cause.name,
            };
          });
          this.countLoadingRules++;
        })
        .catch((err) => {
          this.$snotify.error(err.data);
          this.$snotify.error("Ошибка при загрузке мест простоев");
          console.log(err);
        });
      return rule;
    },
    getRuleEventType() {
      let rule = {
        type: RuleTypes.MULTI_SELECT,
        id: "event_type",
        label: "Тип события",
      };
      Axios.get(this.$store.state.apiEntryPoint + "/event_types")
        .then((response) => {
          let data = response.data["hydra:member"];
          rule.options = data.map((type) => {
            return {
              value: type.id,
              label: type.name,
            };
          });
          this.countLoadingRules++;
        })
        .catch((err) => {
          this.$snotify.error(err.data);
          this.$snotify.error("Ошибка при загрузке тип событий");
          console.log(err);
        });
      return rule;
    },
    getRuleEventSource() {
      let rule = {
        type: RuleTypes.MULTI_SELECT,
        id: "event_source",
        label: "Истотчник события",
      };
      Axios.get(this.$store.state.apiEntryPoint + "/event_sources")
        .then((response) => {
          let data = response.data["hydra:member"];
          rule.options = data.map((source) => {
            return {
              value: source.id,
              label: source.name,
            };
          });
          this.countLoadingRules++;
        })
        .catch((err) => {
          this.$snotify.error(err.data);
          this.$snotify.error("Ошибка при загрузке тип событий");
          console.log(err);
        });
      return rule;
    },
    getRuleSpecies() {
      let rule = {
        type: RuleTypes.MULTI_SELECT,
        id: "species",
        label: "Породы",
      };
      Axios.get(this.$store.state.apiEntryPoint + "/species")
        .then((response) => {
          let data = response.data["hydra:member"];
          rule.options = data.map((specie) => {
            return {
              value: specie.id,
              label: specie.name,
            };
          });
          this.countLoadingRules++;
        })
        .catch((err) => {
          this.$snotify.error(err.data);
          this.$snotify.error("Ошибка при загрузке пород");
          console.log(err);
        });
      return rule;
    },
    getRulePostav() {
      let rule = {
        type: RuleTypes.MULTI_SELECT,
        id: "postav",
        label: "Постав",
      };
      Axios.get(this.$store.state.apiEntryPoint + "/postavs")
        .then((response) => {
          let data = response.data["hydra:member"];
          rule.options = data.map((postav) => {
            return {
              value: postav.id,
              label: postav.name,
            };
          });
          this.countLoadingRules++;
          // console.log(rule.options);
        })
        .catch((err) => {
          this.$snotify.error(err.data);
          this.$snotify.error("Ошибка при загрузке постава");
          console.log(err);
        });
      return rule;
    },
    getRuleDiam() {
      let rule = {
        type: RuleTypes.NUMBER,
        id: "diam",
        label: "Диаметр бревна, см. ",
      };
      this.countLoadingRules++;

      return rule;
    },
    getRulePostavDiam() {
      let rule = {
        type: RuleTypes.NUMBER,
        id: "diam_postav",
        label: "Диаметр постава, см. ",
      };
      this.countLoadingRules++;

      return rule;
    },
    getRuleCut() {
      let rule = {
        type: RuleTypes.TEXT,
        id: "diam",
        label: "Сечение",
      };
      this.countLoadingRules++;

      return rule;
    },
  },
  mounted() {
    this.rules.push(this.getRuleDowntimePlace());
    this.rules.push(this.getRuleDowntimeCause());
    this.rules.push(this.getRuleEventType());
    this.rules.push(this.getRuleEventSource());
    this.rules.push(this.getRuleSpecies());
    this.rules.push(this.getRulePostav());
    this.rules.push(this.getRuleDiam());
    this.rules.push(this.getRulePostavDiam());
    this.rules.push(this.getRuleCut());
  },
  watch: {
    countLoadingRules() {
      // conso
      if (this.rules.length === this.countLoadingRules) {
        this.visible = true;
        this.loading = false;
      } else {
        this.visible = false;
        this.loading = true;
        }
    },
  },
  computed: {},
  data() {
    return {
      query: this.value,
      loading: true,
      visible: false,
      rules: [],
      countLoadingRules: 0,
    };
  },
};
</script>

<style>
</style>