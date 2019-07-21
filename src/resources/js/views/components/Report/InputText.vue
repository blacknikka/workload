<template>
  <v-flex xs12>
    <v-textarea
      name="input-7-1"
      :label="description"
      value=""
      v-on:input="inputChanged"
    ></v-textarea>
  </v-flex>
</template>

<script>
export default {
  props: {
    description: {
      type: String,
      required: true,
    }
  },
  data() {
    return {
      textInput: '',
    }
  },
  methods: {
    inputChanged(updatedText) {
      this.$emit('input', updatedText);
    }
  },
};
</script>
