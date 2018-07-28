$(document).ready ( function() {
    $("#rename").click(function() {
        if ($("#rename_publishing_house").val().length != 0) {
            $.ajax({
                url: "/rename_house",
                type: "POST",
                dataType: "json",
                data: ({
                    id: $("#rename_select").val(),
                    newName: $("#rename_publishing_house").val()
                }),
                success: function (data) {
                    console.log(data.id,  data.name);
                    $("#rename_select :selected").text(data.name);
                    bootbox.alert("Издательство переименовано!");
                }
          })
        }
    });
})
