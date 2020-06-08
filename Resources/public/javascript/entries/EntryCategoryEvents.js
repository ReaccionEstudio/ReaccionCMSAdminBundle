class EntryCategoryEvents {

    events(){
        this._handleLanguageChangeEvent()
        this._toggleFormCategories()
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

        $('div#entry_categories input[data-language]').parent().addClass("d-none");

        if (language) {
            $('div#entry_categories input[data-language="' + language + '"]').parent().removeClass("d-none");
        } else if($('div#entry_categories input[data-language="null"]').length){
            $('div#entry_categories input[data-language="null"]').parent().removeClass("d-none");
        }
    }
}

export default EntryCategoryEvents
