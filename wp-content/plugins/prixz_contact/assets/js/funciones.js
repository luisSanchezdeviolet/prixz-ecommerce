function validaCorreo(valor) {
    const emailRegex = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
    return emailRegex.test(valor);
}

function confirmaAlert(pregunta, ruta) {
    jCustomConfirm(pregunta, 'Prixz', 'Aceptar', 'Cancelar', function(r) {
        if (r) {
            window.location = ruta;
        }
    });
}

function cerrarSesion(ruta) {
    Swal.fire({
        title: '¿Realmente deseas cerrar tu sesión?',
        icon: 'info',
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: 'Sí',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'No',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = ruta;
        }
    });
}

function confirmarSweet(pregunta, ruta) {
    Swal.fire({
        title: pregunta,
        icon: 'error',
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: 'Sí',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'No',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = ruta;
        }
    });
}

function buscador() {
    const searchValue = document.getElementById('s').value;
    if (searchValue === '') {
        return false;
    }
    document.search.submit();
}

function soloNumeros(evt) {
    const key = evt.keyCode || evt.which;
    return (key >= 48 && key <= 57) || key === 8 || key === 127 || key === 9 || key === 0;
}

function obtenerRespuestasFormulario(id) {
    jQuery(document).ready(function($) {
        $("#respuesta_modal").modal("show");
        document.getElementById('respuesta_modal_title').innerHTML = `Respuestas formulario N° ${id}`;
        $.ajax({
            type: "POST",
            url: datosajax.url,
            data: {
                action: "prixz_contact_form_respuestas",
                nonce: datosajax.nonce,
                id: id,
            },
            success: function(resp) {
                $("#respuesta_modal_body").html(resp);
            }
        });
    });
}

function abrirModalFormulario(accion, titulo, nombre, correo, id = null) {
    jQuery(document).ready(function($) {
        $("#crear_formulario").modal("show");
        document.getElementById('crear_formulario_title').innerHTML = titulo;
        document.getElementById('prixz_input_name').value = nombre || '';
        document.getElementById('prixz_input_email').value = correo || '';
        document.getElementById('prixz_input_action').value = accion;

        if (accion === 'edit' && id !== null) {
            document.getElementById('prixz_input_id').value = id;
        }
    });
}

function validarYEnviarFormulario() {
    const form = document.prixz_contact_form_crear;

    if (!form.prixz_input_name.value.trim()) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'El campo nombre es obligatorio',
        });
        return false;
    }

    if (!form.prixz_input_email.value.trim()) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'El campo E-Mail es obligatorio',
        });
        return false;
    }

    if (!validaCorreo(form.prixz_input_email.value)) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'El E-Mail ingresado no es válido',
        });
        return false;
    }

    form.submit();
}

function eliminarFormulario(id) {
    Swal.fire({
        title: '¿Realmente desea eliminar este registro?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'No',
    }).then((result) => {
        if (result.isConfirmed) {
            document.prixz_contact_eliminar.action.value = 'delete';
            document.prixz_contact_eliminar.id.value = id;
            document.prixz_contact_eliminar.submit();
        }
    });
}
