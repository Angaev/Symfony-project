$(document).ready ( function() {
  $("#deleteAvatar").click(function() {
    bootbox.confirm("Удалить безвозвратно?", function(result) {
      if (result) {
        $.ajax ({
          url:"/user_delete_avatar",
          type: "POST",
          dataType : "json",
          data: ({}),
          success: function(data) {
            console.log(data);
            $("#avatar").attr("src", 'img/avatar/noavatar.png');
            $("#deleteAvatar").text("Удалено");
            $("#deleteAvatar").attr("disabled", true);
          }
        })
      }
    });
  })
})
