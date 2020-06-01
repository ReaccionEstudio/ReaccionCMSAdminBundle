import MediaImagePreviewComponent from './MediaImagePreviewComponent.js';
import MediaVideoPreviewComponent from './MediaVideoPreviewComponent.js';
import PageContentInputTypeEvents from '../../page/PageContentInputTypeEvents.js';
import ShowGallery from "./events/ShowGallery";
import ItemSelection from "./events/ItemSelection";

class MediaGalleryFormComponent {
    /**
     * Constructor
     */
    constructor() {
        this.selectedMedia = {};
        this.galleryComponent = "div#open_gallery_button";
        this._getFormSelectorValue();

        // Media image preview component
        this.mediaImagePreviewComponent = new MediaImagePreviewComponent(this.galleryComponent, this.formSelector);

        // Media video preview component
        this.mediaVideoPreviewComponent = new MediaVideoPreviewComponent(this.galleryComponent, this.formSelector);

        // Media gallery events
        this.events();
    }

    /**
     * Media gallery form component events
     */
    events() {
        this._navigateThroughtMediaGalleryEvent();

        let itemSelectionEvent = new ItemSelection()
        itemSelectionEvent.execute()

        let pageContentInputTypeEvents = new PageContentInputTypeEvents(this)
        pageContentInputTypeEvents.events()

        this.showGallery = new ShowGallery()
    }

    /**
     * Get form selector value defined as 'data-form-name' attribute in the 'Open gallery form button'
     */
    _getFormSelectorValue() {
        let formName = $(this.galleryComponent).attr("data-form-name");

        if (!formName) {
            throw new Error("Attribute 'data-form-name' does not exist.");
        }

        this.formSelector = 'form[name="' + formName + '"]';
    }

    /**
     * @param mediaType
     */
    show(mediaType) {
        this.showGallery.execute(mediaType)
    }

    /**
     * Handles navigation event for media gallery
     */
    _navigateThroughtMediaGalleryEvent() {
        $("body").on("click", "div#modal div.modal-body ul.pagination a.page-link", function (e) {
            e.preventDefault();

            $("div#modal div.modal-body .dimmer").addClass("active");
            $("div#modal div.modal-body").load($(this).attr("href"));
        });
    }
}

export default MediaGalleryFormComponent;
