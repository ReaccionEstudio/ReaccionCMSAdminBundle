/**
 * Entries class
 *
 * @author    Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */
//import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import ClassicEditor from '@ckeditor/ckeditor5-build-decoupled-document';
import {ENTRY_EDITOR_CONFIG} from './EntryEditorConfig'
import EntryCategoryEvents from './EntryCategoryEvents'

/**
 * ReaccionCMSUploadAdapter plugin
 */

class Entries {
    /**
     * Entry form events
     */
    formEvents() {
        this.editor = null;
        this.initEditorValue = $("textarea#entry_content").val()
        this._initEditor("#editor");
        this._setEditorContentOnFormSubmit();

        let entryCategoryEvents = new EntryCategoryEvents()
        entryCategoryEvents.events()
    }

    /**
     * Initialize CKeditor
     *
     * @param    string      editorId    Textarea id attribute for CKeditor
     * @return   void        [type]
     */
    _initEditor(editorId) {
        ClassicEditor
            .create(document.querySelector(editorId), ENTRY_EDITOR_CONFIG)
            .then(editor => {
                document.querySelector('#toolbar-container').appendChild( editor.ui.view.toolbar.element );
                this.editor = editor;
                this.editor.data.set(this.initEditorValue)
            })
            .catch(error => { console.error(error); });


    }

    /**
     * Set editor content in textarea when form has been submitted
     */
    _setEditorContentOnFormSubmit() {
        document.querySelector('#submit').addEventListener('click', () => {
            let ckeditorContent = this.editor.getData();
            console.log(ckeditorContent)
            $("textarea#entry_content").val(ckeditorContent);

            return true;
        });
    }
}

export default Entries;
