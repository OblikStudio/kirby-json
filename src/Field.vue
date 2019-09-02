<template>
  <k-json-editor
    v-model="data"
    :label="label"
    :options="options"
    @input="input"
  ></k-json-editor>
</template>

<script>
import { cloneDeep } from 'lodash-es'

export default {
  props: {
    value: Object,
    label: String,

    array: { // Saved value should be an array?
      default: false
    },
    keys: { // Edit object keys?
      default: false
    },
    values: { // Edit values?
      default: true
    },
    mutate: { // Add/remove entries?
      default: false
    },
    sort: { // Allow entry reordering?
      default: true
    }
  },
  data () {
    return {
      data: null,
      options: {
        isKeysEditable: this.keys,
        isValuesEditable: this.values,
        isMutatable: this.mutate,
        isSortable: this.sort
      }
    }
  },
  methods: {
    input () {
      this.$emit('input', this.data)
    }
  },
  watch: {
    value: {
      immediate: true,
      handler: function (value) {
        var isArray = Array.isArray(value)
        var isObject = (value && typeof value === 'object' && !isArray)

        if (this.array) {
          if (!isArray) {
            value = []
          }
        } else if (!isObject) {
          value = {}
        }

        this.data = cloneDeep(value)
      }
    }
  }
}
</script>
