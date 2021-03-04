<template>
  <v-container id="manual_standart_length_dashboard" fluid tag="section">
    <v-row>
      <v-col cols="12">
        <crud-table
          title="Список стандартных длин"
          url-api='/lengths'
          icon="mdi-format-line-spacing"
          :headers="headers"
          isCheckPass
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
        { text: "Стандартная длина", value: "standard"},
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