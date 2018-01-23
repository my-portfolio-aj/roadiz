import './scss/styles.scss'
import './less/vendor.less'
import './less/style.less'

// Include bower dependencies
import '../../bower_components/CanvasLoader/js/heartcode-canvasloader'
import '../../bower_components/jquery.actual/jquery.actual'
import '../../bower_components/jquery-tag-editor/jquery.tag-editor'
import '../../bower_components/bootstrap-switch/dist/js/bootstrap-switch'
import '../../bower_components/mousetrap/mousetrap'
import '../../bower_components/caret/jquery.caret.js'
import '../../bower_components/jquery-minicolors/jquery.minicolors.js'

import UIkit from '../../bower_components/uikit/js/uikit'
import '../../bower_components/uikit/js/components/nestable'
import '../../bower_components/uikit/js/components/sortable.js'
import '../../bower_components/uikit/js/components/datepicker.js'
import '../../bower_components/uikit/js/components/pagination.js'
import '../../bower_components/uikit/js/components/notify.js'
import '../../bower_components/uikit/js/components/tooltip.js'

import CodeMirror from 'codemirror'
import 'codemirror/mode/markdown/markdown.js'
import 'codemirror/mode/javascript/javascript.js'
import 'codemirror/mode/css/css.js'
import 'codemirror/addon/mode/overlay.js'
import 'codemirror/mode/xml/xml.js'
import 'codemirror/mode/yaml/yaml.js'
import 'codemirror/mode/gfm/gfm.js'

import 'jquery-ui'
import 'jquery-ui/ui/widgets/autocomplete'
import './components/login/login'

import $ from 'jquery'
import Rozier from './Rozier'
import GeotagField from './widgets/GeotagField'
import MultiGeotagField from './widgets/MultiGeotagField'
import Api from './api'
import Promise from 'bluebird'

// Translations
import Translator from 'bazinga-translator'

// Set globals
window.CodeMirror = CodeMirror
window.UIkit = UIkit

// Config Translator
Translator.locale = window.temp.locale || 'fr'

// eslint-disable-next-line
window.initializeGeotagFields = () => {
    window.Rozier.gMapLoaded = true
    window.Rozier.gMapLoading = false

    /* eslint-disable no-new */
    new GeotagField()
    new MultiGeotagField()
}

/*
 * ============================================================================
 * Rozier entry point
 * ============================================================================
 */
window.Rozier = new Rozier()

/*
 * ============================================================================
 * Plug into jQuery standard events
 * ============================================================================
 */
const onLoad = () => {
    return new Promise(resolve => {
        $(document).ready(() => {
            resolve()
        })
    })
}

// Waiting for translations and document ready
Promise
    .all([onLoad(), Api.getTranslations()])
    .then(results => {
        // Set translations
        if (results[1]) {
            Translator.fromJSON(results[1])
        }

        // Build Rozier
        window.Rozier.onDocumentReady()
    })
    .catch(err => console.error(err.message))
