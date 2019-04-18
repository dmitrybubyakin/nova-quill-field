import noScroll from 'no-scroll'

export default class Fullscreen {
    constructor (quill, options = {}) {
        this.quill = quill

        this.options = options

        this.fullscreen = false
    }

    handleFullscreen () {
        if (! this.fullscreen) {
            this.options.change(this.fullscreen = true)
            noScroll.on()
        }
    }

    handleFullscreenExit () {
        if (this.fullscreen) {
            this.options.change(this.fullscreen = false)
            noScroll.off()
        }
    }
}
