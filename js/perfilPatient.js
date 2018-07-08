$(".deletebtn").click(function(){
    $("#deleteId").val($(this).data("id"))
    tr = $(this).closest('tr')
});

$(".editbtn").click(function(){
    $("#editId").val($(this).data("id"))
    tr = $(this).closest('tr')
});

$(document).ready(function () {
    $("#editLastName").val( $("tr td")[1].innerText);
    $("#editName").val( $("tr td")[3].innerText);
    $("#editBirthday").val( $("tr td")[5].innerText);

    if( $("tr td")[7].innerText == "Masculino"){
        $("#editGender option[value="+ 1 +"]").attr("selected",true);
    }else{
        $("#editGender option[value="+ 2 +"]").attr("selected",true);
    }

    if( $("tr td")[9].innerText == "DNI"){
        $("#editTypeDocument option[value="+ 1 +"]").attr("selected",true);
    }else{
        if( $("tr td")[9].innerText == "Libreta Cívica"){
            $("#editTypeDocument option[value="+ 2 +"]").attr("selected",true);
        }else{
            $("#editTypeDocument option[value="+ 3 +"]").attr("selected",true);
        }
    }
    
    $("#editNumberDocument").val( $("tr td")[11].innerText);
    $("#editAddress").val( $("tr td")[13].innerText);
    $("#editPhone").val( $("tr td")[15].innerText);

    if( $("tr td")[17].innerText == "ATSA"){
        $("#editHealthInsurance option[value="+ 1 +"]").attr("selected",true);
    }else{
        if( $("tr td")[17].innerText == "IOMA"){
            $("#editHealthInsurance option[value="+ 2 +"]").attr("selected",true);
        }else{
            $("#editHealthInsurance option[value="+ 3 +"]").attr("selected",true);
        }
    }

    if( $("tr td")[19].innerText == "Si"){
        $("#editFridge option[value="+ 1 +"]").attr("selected",true);
    }else{
        $("#editFridge option[value="+ 2 +"]").attr("selected",true);
    }

    if( $("tr td")[21].innerText == "Si"){
        $("#editElectricity option[value="+ 1 +"]").attr("selected",true);
    }else{
        $("#editElectricity option[value="+ 2 +"]").attr("selected",true);
    }

    if( $("tr td")[23].innerText == "Si"){
        $("#editPet option[value="+ 1 +"]").attr("selected",true);
    }else{
        $("#editPet option[value="+ 2 +"]").attr("selected",true);
    }

    if( $("tr td")[25].innerText == "Casa"){
        $("#editTypeOfHousing option[value="+ 1 +"]").attr("selected",true);
    }else{
        $("#editTypeOfHousing option[value="+ 2 +"]").attr("selected",true);
    }

    if( $("tr td")[27].innerText == "Estufa"){
        $("#editHeating option[value="+ 1 +"]").attr("selected",true);
    }else{
        if( $("tr td")[27].innerText == "Aire acondicionado"){
            $("#editHeating option[value="+ 2 +"]").attr("selected",true);
        }else{
            $("#editHeating option[value="+ 3 +"]").attr("selected",true);
        }
    }

    if( $("tr td")[29].innerText == "Potable"){
        $("#editPet option[value="+ 1 +"]").attr("selected",true);
    }else{
        $("#editPet option[value="+ 2 +"]").attr("selected",true);
    }
});