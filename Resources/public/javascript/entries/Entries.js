/**
 * Entries class
 *
 * @author    Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */

import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import ReaccionCMSUploadAdapter from '../ckeditor/imageUpload/ReaccionCMSUploadAdapter.js';
import EntryCategoryEvents from './EntryCategoryEvents'

/**
 * ReaccionCMSUploadAdapter plugin
 */
function ReaccionCMSUploadAdapterPlugin(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {

        let ImageUploadRoute = Routing.generate('reaccion_cms_admin_media_image_upload');
        return new ReaccionCMSUploadAdapter(loader, 'http://localhost:8000/admin/media/image/upload');

    };
}

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
                    extraPlugins: [ReaccionCMSUploadAdapterPlugin]
                }
            )
            .then(editor => {
                this.editor = editor;
            })
            .catch(error => {
                console.error(error);
            });
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
