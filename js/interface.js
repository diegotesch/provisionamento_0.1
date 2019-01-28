$(function(){
    /*MASCARAS*/
    $.mask.definitions['h'] = "[A-Fa-f0-9]";
    $('#mac').mask("hh:hh:hh:hh:hh:hh");
    $('.macMask').mask("hh:hh:hh:hh:hh:hh");
    /*FIM MASCARAS*/
    
    /*VALIDAÇÕES DE FORMULÁRIOS*/
    $("#formCadLoteOnu").validate({
        rules:{
            onu_mac:{
                required: true
            }
        },
        messages:{
            onu_mac:{
                required: "É obrigatório o preenchimento do campo <b>MAC do Equipamento</b>!"
            }
        }
    });
    /*FIM VALIDAÇÕES DE FORMULÁRIOS*/
    
    /*SUBMITS*/
    
    /*$("#addOnuToLote").click(function(){
        $("form#formCadLoteOnu").submit();
        $("form#formCadLoteOnu")[0].reset();
    });*/
    
    $("#generateSelEtiquetas").click(function(){
        $("form#formEtiquetasOnu").attr('action', 'etiquetas.php').submit();
    });
    
    $("#alocarSelTecnico").click(function(){
        $("form#formEtiquetasOnu").attr({
            'target': 'onuTecnico',
            'action': 'onuTecnico.php'
        });
        PostaDados();
    });
    
    function PostaDados(url){
        window.open('about:blank', 'onuTecnico', 'resizable=yes, toolbar=no, status=yes, menubar=no, scrollbars=noe, width=500, height=500, top=10, left=10');
        $("form#formEtiquetasOnu").submit();
    }
    
    $("#SelectOnuType").click(function(){
       $("form#formSelOnuType").submit();
    });
    
    /*FIM SUBMITS*/
    
    /*GERAL*/
    $("#formCadLoteOnu").ready(function(){
        $("#mac").focus();
    });
    
    $("a.lote").hover(function(){
        if($(this).hasClass('loteHover')){
            $(this).removeClass('loteHover');
            $(this).children().children("img").attr("src", "images/icone_lotes.png");
        }else{
            $(this).addClass('loteHover');
            $(this).children().children("img").attr("src", "images/icone_lotes_hover.png");
        }
    });
});




