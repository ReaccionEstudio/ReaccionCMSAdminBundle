class ItemSelection {
    execute(){
        $("body").on("click", "div#modal div.card a[data-media-id]", function (e) {
            e.preventDefault();

            let mediaId = $(this).attr("data-media-id");

            // Get image details
            let detailMediaRoute = Routing.generate('reaccion_cms_admin_media_detail', {'media': mediaId});

            $.get(detailMediaRoute + '?json=1', function (result) {
                this.selectedMedia = result;

                console.log(this.selectedMedia);

                // create and dispatch event
                let mediaKey = this.selectedMedia['type'];

                // event data
                let detail = {"reset": true};
                detail[mediaKey] = this.selectedMedia;

                var event = new CustomEvent(
                    'selectedItemFromMediaGallery',
                    {
                        'detail': detail
                    }
                );

                document.dispatchEvent(event);
            });
        });
    }
}

export default ItemSelection
