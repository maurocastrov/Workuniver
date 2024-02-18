jQuery( document ).ready(function() {
    "use strict"
    jQuery('#add_author').unbind('click').bind('click', function (e) {
        var button = jQuery(this)
        button.parent().next().css('display','block');
        button.parent().next().clone().insertAfter(button.parent()).hide();
        return false
    })
    jQuery('#submit_book_data').unbind('click').bind('click', function (e) {
        var button = jQuery(this)
        button.addClass('loading')
        button.attr('disabled',true)
        e.preventDefault();
        var data={
            action: 'add_book'
        };
        // data['nonce'] = localizedObject.nonce;

        data['editor'] = jQuery('#editor').val();
        data['author'] = [];
        jQuery('.author_data').each(function(index){
            if(index !== 0) {
                data['author'].push(jQuery(this).val())
            }
        })
        data['full_name'] = jQuery('#full_name').val();
        data['edition_type'] = jQuery('#edition_type').val();
        data['volume_lines'] = jQuery('#volume_lines').val();
        data['book_format'] = jQuery('#book_format').val();
        data['circulations'] = jQuery('#circulations').val();
        data['neck'] = jQuery('#neck').val();
        data['city'] = jQuery('#city-publisher').val();
        data['ISBN'] = jQuery('#ISBN').val();
        data['MPF'] = jQuery('#MPF').val();
        data['funding_source'] = jQuery('#funding-source').val();
        data['budget'] = jQuery('#budget').val();
        data['book_link'] = jQuery('#book-link').val();
        data['book_notes'] = jQuery('#book-notes').val();

        let IPM_availability = 0;
        var selected = jQuery("input[name='IPM_availability']:checked");

        if (selected.length > 0) {
            IPM_availability = 1;
        }
        data['IPM_availability'] = IPM_availability;
        jQuery.ajax(
            {
                url:'/wp-admin/admin-ajax.php',
                type:'POST',
                data: data,
                success: function(result){
                    if(result.success == true) {
                        alert(result.data);
                    }
                    if(result.success == false) {
                        alert(result.data);
                        button.attr('disabled',false)
                        button.removeClass('loading')
                    }
                },
                error:  function(err){
                    console.log(err);
                    button.attr('disabled',false)
                    button.removeClass('loading')
                }
            });
            
        })
})