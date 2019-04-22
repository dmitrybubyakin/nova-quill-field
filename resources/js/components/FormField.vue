<template>
    <default-field :field="field" :errors="errors" :full-width-content="true">
        <template slot="field">
            <div :class="{ fullscreen }">
                <Editor
                    class="fullscreen-editor border border-60 rounded-lg"
                    v-model="value"
                    :id="field.attribute"
                    :class="[...errorClasses, editorHeightClass]"
                    :options="options"
                    @ready="handleReady"
                    @input="autosave"
                />
            </div>
        </template>
    </default-field>
</template>

<script>
import debounce from 'lodash/debounce'
import objectToFormData from 'object-to-formdata'
import { FormField, HandlesValidationErrors, Errors } from 'laravel-nova'
import Quill from 'quill'
import 'quill/dist/quill.core.css'
import 'quill/dist/quill.snow.css'
import Editor from './Editor'

Nova.$emit('nova-quill-field:loaded', Quill)

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    components: {
        Editor,
    },

    data () {
        return {
            fullscreen: false,
        }
    },

    computed: {
        options () {
            const vm = this

            return {
                placeholder: this.field.placeholder || this.field.name,
                theme: 'snow',
                modules: {
                    fullscreen: {
                        change (fullscreen) {
                            vm.fullscreen = fullscreen
                        }
                    },
                    toolbar: {
                        container: this.field.toolbar || [
                            [{'header': 1}, {'header': 2}],
                            [{'align': ''}, {'align': 'center'}, {'align': 'right'}, {'align': 'justify'}],
                            ['bold', 'italic', 'underline', 'strike'],
                            ['blockquote'],
                            [{'list': 'ordered'}, {'list': 'bullet'}],
                            ['clean'],
                            ['link', 'image', 'video'],
                            ['fullscreen', 'fullscreenExit'],
                        ],
                        handlers: {
                            fullscreen () {
                                this.quill.getModule('fullscreen').handleFullscreen()
                            },

                            fullscreenExit () {
                                this.quill.getModule('fullscreen').handleFullscreenExit()
                            }
                        }
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
                                const { data: { dataUrl }} = await Nova.request().post('/nova-vendor/nova-quill-field/attachment', data)

                                return dataUrl
                            } catch (error) {
                                const errors = new Errors(error.response.data.errors)

                                Nova.$emit('error', errors.first('file'))
                            }
                        }
                    }
                }
            }
        },

        editorHeightClass () {
            const height = this.field.height || 400

            return `ql-container-h-${height}`
        }
    },

    mounted () {
        this.$el.addEventListener('keydown', this.handleKeydown)
    },

    beforeDestroy () {
        this.$el.removeEventListener('keydown', this.handleKeydown)
    },

    methods: {
        handleReady (quill) {
            Nova.$emit('nova-quill-field:ready', { field: this.field, quill })
        },

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

        autosave: debounce(function () {
            if (this.field.autosave) {
                Nova.request().post('/nova-vendor/nova-quill-field/save', {
                    value: this.value,
                    attribute: this.field.attribute,
                    resource: this.resourceName,
                    resourceId: this.resourceId,
                })
            }
        }, 1000)
    },
}
</script>

<style lang="scss">
.fullscreen {
    background: white;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    z-index: 100000;

    .fullscreen-editor {
        border-radius: 0;
        border: none;
    }

    .ql-container {
        height: calc(100vh - 3rem - 24px) !important; // 24px is the height of the tooltip button. 3rem is the padding
        max-width: 1024px;
        margin: 0 auto;
        overflow-y: auto;
    }
}

// height: ql-container-200 ... ql-container-700;
@for $height from 2 through 7 {
    .ql-container-h-#{$height * 100} {
        .ql-container {
            height: 100px * $height;
        }
    }
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
