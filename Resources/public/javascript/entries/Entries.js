/**
 * Entries class
 *
 * @author    Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */

import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import ReaccionCMSUploadAdapter from '../ckeditor/imageUpload/ReaccionCMSUploadAdapter.js';
import MediaGalleryFormComponent from '../components/media/MediaGalleryFormComponent';

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
        this._toggleFormCategories();
        this._handleLanguageChangeEvent();
        this._defaultImageEvents();
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

    _defaultImageEvents() {
        let mediaGalleryFormComponent = new MediaGalleryFormComponent();

        $('[data-event="open-media-gallery"]').on("click", function () {
            console.log('click!')
            // Show media gallery modal box
            mediaGalleryFormComponent.showMediaGallery('image');
        });

        this._selectedDefaultImageEvent();
        this._removeDefaultImage();
    }

    _removeDefaultImage() {
        $('[data-action="remove-default-image"]').on('click', function(e)
        {
            $('div#default_image_preview img').attr('src', '');
            $('div#default_image_preview').addClass('d-none');
            $('select#entry_defaultImage').val('');
        });
    }

    _selectedDefaultImageEvent() {
        document.addEventListener('selectedItemFromMediaGallery', function (e) {
            let id = e.detail.image.id;
            let path = e.detail.image.path;

            let imageUrl = appUrl + '/uploads/' + path;

            $('div#default_image_preview img').attr('src', imageUrl);
            $('div#default_image_preview').removeClass('d-none');

            $('select#entry_defaultImage').val(id);
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

    /**
     * Handle language change event:
     *
     *     - Show only the categories for selected language
     */
    _handleLanguageChangeEvent() {
        let _self = this;

        $("select#entry_language").on("change", function (e) {
            e.preventDefault();

            // unselect all languages
            $('div#entry_categories input[data-language]').prop('checked', false);

            _self._toggleFormCategories();
        });
    }

    /**
     * Show only the categories for selected language
     *
     * @param String  Language  Selected language value
     */
    _toggleFormCategories(language) {
        if (typeof language == "undefined") {
            language = $("#entry_language").val();
        }

        if (language) {
            $('div#entry_categories input[data-language]').parent().addClass("d-none");
            $('div#entry_categories input[data-language="' + language + '"]').parent().removeClass("d-none");
        } else {
            $('div#entry_categories input[data-language]').parent().addClass("d-none");
        }
    }
}

export default Entries;
