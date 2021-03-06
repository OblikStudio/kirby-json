<template>
  <div>
    <table
      class="k-structure-table"
      :data-sortable="settings.isSortable ? 'true' : false"
    >
      <thead v-if="list.length">
        <tr>
          <th class="k-structure-table-index">#</th>
          <th class="k-structure-table-column">{{ $t('oblik.json.value') }}</th>
          <th></th>
        </tr>
      </thead>

      <k-draggable
        element="tbody"
        :list="list"
        :handle="true"
        :options="dragOptions"
      >
        <tr v-for="(entry, index) in list" :key="entry.key">
          <td class="k-structure-table-index">
            <k-sort-handle v-if="settings.isSortable" />
            <span class="k-structure-table-index-number">{{ index }}</span>
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
                {{ $t('oblik.json.' + (isArray(entry.value) ? 'array' : 'object')) }}
                ({{ keys(entry.value).length }} {{ $t('oblik.json.values') }})
              </k-button>
            </p>
          </td>

          <td class="k-structure-table-option">
            <k-button
              icon="remove"
              :class="{
                'k-je-not-allowed': !settings.isMutatable
              }"
              @click="remove(entry.key)"
            />
          </td>
        </tr>
      </k-draggable>

      <Foot v-if="settings.isMutatable" @add="add"></Foot>
    </table>
  </div>
</template>

<script>
import Foot from './Foot.vue'

export default {
  components: {
    Foot
  },
  props: {
    value: Object,
    settings: Object
  },
  data () {
    return {
      list: null,
      updatingList: false
    }
  },
  computed: {
    dragOptions () {
      return {
        disabled: !this.settings.isSortable,
        fallbackClass: 'k-sortable-row-fallback'
      }
    }
  },
  methods: {
    keys: Object.keys,
    isArray: Array.isArray,
    generateEntryId () {
      return this.list.length
    },
    serialize () {
      return this.list.map(entry => entry.value)
    },
    updateList (object) {
      this.updatingList = true
      this.list = []

      if (this.value && typeof this.value === 'object') {
        this.list = Object.keys(this.value).map(key => {
          return {
            key: key,
            value: this.value[key]
          }
        })
      }

      this.$nextTick(() => {
        this.updatingList = false
      })
    },
    add (type) {
      var entry = {
        key: this.generateEntryId()
      }

      switch (type) {
        case 'value': entry.value = 'Value'; break
        case 'array': entry.value = []; break
        case 'object': entry.value = {}; break
      }

      if (this.settings.isMutatable) {
        this.list.push(entry)
      }
    },
    remove (key) {
      if (this.settings.isMutatable) {
        this.list = this.list.filter(item => item.key !== key)
      }
    }
  },
  watch: {
    value: {
      deep: true,
      immediate: true,
      handler (value) {
        this.updateList(value)
      }
    },
    list: {
      deep: true,
      handler (value) {
        if (!this.updatingList) {
          this.$emit('input', this.serialize())
        }
      }
    }
  }
}
</script>
