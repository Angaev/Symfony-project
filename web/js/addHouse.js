$(document).ready ( function() {
  $("#add").click(function() {
     if ($("#new_publishing_house").val().length != 0) {
        $.ajax ({
          url: "/add_house",
          type: "POST",
          dataType: "json",
          data: ({newHouse: $("#new_publishing_house").val()}),
          success: function(data) {
            bootbox.alert("Издательство добавлено!");
            $("#delete_select").append($("<option></option>").attr("value", data.id).text(data.name));
            $("#rename_select").append($("<option></option>").attr("value", data.id).text(data.name));
          }
        })
     }
  });
})
