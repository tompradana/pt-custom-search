jQuery(document).ready(function(){
    let $pts_els = jQuery('.pt-custom-search.use-ajax');

    $pts_els.each(function(e){
        let theForm = jQuery(this).find('form[name="custom-search-posttype"]');
        let searcResultsContainer = jQuery(this).find('.search-results');

        theForm.on('submit', function(e){
            e.preventDefault();
            
            let formData = jQuery(this).serializeArray();

            jQuery.get(pt_custom_search_var.ajax_url, formData,
                function (data, textStatus, jqXHR) {
                    searcResultsContainer.html(data);
                },
                "html"
            );
        });

        return false;
    });
});