Nova.booting((Vue, router, store) => {
    Vue.component('index-nova-quill-field', require('./components/IndexField'))
    Vue.component('detail-nova-quill-field', require('./components/DetailField'))
    Vue.component('form-nova-quill-field', require('./components/FormField'))
})
