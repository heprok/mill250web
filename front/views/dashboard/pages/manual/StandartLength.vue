<template>
  <v-container id="manual_standart_length_dashboard" fluid tag="section">
    <v-row>
      <v-col cols="12">
        <crud-table
          title="Список людей"
          url-api="api/lengths"
          icon="mdi-format-line-spacing"
          :headers="headers"
          is-crud
        >
        </crud-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "manual_standart_length_dashboard",

  data() {
    return {
      headers: [
        { text: "Стандартная длина", value: "standard", edited: false },
        { text: "Мин. граница", value: "minimum" },
        { text: "Макс. граница", value: "maximum" },
        { text: "Действия", value: "actions", edited: false },
      ],
    };
  },
  mounted() {},
  methods: {},
};
</script>