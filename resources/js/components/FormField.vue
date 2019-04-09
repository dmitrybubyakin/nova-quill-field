<template>
    <default-field :field="field" :errors="errors" :full-width-content="true">
        <template slot="field">
            <quill-editor
                class="border border-60 rounded-lg"
                v-model="value"
                :id="field.attribute"
                :class="errorClasses"
                :options="options"
                :placeholder="field.name"
            />
        </template>
    </default-field>
</template>

<script>
import objectToFormData from 'object-to-formdata'
import { FormField, HandlesValidationErrors, Errors } from 'laravel-nova'
import { quillEditor, Quill } from 'vue-quill-editor'
import 'quill/dist/quill.core.css'
import 'quill/dist/quill.snow.css'

import ImageUploader from '../ImageUploader'
Quill.register('modules/imageUploader', ImageUploader)

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    components: {
        quillEditor,
    },

    computed: {
        options () {
            const vm = this

            return {
                placeholder: this.field.placeholder || this.field.name,
                modules: {
                    toolbar: {
                        container: this.field.toolbar || [
                            [{'header': 1}, {'header': 2}],
                            [{'align': ''}, {'align': 'center'}, {'align': 'right'}, {'align': 'justify'}],
                            ['bold', 'italic', 'underline', 'strike'],
                            ['blockquote'],
                            [{'list': 'ordered'}, {'list': 'bullet'}],
                            ['clean'],
                            ['link', 'image', 'video']
                        ]
                    },
                    imageUploader: {
                        async upload (file) {
                            const data = objectToFormData({
                                file: file,
                                attribute: vm.field.attribute,
                                resource: vm.resourceName,
                                resourceId: vm.resourceId,
                                ...vm.field.imageUploadParameters,
                            })

                            try {
                                const { data: { dataUrl }} = await Nova.request().post('/nova-vendor/nova-quill-field', data)

                                return dataUrl
                            } catch (error) {
                                const errors = new Errors(error.response.data.errors)

                                Nova.$emit('error', errors.first('file'))

                                throw error
                            }
                        }
                    }
                }
            }
        }
    },

    mounted () {
        this.$el.addEventListener('keydown', this.handleKeydown)
    },

    beforeDestroy () {
        this.$el.removeEventListener('keydown', this.handleKeydown)
    },

    methods: {
        handleKeydown (event) {
            // fix https://github.com/laravel/nova-issues/issues/998
            if (event.keyCode == 191) {
                event.stopPropagation()

                return false
            }
        },

        setInitialValue () {
            this.value = this.field.value || ''
        },

        fill (formData) {
            formData.append(this.field.attribute, this.value || '')
        },

        handleChange (value) {
            this.value = value
        },
    },
}
</script>

<style lang="scss">
.ql-container {
    height: 500px;
}

.ql-container,
.ql-toolbar {
    border: none !important;
}

.ql-toolbar {
    display: flex;
    justify-content: center;
    padding: 1.5rem !important;
}
</style>
