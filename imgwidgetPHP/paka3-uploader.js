jQuery(document).ready(function($){
    var custom_uploader;
    /*##############################*/
    /* 画像選択ボタンがクリックされた場合の処理。*/
    /*##############################*/
    $('.paka3media').live('click', 'button', function(e) {
        paka3images = "#box_"+$(this).attr("id");
        f_img_name = $(this).attr("name");
 
        e.preventDefault();
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
        custom_uploader = wp.media({
            title: 'Choose Image',
            // 以下のコメントアウトを解除すると画像のみに限定される。
 
            library: {
                type: 'image'
            }, 
 
            button: {
                text: 'Choose Image'
            },
            multiple: true // falseにすると画像を1つしか選択できなくなる
        });
        custom_uploader.on('select', function() {
            var images = custom_uploader.state().get('selection');
            var date = new Date().getTime();
            images.each(function(file){
                img_id = file.toJSON().id+"_"+date;
                $(paka3images).append('<div id=img_'+ img_id +'></div>')
                .find('div:last').append('<a href="#" class="paka3image_remove">画像を削除する</a><br />'
                     +'<input type="hidden" name="'+ f_img_name +'" value="'+file.toJSON().url+'" />'
                     +'<img src="'+file.toJSON().url+'" />');
            });
        });
        custom_uploader.open();
    });
    /*##############################*/
    /* 削除がクリックされた場合の処理。*/
    /*##############################*/
    $( ".paka3image_remove" ).live( 'click', function( e ) {
        
        e.preventDefault();
        e.stopPropagation();
        
        img_obj = $(this).parent();
        if(img_obj.length >0){
            img_obj.remove();
        }
    });
});