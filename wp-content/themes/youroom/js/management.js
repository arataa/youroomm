function get_url(file){
  var host = location.hostname;
  var url  = 'http://' + host + '/wp-content/themes/youroom/' + file;
  if ( host == "localhost" ) {
    url  = 'http://' + host + '/~okura/wordpress/wp-content/themes/youroom/' + file;
  }
  return url;
}


function upload_image_file(item, image_field, url_field){
  var url = get_url('upload.php');
  url = url + "?type=" + item.attr('class') ;

  item.upload(url, function(res) {
    if (image_field.length > 0) image_field.attr('src',res.url);
    url_field.val(res.url);
  }, 'json');
}

$(function(){
  $('#input_profile').change(function() {
    upload_image_file($(this),$('#profile_image'),$('#image_url'));
  });

  $('#image_browse').click(function(){
    $('#input_profile').click();
  });

  $('#add_property').click(function(){
    var url = get_url('room.php');
    var id  = 0 ;
    $('.deletebtn').each( function(){
      if ( $(this).attr('p_id') <= id ) id = $(this).attr('p_id') - 1;
    });

    $.ajax({
        url: url + '?method=add&id=' + id,
        type: 'GET',
        contentType: "application/json; charset=utf-8",
        success: function(data){
          $("div.addmore").before(data);
        },
    });
  });


  for ( var i = 1 ; i <= 10 ; i++ ){
    $(document).on('click','img.image' + i,function(){$(this).parent().children('input[type=file]').click();});
  }

  $(document).on('change','input[type=file].propety_pic',function(){update_image_filename($(this));
                  upload_image_file($(this),"",$(this).parent().children('input[type=text]'));
                                                           });
  $(document).on('click','.deletebtn',function(){delete_property($(this));});
});


function update_image_filename(item){
  var name = String(item.val()).replace(/.*\\/,'');
  item.parent().parent().children('td.file_name').text(name);
}

function delete_property(item){
  var url = get_url('room.php');
  if ( item.attr('p_id') == 0 ) {
    item.parent().parent().remove();
    return ;
  }
  $.ajax({
      url: url + '?method=remove&id=' + item.attr('p_id'),
      type: 'GET',
      contentType: "application/json; charset=utf-8",
      success: function(data){
        if (data == true) item.parent().parent().remove();
      },
  });
}

