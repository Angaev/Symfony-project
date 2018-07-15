
$(document).ready ( function() {
  var NO_LIKE_PRESSED = 1;
  var LIKE_PRESSED = 2;

  console.log('hello');

  $("#like_button").click(function() {
    $.ajax ({
        url:"/like",
        type: "POST",
        dataType : "json", 
        data: ({book_id: $("#book_id").val()}),
        beforeSend: function() {
            $("#likeBtn_text").text("Отправляется...");
            $("#like_button").addClass("disabled");

        },
        success: function(data) {
            $("#like_count").text(data);
            if (Number($("#likeBtn_status").val()) === NO_LIKE_PRESSED) {
                $("#likeBtn_status").val(LIKE_PRESSED);
                $("#likeBtn_text").text("Больше не нравится");
                
                $("#likeBtn_icon").addClass("glyphicon-thumbs-down");
                $("#likeBtn_icon").removeClass("glyphicon-heart");
                
                $("#like_button").addClass("btn-primary");
                $("#like_button").removeClass("btn-info disabled");
            } else {
                $("#likeBtn_status").val(NO_LIKE_PRESSED);
                $("#likeBtn_text").text("Мне нравится");

                $("#likeBtn_icon").removeClass("glyphicon-thumbs-down");
                $("#likeBtn_icon").addClass("glyphicon-heart");
                
                $("#like_button").removeClass("btn-primary disabled");
                $("#like_button").addClass("btn-info");
            }
        }
    })
  })

})
