import Editor from './editor/Editor.vue'
import Field from './Field.vue'

panel.plugin('oblik/json', {
  components: {
    'k-json-editor': Editor
  },
  fields: {
    json: Field
  }
})
