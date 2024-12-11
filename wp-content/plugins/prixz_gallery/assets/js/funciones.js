function validaCorreo(valor) {
  if (/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i.test(valor)){
   return true;
  } else {
   return false;
  }
}
 
 function confirmaAlert(pregunta, ruta) {
     jCustomConfirm(pregunta, 'Tamila', 'Aceptar', 'Cancelar', function(r) {
         if (r) {
             window.location = ruta;
         }
     });
 }
 function cerrarSesion(ruta)
 {
     Swal.fire({
         title: 'Realmente deseas cerrar tu sesión?',
         icon: 'info',
         showDenyButton: true,
         showCancelButton: true,
         confirmButtonText: 'Si',
         confirmButtonColor: '#3085d6',
             cancelButtonColor: '#d33',
             cancelButtonText: 'NO' 
       }).then((result) => {
         
         if (result.isConfirmed) {
           window.location=ruta;
         }  
       })
 }
 function confirmarSweet(pregunta, ruta)
 {
     Swal.fire({
         title: pregunta,
         icon: 'error',
         showDenyButton: true,
         showCancelButton: true,
         confirmButtonText: 'Si',
         confirmButtonColor: '#3085d6',
             cancelButtonColor: '#d33',
             cancelButtonText: 'NO' 
       }).then((result) => {
         
         if (result.isConfirmed) {
           window.location=ruta;
         }  
       })
 }
 
 function buscador()
 {
    if(document.getElementById('s').value==0)
    {
        return false;
    }
    document.search.submit();
 }
 function soloNumeros(evt) {
     key = (document.all) ? evt.keyCode : evt.which;
     //alert(key);
     if (key == 17) return false;
     /* digitos,del, sup,tab,arrows*/
     return ((key >= 48 && key <= 57) || key == 8 || key == 127 || key == 9 || key == 0);
 }
 function get_respuestas_formulario(id){
    jQuery(document).ready(function($){
        $("#respuesta_modal").modal("show");
        document.getElementById('respuesta_moda_title').innerHTML="Respuestas formulario N°"+id;
        $.ajax({
            type: "POST",
            url: datosajax.url,
            data:{
                action : "tamila_form_contact_respuestas_ajax",
                nonce : datosajax.nonce,
                id: id,
            },
            success:function(resp){
                //document.getElementById('respuesta_moda_body').innerHTML=resp;
                $("#respuesta_moda_body").html(resp);
                return false;
            }
        });
    });
    
 }
 function galleryCreateModal (){
    jQuery(document).ready(function($){
        $("#createModal").modal("show"); 
        
         
    });
 }
 function galleryCreateRegister(){
    var form=document.formCrearGaleria;
    if(form.name.value==0)
    { 
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'El campo nombre es obligatorio',
    });
    form.name.value='';
    return false;
    }
  
    
    form.submit();
 }

 
 function get_eliminar_galeria(id){
    Swal.fire({
        title: 'Realmente desea eliminar este registro?',
        icon: 'warning',
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: 'Si',
        confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'NO' 
      }).then((result) => {
        
        if (result.isConfirmed) {
             
          document.tamila_form_contact_form_eliminar.accion.value='3';
          document.tamila_form_contact_form_eliminar.id.value=id;
          document.tamila_form_contact_form_eliminar.submit();
        }  
      });
      return false;
 }
 function deletePhoto(photo_id, gallery_id, photo_path) {
  // Validar existencia del formulario
  const form = document.forms["form_delet_photo"];
  if (!form) {
      console.error("El formulario form_delet_photo no existe.");
      Swal.fire("Error", "No se pudo encontrar el formulario para eliminar la foto.", "error");
      return;
  }

  // Validar existencia de los campos
  if (!form.gallery_id || !form.photo_id || !form.photo_path) {
      console.error("Los campos necesarios del formulario no están definidos.", { form });
      Swal.fire("Error", "Faltan campos necesarios en el formulario.", "error");
      return;
  }

  // Validar datos recibidos
  if (!photo_id || !gallery_id || !photo_path) {
      console.error("Faltan parámetros necesarios:", { photo_id, gallery_id, photo_path });
      Swal.fire("Error", "No se recibieron datos necesarios para eliminar la foto.", "error");
      return;
  }

  // Confirmar eliminación
  Swal.fire({
      title: "¿Realmente desea eliminar este registro?",
      icon: "warning",
      showDenyButton: true,
      showCancelButton: true,
      confirmButtonText: "Si",
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      cancelButtonText: "NO",
  }).then((result) => {
      if (result.isConfirmed) {
          // Asignar valores al formulario
          form.action.value = "delete";
          form.gallery_id.value = gallery_id;
          form.photo_id.value = photo_id;
          form.photo_path.value = photo_path;

          // Enviar formulario
          form.submit();
      }
  });
}

 
 //media de wordpress
 jQuery(document).ready(function($){
  var marco, $btn_marco=$('.btn-marco');
  $btn_marco.on('click', function(){
    if (marco){
      marco.open();
      return;
    }
    var marco = wp.media({
      frame: 'select',
      title: 'Seleccionar imagen para la galería',
      button: {
          text: 'Usar esta imagen'
      },
      multiple: false,
      library: {
          type: 'image',
          order:'DESC',
          orderby:'name'
      }
  });
  marco.on( 'select', function(){
            
          //console.log(marco.state().get('selection').first().toJSON());
          //console.log(marco.state().get('selection').first().toJSON().id);
          //console.log(marco.state().get('selection').first().toJSON().filename);
          //console.log(marco.state().get('selection').first().toJSON().url);
          let form=document.form_add_photo;
          form.photo_id.value=  marco.state().get('selection').first().toJSON().id;
          form.photo_value.value=  marco.state().get('selection').first().toJSON().filename;
          form.photo_url.value=  marco.state().get('selection').first().toJSON().url;
          form.submit();
        });
        
        marco.open();
  });
});
 
 

 