class ShowGallery {
    /**
     * @param mediaType
     */
    execute(mediaType) {
        let mediaListRoute = Routing.generate('reaccion_cms_admin_media_index');
        mediaListRoute = mediaListRoute + '?modal=1';

        if (mediaType) {
            mediaListRoute += '&type=' + mediaType;
        }

        $("div#modal").modal("show");
        $("div#modal div.modal-body div.dimmer-content").load(mediaListRoute, function (result) {
            $("div#modal div.modal-body .dimmer").removeClass("active");
        });
    }
}

export default ShowGallery
