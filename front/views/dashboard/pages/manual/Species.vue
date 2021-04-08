<template>
  <v-container id="manual_species_dashboard" fluid tag="section">
    <v-row>
      <v-col cols="12">
        <crud-table
          title="Список пород"
          url-api="/species"
          icon="mdi-pine-tree"
          :headers="headers"
          isCheckPass
          is-crud
        >
          <template #[`item.fir`]="{ item }">
            <v-simple-checkbox v-model="item.fir" disabled />
          </template>

          <template #[`item.enabled`]="{ item }">
            <v-simple-checkbox v-model="item.enabled" disabled />
          </template>
        </crud-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "manual_species_dashboard",

  data() {
    return {
      headers: [
        {
          text: "id",
          align: "start",
          filterable: false,
          value: "id",
        },
        { text: "Название", value: "name" },
        { text: "Хвойное", type: "bool", value: "fir" },
        { text: "Включен", type: "bool", value: "enabled" },
        { text: "Действия", value: "actions", edited: false },
      ],
    };
  },
  mounted() {},
  methods: {},
};
</script>
