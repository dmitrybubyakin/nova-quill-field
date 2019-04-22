<template>
    <div class="ql-editor">
        <div ref="editor"></div>
    </div>
</template>

<script>
import Quill from 'quill'
import debounce from 'lodash/debounce'

export default {
    props: {
        value: String,
        options: Object,
    },

    mounted () {
        this.create()

        this.initialize()
    },

    methods: {
        create (element) {
            this.editor = new Quill(this.$refs.editor, this.options || {})
        },

        initialize () {
            this.editor.clipboard.dangerouslyPasteHTML(this.value)

            this.editor.on('text-change', debounce(this.handleInput, 300))

            this.$watch('value', this.handleValueChange)

            this.$emit('ready', this.editor)
        },

        handleInput () {
            this.content = this.editor.getText() ? this.editor.root.innerHTML : ''

            this.$emit('input', this.content)
        },

        handleValueChange (value) {
            if (value !== this.content) {
                this.editor.clipboard.dangerouslyPasteHTML(value)
            }
        }
    }
}
</script>
