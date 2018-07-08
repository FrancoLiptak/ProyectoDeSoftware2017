$(".deletebtn").click(function(){
    $("#deleteId").val($(this).data("id"))
    $("#deletePatientId").val($(this).data("patientid"))
});

$(".editbtn").click(function(){
    $("#editId").val($(this).data("id"))
    $("#editPatientId").val($(this).data("patientid"))
    $("#fecha").val($(this).data("fecha"))
    $("#pesa").val($(this).data("pesa"))
    if($(this).data("vacunas_completas") == 1){
        $("#vacunas_completas_si").prop("checked", true)    
    }else{
        $("#vacunas_completas_no").prop("checked", true)    
    }
    $("#vacunas_observaciones").val($(this).data("vacunas_observaciones"))

    if($(this).data("maduracion_acorde") == 1){
        $("#maduracion_acorde_si").prop("checked", true)    
    }else{
        $("#maduracion_acorde_no").prop("checked", true)    
    }

    $("#maduracion_observaciones").val($(this).data("maduracion_observaciones"))
    if($(this).data("ex_fisico_normal") == 1){
        $("#ex_fisico_normal_si").prop("checked", true)    
    }else{
        $("#ex_fisico_normal_no").prop("checked", true)    
    }
    $("#ex_fisico_observaciones").val($(this).data("ex_fisico_observaciones"))
    $("#pc").val($(this).data("pc"))
    $("#ppc").val($(this).data("ppc"))
    $("#talla").val($(this).data("talla"))
    $("#alimentacion").val($(this).data("alimentacion"))
    $("#observaciones_generales").val($(this).data("observaciones_generales"))
});

$(".detailsbtn").click(function(){
    $("#usuarioDetalle").text($(this).data("usuario"))
    $("#fechaDetalle").text($(this).data("fecha"))
    $("#pesaDetalle").text($(this).data("pesa"))
    if($(this).data("vacunas_completas") == 1){
        $("#vacunas_completasDetalle").text("Si")    
    }else{
        $("#vacunas_completasDetalle").text("No")    
    }
    $("#vacunas_observacionesDetalle").text($(this).data("vacunas_observaciones"))
    if($(this).data("maduracion_acorde") == 1){
        $("#maduracion_acordeDetalle").text("Si")    
    }else{
        $("#maduracion_acordeDetalle").text("No")    
    }
    $("#maduracion_observacionesDetalle").text($(this).data("maduracion_observaciones"))
    if($(this).data("ex_fisico_normal") == 1){
        $("#ex_fisico_normalDetalle").text("Si")    
    }else{
        $("#ex_fisico_normalDetalle").text("No")    
    }
    $("#ex_fisico_observacionesDetalle").text($(this).data("ex_fisico_observaciones"))
    $("#pcDetalle").text($(this).data("pc"))
    $("#ppcDetalle").text($(this).data("ppc"))
    $("#tallaDetalle").text($(this).data("talla"))
    $("#alimentacionDetalle").text($(this).data("alimentacion"))
    $("#observaciones_generalesDetalle").text($(this).data("observaciones_generales"))
})