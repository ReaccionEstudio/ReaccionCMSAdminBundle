/**
 * Entries class
 *
 * @author    Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */

import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import ReaccionCMSUploadPlugin from '../ckeditor/imageUpload/ReaccionCMSUploadPlugin.js';
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
        this._initEditor("textarea#entry_content");
        this._setEditorContentOnFormSubmit();

        let entryCategoryEvents = new EntryCategoryEvents()
        entryCategoryEvents.events()
    }

    /**
     * Initialize CKeditor
     *
     * @param    String        editorId    Textarea id attribute for CKeditor
     * @return    void        [type]
     */
    _initEditor(editorId) {
        ClassicEditor
            .create(
                document.querySelector(editorId), {
                    extraPlugins: [ReaccionCMSUploadPlugin]
                }
            )
            .then(editor => {
                this.editor = editor;
            })
            .catch(error => {
                console.error(error);
            });

        ClassicEditor.builtinPlugins.map( plugin => console.log(plugin.pluginName) );
    }

    /**
     * Set editor content in textarea when form has been submitted
     */
    _setEditorContentOnFormSubmit() {
        document.querySelector('#submit').addEventListener('click', () => {
            let ckeditorContent = this.editor.getData();
            console.log(ckeditorContent);
            $("textarea#entry_content").val(ckeditorContent);

            return true;
        });
    }
}

export default Entries;
