<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Migrador</title>
    <link rel="icon" href="{{asset('/images/logo_migrador.png')}}" type="image/x-icon">
    <link rel="stylesheet" href="{{asset('/css/estilosMigrador.css')}}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('/libs/toastr/toastr.min.css') }}" rel="stylesheet">

    <style>

        .modal {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; 
            background-color: transparent;
        }

        .modalResultadoConsulta{
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; 
            background-color: transparent;
        }

        #modalLoginSqlServer{
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; 
            background-color: transparent;
        }

        #modalLoginMySql{
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; 
            background-color: transparent;
        }

        #modalResultadosMigracion{
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; 
            background-color: transparent;
        }

        .modal-overlay {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); 
        }

        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        ::-webkit-scrollbar-horizontal {
            height: 10px;
        }

        ::-webkit-scrollbar-thumb:horizontal {
            background: #888;
        }

        .progress {
            width: 100%;
            height: 30px;
            background-color: #f5f5f5;
            border-radius: 5px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            line-height: 30px;
            color: #fff;
            text-align: center;
            background-color: #007bff;
            transition: width 0.6s ease;
        }

        #confirmarProcesoSi {
            background-color: green; 
            padding-left: 10px;
            padding-right: 10px;
            border-radius: 20%;
            color: #fff;
        }   
        
        #confirmarProcesoNo {
            background-color: red; 
            padding-left: 10px;
            padding-right: 10px;
            border-radius: 20%;
            color: #fff;
        }
        
        #confirmarProcesoSi:hover, #confirmarProcesoNo:hover {
            opacity: 0.8; 
        }

        .accordion {
            overflow: hidden;
        }

        .accordion-item {
            border-bottom: 1px solid #ddd;
        }

        .accordion-header {
            background-color: #f0f0f0;
            padding: 10px;
            cursor: pointer;
        }

        .accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.2s ease-out;
        }

        .active {
            background-color: #ddd;
        }


    </style>
</head>
    <body class="bg-[#156082]">

        <header class="bg-white">
            <nav class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8" aria-label="Global">
                <div class="flex lg:flex-1">
                    <a href="#" class="-m-2.5 p-2.5">
                        <span class="sr-only">logoMigrador</span>
                        <img class="h-12 w-auto" src="{{asset('/images/logo_migrador.png')}}" alt="">
                    </a>
                </div>
                <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                    <button class="bg-[#595959] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full show-modal" >Migrate</button>
                </div>
            </nav>
        </header>

        <div  class="container mx-auto mt-5">
            <div class="grid grid-cols-6 gap-4 mt-2">
                {{------------------------------------------------------------------------------------------}}
                {{--                    Aparatado de las base de dato SQL SERVER                          --}}
                {{------------------------------------------------------------------------------------------}}
                <div class="col-span-6 py-5">
                    <button class="bg-[#595959] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full" id="AgregarNuevaConsultaSqlServer">New Query</button>
                    <button id="ejecutarConsultaSqlServer" class="bg-[#FF9900] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full" type="submit">Execute</button>
                    <select id="databaseSqlSever" name="databaseSqlSever" class="bg-gray-50 border border-gray-300 py-2 px-4 text-gray-900 rounded-lg">
                        <option value="Database" selected>Database</option>
                        @foreach($databases as $database)
                            <option value="{{$database->DATABASE_NAME}}">{{$database->DATABASE_NAME}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-2 bg-white">
                    <div class="flex items-center justify-between mx-2 py-2">
                        <header class="p-2 font-bold">SqlServer</header>
                        <div class="flex items-center space-x-2 divDesconectadoSqlServer">
                            <button class="bg-blue-500 hover:bg-blue-700 py-1 px-3 rounded-full abrirLoginSqlServer">Connect</button>
                            <p>Disconnected</p>
                            <div class="w-5 h-5 bg-[#FF0000] rounded-full"></div>
                        </div>
                        <div class="flex items-center space-x-2 hidden divConectadoSqlServer">
                            <p>Connected</p>
                            <div class="w-5 h-5 bg-[#4EA72E] rounded-full"></div>
                        </div>
                    </div>
                    <div class="hidden divDatabaseSqlServer" style="overflow-y: scroll; max-height: 500px;">
                        @foreach($tablesAndColumnsByDatabase as $databaseName => $tables)
                            <details class="border-2 p-4 [&_svg]:open:-rotate-180">
                                <summary class="flex cursor-pointer list-none items-center gap-4">
                                    <div>
                                        <svg class="rotate-0 transform text-blue-700 transition-all duration-300" fill="none" height="20" width="20" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                                            <polyline points="6 9 12 15 18 9"></polyline>
                                        </svg>
                                    </div>
                                    <img class="h-auto w-auto" src="{{ asset('/images/icon_database.png') }}" alt="" style="width: 20px;">  
                                    <div>{{ $databaseName }}</div>
                                </summary>
                                <ul style="padding-left: 20px; margin-top: 5px;">
                                    @foreach($tables as $tableName => $columns)
                                        <li class="ml-10 mt-2">
                                            <details class="border-2 p-4 [&_svg]:open:-rotate-180">
                                                <summary class="flex cursor-pointer list-none items-center gap-4">
                                                    <div>
                                                        <svg class="rotate-0 transform text-blue-700 transition-all duration-300" fill="none" height="20" width="20" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                                                            <polyline points="6 9 12 15 18 9"></polyline>
                                                        </svg>
                                                    </div>
                                                    <img src="{{ asset('/images/icon_table.png') }}" alt="" style="height: 20px; width: 20px;">
                                                    <p class="font-semibold text-neutral-700">{{ $tableName }}</p>
                                                </summary>
                                                <ul style="padding-left: 20px; margin-top: 5px;">
                                                    @foreach($columns as $column)
                                                        <li class="flex items-center text-neutral-500 ml-6">
                                                            <img class="mr-2" src="{{ asset('/images/icon_column.png') }}" alt="" style="width: 16px; height: 16px;">
                                                            <span>{{ $column->COLUMN_NAME }} ({{ $column->DATA_TYPE }})</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </details>
                                        </li>
                                    @endforeach
                                </ul>
                            </details>
                        @endforeach
                    </div>
                
                </div>

                <div class="col-span-4">
                    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="pestanasSqlServer"></ul>
                    <div id="consultasSqlServer"></div>
                </div>

                <hr class="mt-5 col-span-6 h-1 border-4">

                {{------------------------------------------------------------------------------------------}}
                {{--                    Aparatado de las base de dato MySql                               --}}
                {{------------------------------------------------------------------------------------------}}

                <div class="col-span-6 py-5">
                    <button class="bg-[#595959] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full" id="AgregarNuevaConsultaMySql">New Query</button>
                    <button id="ejecutarConsultaMySql" class="bg-[#FF9900] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">Execute</button>
                    <select id="databaseMySql" class="bg-gray-50 border border-gray-300 py-2 px-4 text-gray-900 rounded-lg">
                        <option selected>Database</option>
                        @foreach($databases2 as $database2)
                            <option value="{{$database2->SCHEMA_NAME2}}">{{$database2->SCHEMA_NAME2}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-2 bg-white mb-3">
                    <div class="flex items-center justify-between mx-2 py-2">
                        <header class="p-2 font-bold">MySql</header>
                        <div class="flex items-center space-x-2 divDesconectadoMySql">
                            <button class="bg-blue-500 hover:bg-blue-700 py-1 px-3 rounded-full abrirLoginMySql">Connect</button>
                            <p>Disconnected</p>
                            <div class="w-5 h-5 bg-[#FF0000] rounded-full"></div>
                        </div>
                        <div class="flex items-center space-x-2 hidden divConectadoMySql">
                            <p>Connected</p>
                            <div class="w-5 h-5 bg-[#4EA72E] rounded-full"></div>
                        </div>
                    </div>
                    <div class="hidden divDatabaseMySql" style="overflow-y: scroll; max-height: 500px;">
                        @foreach($tablesAndColumnsByDatabase2 as $databaseName2 => $tables2)
                        <details class="border-2 p-4 [&_svg]:open:-rotate-180">
                            <summary class="flex cursor-pointer list-none items-center gap-4">
                                <div>
                                    <svg class="rotate-0 transform text-blue-700 transition-all duration-300" fill="none" height="20" width="20" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </div>
                                <img class="h-auto w-auto" src="{{ asset('/images/icon_database.png') }}" alt="" style="width: 20px;">  
                                <div>{{ $databaseName2 }}</div>
                            </summary>
                            <ul style="padding-left: 20px; margin-top: 5px;">
                                @foreach($tables2 as $tableName2 => $columns2)
                                    <li class="ml-10 mt-2">
                                        <details class="border-2 p-4 [&_svg]:open:-rotate-180">
                                            <summary class="flex cursor-pointer list-none items-center gap-4">
                                                <div>
                                                    <svg class="rotate-0 transform text-blue-700 transition-all duration-300" fill="none" height="20" width="20" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                                                        <polyline points="6 9 12 15 18 9"></polyline>
                                                    </svg>
                                                </div>
                                                <img src="{{ asset('/images/icon_table.png') }}" alt="" style="height: 20px; width: 20px;">
                                                <p class="font-semibold text-neutral-700">{{ $tableName2 }}</p>
                                            </summary>
                                            <ul style="padding-left: 20px; margin-top: 5px;">
                                                @foreach($columns2 as $column2)
                                                    <li class="flex items-center text-neutral-500 ml-6">
                                                        <img class="mr-2" src="{{ asset('/images/icon_column.png') }}" alt="" style="width: 16px; height: 16px;">
                                                        <span>{{ $column2->COLUMN_NAME2 }} ({{ $column2->DATA_TYPE2 }})</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </details>
                                    </li>
                                @endforeach
                            </ul>
                        </details>
                        @endforeach
                    </div>
                </div>

                <div class="col-span-4">
                    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="pestanasMySql"></ul>
                    <div id="consultasMySql"></div>
                </div>

            </div>
        </div>
        

        {{------------------------------------------------------------------------------------------}}
        {{--                        Modal del resultado de las  query's                           --}}
        {{------------------------------------------------------------------------------------------}}

        <div id="default-modal-2" tabindex="-1" class="modalResultadoConsulta flex justify-center items-center h-screen hidden">
            <div class="bg-white rounded-lg shadow-lg max-w-7xl w-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <span class="flex font-bold items-center after:content-['/'] sm:after:hidden after:mx-2 after:text-gray-200 dark:after:text-gray-500">Output</span>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white cerrarModalResultadoConsulta" data-modal-hide="default-modal">
                            <svg class="w-3 h-3" aria-hidden="true" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="p-4 md:p-5">
                        <div id="resultadoQuery" class="relative overflow-x-auto shadow-md sm:rounded-lg" style="max-width: 100%; overflow-x: auto; max-height: 400px;"></div>
                    </div>

                    <div class="flex justify-center border-t pt-4 md:p-5">
                    </div>
                </div>
            </div>
        </div>


        {{------------------------------------------------------------------------------------------}}
        {{--                        Modal del asistente para migrador                             --}}
        {{------------------------------------------------------------------------------------------}}

        <div id="default-modal" tabindex="-1" class="modal flex justify-center items-center h-screen hidden">
            <div class="bg-white rounded-lg shadow-lg max-w-5xl w-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <ol class="flex items-center w-full text-sm font-medium text-center text-gray-500 dark:text-gray-400 sm:text-base mx-12">
                            <li class="flex md:w-full items-center text-blue-600 dark:text-blue-500 sm:after:content-[''] after:w-full after:h-1 after:border-b after:border-gray-200 after:border-1 after:hidden sm:after:inline-block after:mx-6 xl:after:mx-10 dark:after:border-gray-700">
                                <span class="flex items-center after:content-['/'] sm:after:hidden after:mx-2 after:text-gray-200 dark:after:text-gray-500">
                                    1.- Seleccionar manejador "SqlServer/MySql"
                                </span>
                            </li>
                            <li class="flex md:w-full items-center after:content-[''] after:w-full after:h-1 after:border-b after:border-gray-200 after:border-1 after:hidden sm:after:inline-block after:mx-6 xl:after:mx-10 dark:after:border-gray-700">
                                <span id="check_image_2" class="flex items-center after:content-['/'] sm:after:hidden after:mx-2 after:text-gray-200 dark:after:text-gray-500">
                                    2.- Seleccionar la base de datos
                                </span>
                            </li>
                            <li class="flex items-center">
                                <span class="me-2">3.-</span>
                                Confirmar
                            </li>
                        </ol>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white close-modal" data-modal-hide="default-modal">
                            <svg class="w-3 h-3" aria-hidden="true" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>

                    <div class="p-4 md:p-5">

                        <div id="step-1-content">
                            <label for="manejadorBD" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Selecciona el manejador</label>
                            <select name="manejadorBD" id="manejadorBD" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="Manejador" selected>Manejador</option>
                                <option value="0">SqlServer</option>
                                <option value="1">MySql</option>
                            </select>
                        </div>

                        <div id="step-2-content" class="hidden">
                            <select name="escogerBDSqlServer" id="escogerBDSqlServer" class="hidden bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="BDSqlServer" selected>Base de datos SqlServer</option>
                                @foreach($databases as $database)
                                <option value="{{$database->DATABASE_NAME}}">{{$database->DATABASE_NAME}}</option>
                                @endforeach
                            </select>

                            <select name="escogerBDMySql" id="escogerBDMySql" class="hidden bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="BDMySql" selected>Base de datos MySql</option>
                                @foreach($databases2 as $database2)
                                <option value="{{$database2->SCHEMA_NAME2}}">{{$database2->SCHEMA_NAME2}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="step-3-content" class="hidden">
                            <p>¿Seguro que quiere migrar esa base de datos? Dale click en <strong>confirmar</strong> para hacer la migración.</p>
                        </div>
                        
                    </div>

                    <div class="flex justify-center border-t pt-4 md:p-5">
                        <button id="prev-btn" data-modal-hide="default-modal" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Antras</button>
                        <button id="next-btn" data-modal-hide="default-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Seguiente</button>
                        <button id="confirmarSqlServer" type="button" class="absolute hidden right-0 py-2.5 px-5 ms-3 mr-5 text-sm font-medium text-white focus:outline-none bg-green-500 rounded-lg border border-green-500 hover:bg-green-600 focus:z-10 focus:ring-4 focus:ring-green-100 dark:focus:ring-green-700">Confirmar sql server</button>
                        <button id="confirmarMySQL" type="button" class="absolute hidden right-0 py-2.5 px-5 ms-3 mr-5 text-sm font-medium text-white focus:outline-none bg-green-500 rounded-lg border border-green-500 hover:bg-green-600 focus:z-10 focus:ring-4 focus:ring-green-100 dark:focus:ring-green-700">Confirmar mysql</button>
                    </div>
                </div>
            </div>
        </div>


        {{------------------------------------------------------------------------------------------}}
        {{--                        Modal de inicio de sesión de sqlserver                        --}}
        {{------------------------------------------------------------------------------------------}}

        <div id="modalLoginSqlServer" tabindex="-1" class="flex justify-center items-center h-screen hidden">
            <div class="bg-white rounded-lg shadow-lg max-w-7xl w-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <span class="flex font-bold items-center after:content-['/'] sm:after:hidden after:mx-2 after:text-gray-200 dark:after:text-gray-500">Inicio de Sesión SqlServer</span>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white cerrarModalLoginSqlServer" data-modal-hide="default-modal">
                            <svg class="w-3 h-3" aria-hidden="true" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="p-4 md:p-5">
                        <div class="mb-6">
                            <label for="usuarioSqlServer" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email address</label>
                            <input type="text" id="usuarioSqlServer" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="@usuario SqlServer" required />
                        </div> 
                        <div class="mb-6">
                            <label for="passwordSqlServer" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                            <input type="password" id="passwordSqlServer" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="•••••••••" required />
                        </div>
                    </div>

                    <div class="flex justify-center border-t pt-4 md:p-5">
                        <button id="loginSqlServer" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Conectar</button>
                    </div>
                </div>
            </div>
        </div>


        {{------------------------------------------------------------------------------------------}}
        {{--                        Modal de inicio de sesión de mysql                            --}}
        {{------------------------------------------------------------------------------------------}}

        <div id="modalLoginMySql" tabindex="-1" class="flex justify-center items-center h-screen hidden">
            <div class="bg-white rounded-lg shadow-lg max-w-7xl w-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <span class="flex font-bold items-center after:content-['/'] sm:after:hidden after:mx-2 after:text-gray-200 dark:after:text-gray-500">Inicio de Sesión MySql</span>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white cerrarModalLoginMySql" data-modal-hide="default-modal">
                            <svg class="w-3 h-3" aria-hidden="true" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="p-4 md:p-5">
                        <div class="mb-6">
                            <label for="usuarioMySql" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Usuario MySql</label>
                            <input type="text" id="usuarioMySql" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="@usuario Mysql" required />
                        </div> 
                        <div class="mb-6">
                            <label for="passwordMySql" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                            <input type="password" id="passwordMySql" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="•••••••••" required />
                        </div>
                    </div>

                    <div class="flex justify-center border-t pt-4 md:p-5">
                        <button id="loginMySql" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Conectar</button>
                    </div>
                </div>
            </div>
        </div>


        {{------------------------------------------------------------------------------------------}}
        {{--                        Modal de resultados de la migración                           --}}
        {{------------------------------------------------------------------------------------------}}

        <div id="modalResultadosMigracion" tabindex="-1" class="flex justify-center items-center h-screen hidden">
            <div class="bg-white rounded-lg shadow-lg max-w-7xl w-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <span class="flex font-bold items-center after:content-['/'] sm:after:hidden after:mx-2 after:text-gray-200 dark:after:text-gray-500">Resultados de migración</span>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white cerrarModalResultadosMigracion" data-modal-hide="default-modal">
                            <svg class="w-3 h-3" aria-hidden="true" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="pr-5 pb-2 pt-2">
                        <div id="resultadosMigracionString" class="relative overflow-x-auto shadow-md sm:rounded-lg" style="max-width: 100%; overflow-x: auto; max-height: 400px;"></div>
                    </div>
                    <div class="flex justify-center border-t pt-4 md:p-5">
                    </div>
                </div>
            </div>
        </div>



        <div id="modalOverlay" class="modal-overlay"></div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="{{asset('/js/nuevaConsultaSqlServer.js')}}"></script>
        <script src="{{asset('/js/nuevaConsultaMySql.js')}}"></script>
        <script src="{{asset('/js/modalAsistente.js')}}"></script>
        <script src="{{asset('/js/ejecutarConsulta.js')}}"></script>
        <script src="{{asset('/js/login.js')}}"></script>
        <script src="{{asset('/libs/toastr/toastr.min.js')}}"></script>
        {{-- <script src="{{asset('/js/pruebamigrar.js')}}"></script> --}}

    </body>
</html>