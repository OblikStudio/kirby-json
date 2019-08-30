<template>
  <tfoot class="k-je-foot">
    <tr>
      <td class="k-structure-table-index"></td>

      <td :colspan="colspan">
        <p class="k-structure-table-text">
          <k-input
            v-model="type"
            :options="[
              { value: 'object', text: $t('Object') },
              { value: 'array', text: $t('Array') },
              { value: 'value', text: $t('Value') }
            ]"
            type="radio"
          />
        </p>
      </td>

      <td class="k-structure-table-option">
        <k-button
          :tooltip="$t('add')"
          icon="add"
          @click="$emit('add', type)"
        />
      </td>
    </tr>
  </tfoot>
</template>

<script>
export default {
  props: {
    colspan: {
      type: Number,
      default: 1
    }
  },
  data: function () {
    return {
      type: 'value'
    }
  }
}
</script>
