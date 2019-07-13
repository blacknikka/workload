<template>
  <v-data-table
    v-model="selected"
    :headers="headers"
    :items="getWorkloadList"
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
          v-for="header in props.headers"
          :key="header.text"
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
        @click="itemClicked(props)"
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
      pagination: {
        sortBy: 'name'
      }
    };
  },
  props: {
    workloads: {
      type: Array,
      required: true
    }
  },
  methods: {
    toggleAll() {
      if (this.selected.length) {
        this.selected = [];
      } else {
        this.selected = this.workloads.slice();
      }
    },
    changeSort(column) {
      if (this.pagination.sortBy === column) {
        this.pagination.descending = !this.pagination.descending;
      } else {
        this.pagination.sortBy = column;
        this.pagination.descending = false;
      }
    },
    itemClicked(props) {
      if (props.selected === true) {
        this.selected.push(this.workloads[props.index]);
      } else {
        this.selected.splice(props.index);
      }
    }
  },
  computed: {
    getWorkloadList() {
      return this.workloads.map(item => {
        return {
          date: item.date,
          name: item.projectId,
          category: item.categoryId,
          amount: item.amount
        };
      });
    }
  }
};
</script>
