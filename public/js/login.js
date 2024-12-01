const modalLonginSqlServer =  document.getElementById('modalLoginSqlServer');
const modalLoginMySql =  document.getElementById('modalLoginMySql');

const mostrarModalSqlServer =  document.querySelector('.abrirLoginSqlServer');
const cerrarModalSqlServer =  document.querySelector('.cerrarModalLoginSqlServer');

const mostrarModalMySql =  document.querySelector('.abrirLoginMySql');
const cerrarModalMySql =  document.querySelector('.cerrarModalLoginMySql');


const overlayLogin = document.getElementById("modalOverlay");


const btn_loginSqlServer = document.getElementById("loginSqlServer");
const btn_loginMySql = document.getElementById("loginMySql");


const divConectadoSqlServer = document.querySelector('.divConectadoSqlServer');
const divDesconectadoSqlServer = document.querySelector('.divDesconectadoSqlServer');
const divConectadoMySql = document.querySelector('.divConectadoMySql');
const divDesconectadoMySql = document.querySelector('.divDesconectadoMySql');

const divDatabaseSqlServer = document.querySelector('.divDatabaseSqlServer');
const divDatabaseMySql = document.querySelector('.divDatabaseMySql');

btn_loginSqlServer.addEventListener('click', function(){

    const txt_usuarioSqlServer = document.getElementById('usuarioSqlServer').value;
    const txt_passwordSqlServer = document.getElementById('passwordSqlServer').value;

    if(txt_usuarioSqlServer === "" || txt_passwordSqlServer === ""){
        toastr.error('Rellene todos los campos', '', {positionClass: 'toast-bottom-center'});
    } else {

        const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

        $.ajax({
            type: "POST",
            url: "/login-sqlserver",
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: {
                nombreUsuario: txt_usuarioSqlServer,
                password: txt_passwordSqlServer
            },
            success: function(response) {
                if (response.error) {
                    toastr.error('No se pudo el inicio de sesión', '', {positionClass: 'toast-bottom-center'});
                } else if (response.success) {
                    toastr.success('Inicio de sesión exitosa', '', {positionClass: 'toast-bottom-center'});
                    modalLonginSqlServer.classList.add('hidden');
                    overlay.style.display = "none";
                    divDesconectadoSqlServer.classList.add('hidden');
                    divConectadoSqlServer.classList.remove('hidden');
                    divDatabaseSqlServer.classList.remove('hidden');
                }
            },
            error: function(xhr, status, error) {
                console.log(error);
                alert('Error en el servidor. Inténtelo de nuevo más tarde.');
            }
        });

    }
});


btn_loginMySql.addEventListener('click', function(){

    const txt_usuarioMySql = document.getElementById('usuarioMySql').value;
    const txt_passwordMySql = document.getElementById('passwordMySql').value;

    if(txt_usuarioMySql === "" || txt_passwordMySql === ""){
        toastr.error('Rellene todos los campos', '', {positionClass: 'toast-bottom-center'});
    } else {

        const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

        $.ajax({
            type: "POST",
            url: "/login-mysql",
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: {
                nombreUsuarioMySql: txt_usuarioMySql,
                passwordMySql: txt_passwordMySql
            },
            success: function(response) {
                if (response.error) {
                    toastr.error('No se pudo el inicio de sesión', '', {positionClass: 'toast-bottom-center'});
                } else if (response.success) {
                    toastr.success('Inicio de sesión exitosa', '', {positionClass: 'toast-bottom-center'});
                    modalLoginMySql.classList.add('hidden');
                    overlay.style.display = "none";
                    divDesconectadoMySql.classList.add('hidden');
                    divConectadoMySql.classList.remove('hidden');
                    divDatabaseMySql.classList.remove('hidden');
                }
            },
            error: function(xhr, status, error) {
                console.log(error);
                alert('Error en el servidor. Inténtelo de nuevo más tarde.');
            }
        });

    }
});



mostrarModalSqlServer.addEventListener('click', function(){
    modalLonginSqlServer.classList.remove('hidden')
    overlay.style.display = "block";
});

cerrarModalSqlServer.addEventListener('click', function(){
    modalLonginSqlServer.classList.add('hidden');
    overlay.style.display = "none";
});

mostrarModalMySql.addEventListener('click', function(){
    modalLoginMySql.classList.remove('hidden')
    overlay.style.display = "block";
});

cerrarModalMySql.addEventListener('click', function(){
    modalLoginMySql.classList.add('hidden');
    overlay.style.display = "none";
});