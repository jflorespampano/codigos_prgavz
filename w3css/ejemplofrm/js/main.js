window.onload=function(){

    function eventoClic(){
        alert("evento clic 2")
    }
    function traeFuncion(){
        return function(){
            const sexo=document.forms.frm_alta.elements.sexo.value;
            const estado=document.forms["frm_alta"].elements.cmb_estado.value;
            alert("sexo:"+sexo+" estado: "+estado);
        };
    }
    document.querySelector("#btn_aceptar").addEventListener('click',traeFuncion());
    document.querySelector("#btn_cancelar").addEventListener('click',()=>{
        alert("evento clic desde cancelar")
    });
}