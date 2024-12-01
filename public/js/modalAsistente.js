const modal =  document.querySelector('.modal');
const modalResultadosMigracion = document.getElementById('modalResultadosMigracion');


const showModal =  document.querySelector('.show-modal');
const closeModal =  document.querySelector('.close-modal');

const cerrarModalResultadosMigracion =  document.querySelector('.cerrarModalResultadosMigracion');

const prevBtn = document.getElementById('prev-btn');
const nextBtn = document.getElementById('next-btn');
const btn_confirmarSqlServer = document.getElementById('confirmarSqlServer');
const btn_confirmarMySQL = document.getElementById('confirmarMySQL');

const overlay = document.getElementById("modalOverlay");

const manejadorBDSelect = document.getElementById('manejadorBD');
const sqlServerDiv = document.getElementById('escogerBDSqlServer');
const mySqlDiv = document.getElementById('escogerBDMySql');


const steps = ['step-1', 'step-2', 'step-3'];
let currentStep = 0;

function showStep(stepIndex) {
    steps.forEach(step => {
        const element = document.getElementById(step + '-content');
        if (element) {
            if (step === steps[stepIndex]) {
                element.classList.remove('hidden');
            } else {
                element.classList.add('hidden');
            }
        }
    });
}

function updateButtons() {
    prevBtn.disabled = currentStep === 0;
    nextBtn.disabled = currentStep === steps.length - 1;

    
    const isLastStep = currentStep === steps.length - 1;
    
    if (isLastStep) {
        const selectedOption = parseInt(manejadorBDSelect.value);
        if (selectedOption === 0) {
            btn_confirmarSqlServer.classList.remove('hidden');
            btn_confirmarMySQL.classList.add('hidden');
        } else if (selectedOption === 1) {
            btn_confirmarSqlServer.classList.add('hidden');
            btn_confirmarMySQL.classList.remove('hidden');
        } else {
            btn_confirmarSqlServer.classList.add('hidden');
            btn_confirmarMySQL.classList.add('hidden');
        }
    } else {
        btn_confirmarSqlServer.classList.add('hidden');
        btn_confirmarMySQL.classList.add('hidden');
    }
}

function updateStepIndicator() {
    const stepIndicators = document.querySelectorAll('.flex li');
    stepIndicators.forEach((indicator, index) => {
            indicator.style.color = '';
        if (index === currentStep) {
            indicator.style.color = '#FFC301';
        } else if (index < currentStep) {
            indicator.style.color = '#3B82F6';
        }
    });
}

function updateModalContent() {
    const content = document.querySelector(`#${steps[currentStep]}-content p`);
    if (content) {
        switch (currentStep) {
            case 0:
                content.textContent = 'Content for step 1.';
                break;
            case 1:
                content.textContent = 'Content for step 2.';
                break;
            case 2:
                content.innerHTML = `<p>Seguro que quiere migrar esa base de datos, dale click en <strong>confirma</strong> para hacer la migración.</p>`;
                break;
        }
    }
}

function initModal() {
    showStep(currentStep);
    updateButtons();
    updateStepIndicator();
    updateModalContent();
}

prevBtn.addEventListener('click', () => {
    if (currentStep > 0) {
        currentStep--;
        initModal();
    }
});

nextBtn.addEventListener('click', () => {
    if (currentStep < steps.length - 1) {
        currentStep++;
        initModal();
    }
});

document.addEventListener('DOMContentLoaded', initModal);

showModal.addEventListener('click', function(){
    modal.classList.remove('hidden');
    manejadorBDSelect.value = 'Manejador';
    sqlServerDiv.value = 'BDSqlServer';
    mySqlDiv.value = 'BDMySql';
    overlay.style.display = "block";
    currentStep = 0;
    initModal();
});

closeModal.addEventListener('click', function(){
    modal.classList.add('hidden');
    overlay.style.display = "none";
});


cerrarModalResultadosMigracion.addEventListener('click', function(){
    modalResultadosMigracion.classList.add('hidden');
    overlay.style.display = "none";
});


manejadorBDSelect.addEventListener('change', function() {
    const selectedOption = parseInt(manejadorBDSelect.value);

    if (selectedOption === 0) {
        sqlServerDiv.classList.remove('hidden');
        mySqlDiv.classList.add('hidden');
    } else if (selectedOption === 1) {
        mySqlDiv.classList.remove('hidden');
        sqlServerDiv.classList.add('hidden');
    } else {
        sqlServerDiv.classList.add('hidden');
        mySqlDiv.classList.add('hidden');
    }
});


$(document).ready(function() {
    const sqlServerSelect = $('#escogerBDSqlServer');
    const mySqlSelect = $('#escogerBDMySql');
    $('#escogerBDSqlServer').change(function() {
        let databaseSqlServer = $(this).val(); 
        if (databaseSqlServer) {
            $('#confirmarSqlServer').prop('disabled', false); 
        } else {
            $('#confirmarSqlServer').prop('disabled', true);
        }
    });

    $('#confirmarSqlServer').click(function() {
        let databaseSqlServer = sqlServerSelect.val().toLowerCase(); 
        let databaseMySqlOptions = mySqlSelect.find('option').map(function() {
            return $(this).val().toLowerCase();
        }).get();
        
        if (databaseSqlServer && !databaseMySqlOptions.includes(databaseSqlServer)) {
            obtenerEstructuraBD(databaseSqlServer); 
            $('#progress-bar').show();
        } else {
            toastr.options = {
                "positionClass": "toast-bottom-center",
                "timeOut": "0",
                "extendedTimeOut": "0",
                "progressBar": true,
            };
            
            toastr.warning('¡Alerta! Ya existe una base de datos seleccionada. ¿Deseas continuar? <button type="button" class="btn btn-outline-danger btn-sm" id="confirmarProcesoSi">Sí</button> <button type="button" class="btn btn-outline-secondary btn-sm" id="confirmarProcesoNo">No</button>', '');
            
            $(document).on("click", "#confirmarProcesoSi", function() {
                let databaseSqlServer = sqlServerSelect.val().toLowerCase();
                obtenerEstructuraBD(databaseSqlServer);
                $('#progress-bar').show();
                toastr.remove();
            });
            
            $(document).on("click", "#confirmarProcesoNo", function() {
                toastr.remove();
            });
        }
    });
    
    function obtenerEstructuraBD(databaseSqlServer) {
        const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

        $.ajax({
            type: "POST",
            url: "/convertir-json",
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: {
                escogerBDSqlServer: databaseSqlServer
            },
            success: function (response) {
                if (response.success) {
                    console.log("Estructura de la base de datos obtenida correctamente");
                    migrarBDSqlServer(databaseSqlServer, response.data, response.insertStrings);
                } else {
                    console.log("Error al obtener la estructura de la base de datos:", response.message);
                }
            },
            error: function (xhr, status, error) {
                console.log("Error en la solicitud AJAX:", error);
            }
        });
    }

    function migrarBDSqlServer(databaseSqlServer, base_datos_sqlserver, insert_into_sqlserver) {
        const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
        const strings = [];

        for (const tabla of base_datos_sqlserver.tables) {
            const nombre_tabla = tabla.tabla_name.toLowerCase();
            const campos = crearCamposSqlServer(tabla.campos); 
            let string = `CREATE TABLE IF NOT EXISTS \`${nombre_tabla}\` (`;
            string += campos.join(", ");
            const nombre_primary = tabla.campos.find(campo => campo.parametros.nombre_primary);
            if (nombre_primary) {
                if (nombre_primary.type === 'longtext') {
                    string += `, PRIMARY KEY (\`${nombre_primary.nombre_campos}\`(191)) USING BTREE`;
                } else if (nombre_primary.type === 'char(36)'){
                    string += `, PRIMARY KEY (\`${nombre_primary.nombre_campos}\`(36)) USING BTREE`;
                } else {
                    string += `, PRIMARY KEY (\`${nombre_primary.nombre_campos}\`) USING BTREE`;
                }
            }
            string += ");";
            strings.push(string);
        }
        

        for (const fk of base_datos_sqlserver.foreignKeys) {
            const nombre_fk = fk.nombre_fk.toLowerCase();
            const campo_origen = fk.campo_origen;
            const tabla_origen = fk.tabla_origen;
            const tabla_referencia = fk.tabla_referencia;
            const campo_referencia = fk.campo_referencia;
            let string_fk = `ALTER TABLE \`${tabla_origen}\` ADD CONSTRAINT \`${nombre_fk}\` FOREIGN KEY (\`${campo_origen}\`) REFERENCES \`${tabla_referencia}\` (\`${campo_referencia}\`);`;
            strings.push(string_fk);
        }

        let progressBar = $('<div class="progress"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">0%</div></div>');
        toastr.info(progressBar, 'Procesando migración...', {closeButton: false, timeOut: 0, positionClass: 'toast-bottom-center'});

        $.ajax({
            type: "POST",
            url: "/migrar-bd",
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: {
                databasesqlserver: databaseSqlServer,
                migradorsqlserver: JSON.stringify(strings),
                insertintosqlserver: JSON.stringify(insert_into_sqlserver)
            },
            xhr: function() {
                let xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        let percentComplete = (evt.loaded / evt.total) * 100;
                        progressBar.find('.progress-bar').css("width", percentComplete + "%").text(percentComplete.toFixed(2) + "%");
                    }
                }, false);
                return xhr;
            },
            success: function (response) {
                console.log("Migración exitosa");

                toastr.clear();
                toastr.success('Migración exitosa', '', {positionClass: 'toast-bottom-center'});
                modal.classList.add('hidden');
                modalResultadosMigracion.classList.remove('hidden');
                overlay.style.display = "block";

                var resultadoConCheck = "<div class='accordion'>";

                resultadoConCheck += "<div class='accordion-item'>";
                resultadoConCheck += "<div class='accordion-header' onclick='toggleAccordion(this)'>Database</div>";
                resultadoConCheck += "<div class='accordion-content'>";
                resultadoConCheck += "<table style='border-collapse: collapse; width: 100%;'>";
                resultadoConCheck += "<tr><td>✔️</td><td>" + response.database + "</td></tr>";
                resultadoConCheck += "</table>";
                resultadoConCheck += "</div></div>";
                
                resultadoConCheck += "<div class='accordion-item'>";
                resultadoConCheck += "<div class='accordion-header' onclick='toggleAccordion(this)'>Tablas y Relaciones</div>";
                resultadoConCheck += "<div class='accordion-content'>";
                resultadoConCheck += "<table style='border-collapse: collapse; width: 100%;'>";
                for (var i = 0; i < response.strings.length; i++) {
                    resultadoConCheck += "<tr><td>✔️</td><td>" + response.strings[i] + "</td></tr>";
                }
                resultadoConCheck += "</table>";
                resultadoConCheck += "</div></div>";
                
                resultadoConCheck += "<div class='accordion-item'>";
                resultadoConCheck += "<div class='accordion-header' onclick='toggleAccordion(this)'>Inserts</div>";
                resultadoConCheck += "<div class='accordion-content'>";
                resultadoConCheck += "<table style='border-collapse: collapse; width: 100%;'>";
                for (var i = 0; i < response.inserts.length; i++) {
                    resultadoConCheck += "<tr><td>✔️</td><td>" + response.inserts[i] + "</td></tr>";
                }
                resultadoConCheck += "</table>";
                resultadoConCheck += "</div></div>";
                
                resultadoConCheck += "</div>";
                
                document.getElementById('resultadosMigracionString').innerHTML = resultadoConCheck;
                
            },
            error: function (xhr, status, error) {
                console.log("Error en la migración de la base de datos:", error);
                toastr.clear();
                toastr.error('Error en la migración de la base de datos: ' + error, '', {positionClass: 'toast-bottom-center'});
            }
        });
    }

    function crearCamposSqlServer(campos) {
        let camposSQL = [];
        for (const campo of campos) {
            const nombre_campo = campo.nombre_campos.toLowerCase();
            let type = campo.type;
            const parametros = campo.parametros;
            let campoString = `\`${nombre_campo}\` ${type}`;
            if(parametros.not_null){
                campoString += " NOT NULL";
            } else {
                campoString += " NULL";
            }
            if(parametros.identity){
                campoString += " AUTO_INCREMENT";
            }
            camposSQL.push(campoString);
        }
        return camposSQL;
    }
});


$(document).ready(function() {
    const mySqlSelect_mysql = $('#escogerBDMySql');
    const sqlServerSelect_mysql = $('#escogerBDSqlServer');
    $('#escogerBDMySql').change(function() {
        let databaseMySql = $(this).val(); 
        if (databaseMySql) {
            $('#confirmarMySQL').prop('disabled', false); 
        } else {
            $('#confirmarMySQL').prop('disabled', true);
        }
    });

    $('#confirmarMySQL').click(function() {
        let databaseMySql = mySqlSelect_mysql.val().toLowerCase();
        let databaseSqlServerOptions = sqlServerSelect_mysql.find('option').map(function() {
            return $(this).val().toLowerCase(); 
        }).get();
        
        if (databaseMySql && !databaseSqlServerOptions.includes(databaseMySql)) { 
            obtenerEstructuraBDMySql(databaseMySql); 
            $('#progress-bar').show();
        } else {

            toastr.options = {
                "positionClass": "toast-bottom-center",
                "timeOut": "0",
                "extendedTimeOut": "0",
                "progressBar": true,
            };
            
            toastr.warning('¡Alerta! Ya existe una base de datos seleccionada. ¿Deseas continuar? <button type="button" class="btn btn-outline-danger btn-sm" id="confirmarProcesoSi">Sí</button> <button type="button" class="btn btn-outline-secondary btn-sm" id="confirmarProcesoNo">No</button>', '');
            
            $(document).on("click", "#confirmarProcesoSi", function() {
                let databaseMySql = mySqlSelect_mysql.val().toLowerCase();
                obtenerEstructuraBDMySql(databaseMySql); 
                $('#progress-bar').show();
                toastr.remove();
            });
            
            $(document).on("click", "#confirmarProcesoNo", function() {
                toastr.remove();
            });
        }
    });

    function obtenerEstructuraBDMySql(databaseMySql) {
        const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

        $.ajax({
            type: "POST",
            url: "/convertir-json-mysql",
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: {
                escogerBDMySql: databaseMySql
            },
            success: function (response) {
                if (response.success) {
                    console.log("Estructura de la base de datos obtenida correctamente");
                    migrarBDMySql(databaseMySql, response.data, response.insertQueries, response.tablesmysql);
                    console.log(response.insertQueries);
                } else {
                    console.log("Error al obtener la estructura de la base de datos:", response.message);
                }
            },
            error: function (xhr, status, error) {
                console.log("Error en la solicitud AJAX:", error);
            }
        });
    }

    function migrarBDMySql(databaseMySql, base_datos_mysql, insert_into_mysql, tables_mysql) {
        const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
        const strings = [];
        const tablas_mysql_array= [];

        for (const tabla of base_datos_mysql.tables) {
            const nombre_tabla = tabla.tabla_name.toLowerCase();
            const campos = crearCampos(tabla.campos); 
            let string = `CREATE TABLE ${nombre_tabla} (`;
            string += campos.join(", ");
            const nombre_primary = tabla.campos.find(campo => campo.parametros.nombre_primary);
            if (nombre_primary) {
                string += `, PRIMARY KEY (${nombre_primary.nombre_campos})`;
            }
            string += ");";
            strings.push(string);
        }

        for (const fk of base_datos_mysql.foreignKeys) {
            const nombre_fk = fk.nombre_fk.toLowerCase();
            const campo_origen = fk.campo_origen;
            const tabla_origen = fk.tabla_origen;
            const tabla_referencia = fk.tabla_referencia;
            const campo_referencia = fk.campo_referencia;
            let string_fk = `ALTER TABLE ${tabla_origen} ADD CONSTRAINT ${nombre_fk} FOREIGN KEY (${campo_origen}) REFERENCES ${tabla_referencia} (${campo_referencia});`;
            strings.push(string_fk);
        }

        let progressBar = $('<div class="progress"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">0%</div></div>');
        toastr.info(progressBar, 'Procesando migración...', {closeButton: false, timeOut: 0, positionClass: 'toast-bottom-center'});

        
        for (const tabla_mysql of tables_mysql) {
            tablas_mysql_array.push(tabla_mysql.tabla_name);
        }

        $.ajax({
            type: "POST",
            url: "/migrar-bd-mysql",
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: {
                databasemysql: databaseMySql,
                migradormysql: JSON.stringify(strings),
                tablasmysql: JSON.stringify(tablas_mysql_array),
                insertintomysql: JSON.stringify(insert_into_mysql)
            },
            xhr: function() {
                let xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        let percentComplete = (evt.loaded / evt.total) * 100;
                        progressBar.find('.progress-bar').css("width", percentComplete + "%").text(percentComplete.toFixed(2) + "%");
                    }
                }, false);
                return xhr;
            },
            success: function (response) {
                console.log("Migración exitosa");
                console.log(response.strings);

                toastr.clear();
                toastr.success('Migración exitosa', '', {positionClass: 'toast-bottom-center'});
                modal.classList.add('hidden');
                modalResultadosMigracion.classList.remove('hidden');
                overlay.style.display = "block";


                var resultadoConCheck = "<div class='accordion'>";

                resultadoConCheck += "<div class='accordion-item'>";
                resultadoConCheck += "<div class='accordion-header' onclick='toggleAccordion(this)'>Database</div>";
                resultadoConCheck += "<div class='accordion-content'>";
                resultadoConCheck += "<table style='border-collapse: collapse; width: 100%;'>";
                resultadoConCheck += "<tr><td>✔️</td><td>" + response.database + "</td></tr>";
                resultadoConCheck += "</table>";
                resultadoConCheck += "</div></div>";
                
                resultadoConCheck += "<div class='accordion-item'>";
                resultadoConCheck += "<div class='accordion-header' onclick='toggleAccordion(this)'>Tablas y Relaciones</div>";
                resultadoConCheck += "<div class='accordion-content'>";
                resultadoConCheck += "<table style='border-collapse: collapse; width: 100%;'>";
                for (var i = 0; i < response.strings.length; i++) {
                    resultadoConCheck += "<tr><td>✔️</td><td>" + response.strings[i] + "</td></tr>";
                }
                resultadoConCheck += "</table>";
                resultadoConCheck += "</div></div>";


                resultadoConCheck += "<div class='accordion-item'>";
                resultadoConCheck += "<div class='accordion-header' onclick='toggleAccordion(this)'>Inserts</div>";
                resultadoConCheck += "<div class='accordion-content'>";
                resultadoConCheck += "<table style='border-collapse: collapse; width: 100%;'>";
                for (var i = 0; i < response.inserts.length; i++) {
                    resultadoConCheck += "<tr><td>✔️</td><td>" + response.inserts[i] + "</td></tr>";
                }
                resultadoConCheck += "</table>";
                resultadoConCheck += "</div></div>";
                
                resultadoConCheck += "</div>";
                
                document.getElementById('resultadosMigracionString').innerHTML = resultadoConCheck;

            },
            error: function (xhr, status, error) {
                console.log("Error en la migración de la base de datos:", error);
            }
        });
    }

    function crearCampos(campos){
        let camposSQL = [];
        for(const campo of campos){
            const nombre_campo = campo.nombre_campos.toLowerCase();
            let type = campo.type.toUpperCase();
            const parametros = campo.parametros;
            let campoString = `${nombre_campo} ${type}`;
      
            if (type.toLowerCase() === 'nvarchar') {
              type = 'varchar(255)';
            }
            if (type.toLowerCase() === 'varchar') {
                campoString += "(255)";
            }
            if(parametros.not_null){
                campoString += " NOT NULL";
            } else {
                campoString += " NULL";
            }
            if(parametros.identity){
              campoString += " IDENTITY(1,1)";
            }
      
            camposSQL.push(campoString);
        }
        return camposSQL;
      }
});


function toggleAccordion(element) {
    element.classList.toggle('active');
    var content = element.nextElementSibling;
    if (content.style.maxHeight) {
        content.style.maxHeight = null;
    } else {
        content.style.maxHeight = content.scrollHeight + "px";
    }
}   
