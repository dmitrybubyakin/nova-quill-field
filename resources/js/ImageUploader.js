export default class ImageUploader {
    constructor (quill, options = {}) {
        this.quill = quill

        this.options = options

        this.quill
            .getModule('toolbar')
            .addHandler('image', this.handle.bind(this))

        this.quill.root.addEventListener('drop', this.handleDrop.bind(this), false)
    }

    async readFiles (files, handler) {
        files = [...files].filter(file => /^image\//.test(file.type))

        const dataUrls = await Promise.all(
            files.map(file => this.options.upload(file))
        )

        dataUrls.filter(Boolean).forEach(handler)
    }

    handle () {
        const input = document.createElement('input')

        input.type = 'file'
        input.accept = 'image/*'
        input.multiple = true
        input.click()

        input.onchange = () => {
            this.readFiles(input.files, dataUrl => this.insert(dataUrl))
        }
    }

    handleDrop (event) {
        event.preventDefault()

        if (event.dataTransfer && event.dataTransfer.files && event.dataTransfer.files.length) {
            if (document.caretRangeFromPoint) {
                const selection = document.getSelection()
                const range = document.caretRangeFromPoint(event.clientX, event.clientY)

                if (selection && range) {
                    selection.setBaseAndExtent(range.startContainer, range.startOffset, range.startContainer, range.startOffset)
                }
            }

            this.readFiles(event.dataTransfer.files, dataUrl => this.insert(dataUrl))
        }
    }

    insert (dataUrl) {
        const range = this.quill.getSelection()

        const index = range
            ? range.index
            : this.quill.getLength()

        this.quill.insertEmbed(index, 'image', dataUrl, 'user')
    }
}
