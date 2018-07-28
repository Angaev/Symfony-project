$(document).ready ( function() {
  $("#delete").click(function() {
    $.ajax ({
      url: "/delete_house",
      type: "POST",
      dataType: "json",
      data: ({id: $("#delete_select").val()}),
      success: function(data) {
        bootbox.alert("Издательство удалено!");
        $("#delete_select :selected").remove();
        $("#delete_select :first").attr("selected", "selected");
      }
    })
  });
})