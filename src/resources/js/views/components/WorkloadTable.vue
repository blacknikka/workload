<template>
  <v-data-table
    v-model="selected"
    :headers="headers"
    :items="items"
    :pagination.sync="pagination"
    select-all
    item-key="name"
    class="elevation-1"
  >
    <template v-slot:headers="props">
      <tr>
        <th>
          <v-checkbox
            :input-value="props.all"
            :indeterminate="props.indeterminate"
            primary
            hide-details
            @click.stop="toggleAll"
          ></v-checkbox>
        </th>
        <th
          v-for="(header, index) in props.headers"
          :key="index"
          :class="['column sortable', pagination.descending ? 'desc' : 'asc', header.value === pagination.sortBy ? 'active' : '']"
          @click="changeSort(header.value)"
        >
          <v-icon small>arrow_upward</v-icon>
          {{ header.text }}
        </th>
      </tr>
    </template>
    <template v-slot:items="props">
      <tr
        :active="props.selected"
        @click="props.selected = !props.selected"
      >
        <td>
          <v-checkbox
            :input-value="props.selected"
            primary
            hide-details
          ></v-checkbox>
        </td>
        <td>{{ props.item.date }}</td>
        <td>{{ props.item.name }}</td>
        <td class="text-xs-right">{{ props.item.category }}</td>
        <td class="text-xs-right">{{ props.item.amount }}</td>
      </tr>
    </template>
  </v-data-table>
</template>

<script>
export default {
  data() {
    return {
      selected: [],
      headers: [
        { text: 'Date', value: 'date' },
        {
          text: 'Project',
          align: 'left',
          value: 'name'
        },
        { text: 'Category', value: 'category' },
        { text: 'Amount', value: 'amount' }
      ],
      items: [],
      pagination: {
        sortBy: 'name'
      }
    };
  },
  props: {
    list: {
      type: Array,
      required: true
    }
  },
  mounted() {
    this.list.forEach(item => {
      this.items.push({
        date: item.date,
        name: item.projectId,
        category: item.categoryId,
        amount: item.amount
      });
    });
  },
  methods: {
    toggleAll() {
      if (this.selected.length) this.selected = [];
      else this.selected = this.desserts.slice();
    },
    changeSort(column) {
      if (this.pagination.sortBy === column) {
        this.pagination.descending = !this.pagination.descending;
      } else {
        this.pagination.sortBy = column;
        this.pagination.descending = false;
      }
    }
  }
};
</script>
