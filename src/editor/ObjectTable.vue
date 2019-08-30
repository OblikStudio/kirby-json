<template>
  <div>
    <table
      class="k-structure-table"
      :data-sortable="settings.isSortable ? 'true' : false"
    >
      <thead v-if="list.length">
        <tr>
          <th class="k-structure-table-index">#</th>
          <th class="k-structure-table-column k-je-column-header-key">{{ $t('key') }}</th>
          <th class="k-structure-table-column">{{ $t('value') }}</th>
          <th></th>
        </tr>
      </thead>

      <k-draggable
        element="tbody"
        :list="list"
        :handle="true"
        :options="dragOptions"
      >
        <tr v-for="(entry, index) in list" :key="index">
          <td class="k-structure-table-index">
            <k-sort-handle v-if="settings.isSortable" />
            <span class="k-structure-table-index-number">
              {{ index + 1 }}
            </span>
          </td>

          <td class="k-structure-table-column">
            <p class="k-structure-table-text" :class="{
              'k-je-not-editable': !settings.isKeysEditable
            }">
              <k-input v-if="settings.isKeysEditable" v-model="entry.key" name="text" type="text" />
              <span v-else>{{ entry.key }}</span>
            </p>
          </td>

          <td class="k-structure-table-column">
            <p class="k-structure-table-text" :class="{
              'k-je-not-editable': !settings.isValuesEditable
            }">
              <template v-if="!entry.value || typeof entry.value !== 'object'">
                <k-input v-if="settings.isValuesEditable" v-model="entry.value" name="text" type="text" />
                <span v-else>{{ entry.value }}</span>
              </template>
              <k-button v-else icon="open" @click="$emit('open', entry.key)">
                {{ $t(isArray(entry.value) ? 'array' : 'object') }}
                ({{ keys(entry.value).length }} {{ $t('values') }})
              </k-button>
            </p>
          </td>

          <td class="k-structure-table-option">
            <k-button
              :tooltip="$t('remove')"
              icon="remove"
              @click="remove(entry.key)"
              :class="{
                'k-je-not-allowed': !settings.isMutatable
              }"
            />
          </td>
        </tr>
      </k-draggable>

      <Foot v-if="settings.isMutatable" @add="add" :colspan="2"></Foot>
    </table>
  </div>
</template>

<script>
import ArrayTable from './ArrayTable.vue'

export default {
  extends: ArrayTable,
  methods: {
    generateEntryId: function () {
      return 'key' + (Math.floor(Math.random() * 9000) + 1000)
    },
    serialize: function (type) {
      var serialized = {}

      this.list.forEach(function (entry) {
        if (typeof entry.key !== 'undefined') {
          serialized[entry.key] = entry.value
        }
      })

      return serialized
    }
  }
}
</script>
