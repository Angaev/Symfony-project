$(document).ready ( function() {
    $("#delete").click(function() {
        // console.log($("#delete_select").val());
        $.ajax ({
            url: "/delete_house",
            type: "POST",
            dataType: "json",
            data: ({id: $("#delete_select").val()}),
            success: function(data)
            {
                // console.log(data);
                bootbox.alert("Издательство удалено!");
                $("#delete_select :selected").remove();
                // $("#delete_select option[value = data.id]").text('Удалено');
                $("#delete_select :first").attr("selected", "selected");
                // $("#delete_select").append($("<option></option>").attr("value", data.id).text(data.name));
            }
        })
    });
})
