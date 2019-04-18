Nova.booting((Vue, router, store) => {
    Vue.component('index-nova-quill-field', require('./components/IndexField'))
    Vue.component('detail-nova-quill-field', require('./components/DetailField'))
    Vue.component('form-nova-quill-field', require('./components/FormField'))
})

import ImageUploader from './ImageUploader'

Nova.$once('nova-quill-field:loaded', Quill => {
    Quill.register('modules/imageUploader', ImageUploader)
})

import Fullscreen from './Fullscreen'

Nova.$once('nova-quill-field:loaded', Quill => {
    Quill.register('modules/fullscreen', Fullscreen)

    const icons = Quill.import('ui/icons')

    icons['fullscreen'] = require('!html-loader!../icons/fullscreen.svg')
    icons['fullscreenExit'] = require('!html-loader!../icons/fullscreen-exit.svg')
})
