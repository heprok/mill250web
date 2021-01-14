<template>
  <v-container id="settings" fluid tag="section">
    <v-card>
      <v-tabs
        v-model="tab"
        background-color="primary accent-4"
        fixed-tabs
        dark
        icons-and-text
      >
        <v-tabs-slider></v-tabs-slider>

        <v-tab href="#tab-people">
          Персонал
          <v-icon>mdi-account-group</v-icon>
        </v-tab>

        <v-tab href="#tab-duty">
          Должности
          <v-icon>mdi-account-box</v-icon>
        </v-tab>
      </v-tabs>

      <v-tabs-items v-model="tab">
        <v-tab-item value="tab-people">
          <v-card flat>
            <v-data-table
              :headers="people.headers"
              :search="search"
              :loading="loading"
              loading-text="Загрузка... Ждите"
              :items="people.items"
              class="elevation-1"
            />
          </v-card>
        </v-tab-item>
        <v-tab-item value="tab-duty">
          <v-card flat>
            <crud-table
              title="Список должностей"
              url-api="api/duties"
              icon="mdi-account-box"
              :headers="duty.headers"
              is-crud
            />
          </v-card>
        </v-tab-item>
      </v-tabs-items>
    </v-card>
  </v-container>
</template>

<script>
import Axios from "axios";
export default {
  name: "settings",
  components: {},

  data() {
    return {
      tab: null,
      text: "",
      dialogAdded: false,
      dialogDelete: false,
      editedIndex: -1,
      editedItem: {},
      selectModel: [],
      defaultItem: {},
      people: {
        loading: false,
        search: "",
        editedItem: {},
        
        items: [],
        headers: [
          { text: "Фамилия", value: "fam" },
          { text: "Имя", value: "nam" },
          { text: "Отчество", value: "pat" },
          { text: "Действия", value: "actions", edited: false },
        ],
      },
      duty: {
        headers: [
          { text: "Код", value: "id" },
          { text: "Название", value: "name" },
          { text: "Действия", value: "actions", edited: false },
        ],
      },
    };
  },
  mounted() {},
  methods: {
    async updatePeople() {
      const request = await Axios.get("api/people");
      this.people.items = request.data["hydra:member"];
    },
    async deleteItem() {
      const request = await Axios.delete(this.editedItem["@id"]);
      return request;
    },
    async editItem() {
      const request = await Axios.put(this.editedItem["@id"], this.editedItem);
      return request;
    },
    async updateItems() {
      this.items = [];
      const config = {
        params: this.query,
      };
      const request = await Axios.get(this.entryPointApi + this.urlApi, config);
      this.items = request.data["hydra:member"];
      return request;
    },
    async addItem() {
      const request = await Axios.post(this.urlApi, this.editedItem);
      return request;
    },
  },
};
</script>
