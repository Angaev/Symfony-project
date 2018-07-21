$(document).ready ( function() {
  $('#fileChoice').on('change', function() {
     // console.log("File set");
      var file_data = $('#fileChoice').prop('files')[0];
      var form_data = new FormData();
      form_data.append('file', file_data);
     // alert(form_data);
      $.ajax({
            url: "/user_add_avatar",
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(phpResponse){
                console.log(phpResponse);
                $("#avatar").attr("src", phpResponse);
                $("#deleteAvatar").text("Удалить аватар");
                $("#deleteAvatar").attr("disabled", false);
            }
     });
  })
})