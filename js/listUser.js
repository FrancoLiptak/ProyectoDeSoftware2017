$(".deletebtn").click(function(){
    $("#deleteId").val($(this).data("id"))
    tr = $(this).closest('tr')
});

$(".editbtn").click(function(){
    $("#editId").val($(this).data("id"))
    $("#userNameEdit").val($(this).data("username"))
    id = $(this).attr("data-id");
    modal = $("#editUser")
    $('#userId').val(id)
    tr = $(this).closest('tr').find("td")
    $("#editLastName").val($(tr).eq(0).text())
    $("#editName").val($(tr).eq(1).text())
    $("#editEmail").val($(tr).eq(2).text())
    $("#editUserName").val($(tr).eq(3).text())

    if($(tr).eq(4).text().indexOf("Administrador") !== -1){
        $("#editRol option[value="+ "Administrador" +"]").attr("selected",true);
    }
    
    if($(tr).eq(4).text().indexOf("Pediatra") !== -1){
        $("#editRol option[value="+ "Pediatra" +"]").attr("selected",true);
    }

    if($(tr).eq(4).text().indexOf("Recepcionista") !== -1){
        $("#editRol option[value="+ "Recepcionista" +"]").attr("selected",true);
    }

    if($(tr).eq(5).text().indexOf("No Activo") !== -1){
        $("#editActivo option[value="+ 0 +"]").attr("selected",true);
    }else{
        $("#editActivo option[value="+ 1 +"]").attr("selected",true);
    }

})

$('#edit').on('hidden.bs.modal', function () {
    $("#editRol option[value="+ "Administrador" +"]").attr("selected",false);
    $("#editRol option[value="+ "Pediatra" +"]").attr("selected",false);
    $("#editRol option[value="+ "Recepcionista" +"]").attr("selected",false);
    $("#editActivo option[value="+ 1 +"]").attr("selected",false);
    $("#editActivo option[value="+ 0 +"]").attr("selected",false);
})