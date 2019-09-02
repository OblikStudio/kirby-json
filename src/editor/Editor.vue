<template>
  <k-field>
    <header slot="header" class="k-field-header">
      <label class="k-field-label" @click.prevent>
        <template v-for="entry in displayPath">
          <div class="k-je-crumb" :key="entry.index">
            <template v-if="entry.index < path.length - 1">
              <button class="k-je-label-button" @click="openPath(entry.index)">{{ entry.name }}</button>
              <span class="k-je-separator">/</span>
            </template>
            <span v-else>{{ entry.name }}</span>
          </div>
        </template>
      </label>
    </header>

    <component
      v-if="!isEmpty(object) || settings.isMutatable"
      :is="isArray(object) ? 'ArrayTable' : 'ObjectTable'"
      :settings="settings"
      v-model="object"
      @input="input"
      @open="openKey"
    ></component>
    <k-box
      v-else
      theme="info"
      :text="$t('oblik.json.message.empty')"
    />
  </k-field>
</template>

<script>
import { cloneDeep, get } from 'lodash-es'
import ObjectTable from './ObjectTable.vue'
import ArrayTable from './ArrayTable.vue'

export default {
  components: {
    ObjectTable,
    ArrayTable
  },
  props: {
    value: Object,
    options: Object,
    label: {
      default: 'Root'
    }
  },
  data: function () {
    return {
      data: null,
      path: [],
      settings: Object.assign(
        {},
        {
          isKeysEditable: true,
          isValuesEditable: true,
          isMutatable: true,
          isSortable: true
        },
        this.options
      )
    }
  },
  computed: {
    location: function () {
      var key = null
      var parent = null
      var object = this.data

      for (let i = 0; i < this.path.length; i++) {
        key = this.path[i]
        parent = object

        if (object[key]) {
          object = object[key]
        } else {
          this.path.splice(i)
          break
        }
      }

      return (key && parent) ? { key, parent } : null
    },
    object: {
      get () {
        if (this.location) {
          return this.location.parent[this.location.key]
        } else {
          return this.data
        }
      },
      set (value) {
        if (this.location) {
          this.location.parent[this.location.key] = value
        } else {
          this.data = value
        }
      }
    },
    displayPath: function () {
      var path = this.path.map((name, index) => {
        return {
          name: name,
          index: index
        }
      })

      path.unshift({
        name: this.label,
        index: -1
      })

      return path
    }
  },
  methods: {
    isArray: Array.isArray,
    openKey (key) {
      this.path.push(key)
    },
    openPath (index) {
      this.path.splice(index + 1)
    },
    input (value) {
      this.$emit('input', this.data)
    },
    isEmpty (value) {
      if (value && typeof value === 'object') {
        return !Object.keys(value).length
      } else {
        return null
      }
    }
  },
  watch: {
    value: {
      immediate: true,
      handler (value) {
        this.data = cloneDeep(value)
      }
    }
  }
};
</script>

<style>
.k-je-column-header-key {
  width: 25%;
}

.k-je-crumb {
  display: inline-block;
}

  .k-je-separator {
    content: '/';
    margin: 0 8px;
    font-weight: lighter;
  }

.k-je-not-editable {
  cursor: default;
  color: #777;
}

.k-je-not-allowed {
  cursor: not-allowed;
}
</style>
