<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SaveBot - Modo Spider-Man</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Bangers&family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            /* Spider-Man Colors */
            --spiderman-red: #e53935;
            --spiderman-dark-red: #b71c1c;
            --spiderman-blue: #1e88e5;
            --spiderman-dark-blue: #0d47a1;
            --spiderman-black: #212121;
            --spiderman-white: #f5f5f5;
            --spiderman-accent: #ffc107;
            
            /* Dark mode variables */
            --dark-bg: radial-gradient(circle, #1a1a2e 0%, #121212 100%);
            --dark-text: #f5f5f5;
            --dark-card-bg: linear-gradient(135deg, #b71c1c 0%, #0d47a1 100%);
            --dark-card-shadow: 0 15px 35px rgba(229, 57, 53, 0.3);
        }
        
        @font-face {
            font-family: 'Comic Font';
            src: url('https://cdnjs.cloudflare.com/ajax/libs/comic-mono/0.0.1/ComicMono.woff2') format('woff2');
            font-weight: normal;
            font-style: normal;
        }
        
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            font-family: 'VT323', monospace;
            font-size: 20px;
            transition: all 0.5s ease;
            position: relative;
            overflow-x: hidden;
            /* Siempre en modo oscuro */
            background: var(--dark-bg);
            color: var(--dark-text);
        }
        
        /* Web pattern overlay */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M50 50 L0 100 L100 100 Z' fill='rgba(255,255,255,0.03)'/%3E%3C/svg%3E");
            background-size: 100px 100px;
            z-index: -1;
            pointer-events: none;
        }
        
        /* SaveBot Logo */
        .savebot-logo {
            font-family: 'Bangers', cursive;
            font-size: 60px;
            text-transform: uppercase;
            letter-spacing: 3px;
            text-align: center;
            margin-bottom: 30px;
            position: relative;
            display: inline-block;
            text-shadow: 5px 5px 0px rgba(0, 0, 0, 0.3);
            transform: rotate(-2deg);
        }
        .savebot-logo .save {
            color: var(--spiderman-red);
        }
        .savebot-logo .bot {
            color: var(--spiderman-blue);
        }
        .savebot-logo::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--spiderman-black);
            transform: rotate(-1deg);
        }
        
        /* Botón de la araña (como ícono, sin recuadro) */
        .spider-icon-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        .spider-button {
            background: transparent;
            border: none;
            outline: none;
            cursor: pointer;
            transition: transform 0.3s ease, color 0.3s ease;
            font-size: 35px;
            color: var(--spiderman-red); /* Color por defecto */
        }
        .spider-button.active {
            transform: scale(1.2) rotate(20deg);
            color: var(--spiderman-blue);
        }
        
        /* Action words */
        .action-word {
            position: absolute;
            font-family: 'Bangers', cursive;
            font-size: 30px;
            color: var(--spiderman-accent);
            transform: rotate(-15deg);
            background: var(--spiderman-black);
            padding: 5px 15px;
            border-radius: 10px;
            box-shadow: 3px 3px 0 rgba(0, 0, 0, 0.3);
            opacity: 0;
            animation: fadeOutIn 5s ease-in-out infinite;
            z-index: 100;
        }
        @keyframes fadeOutIn {
            0% { opacity: 0; transform: scale(0.5) rotate(-15deg); }
            5% { opacity: 1; transform: scale(1.2) rotate(-15deg); }
            15% { opacity: 1; transform: scale(1) rotate(-15deg); }
            25% { opacity: 0; transform: scale(0.5) rotate(-15deg); }
            100% { opacity: 0; transform: scale(0.5) rotate(-15deg); }
        }
        
        .upload-container {
            margin-top: 50px;
            margin-bottom: 80px;
            position: relative;
        }
        
        .upload-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            position: relative;
            z-index: 1;
            transition: all 0.5s ease;
            transform-style: preserve-3d;
            background: var(--dark-card-bg);
            box-shadow: var(--dark-card-shadow);
        }
        .upload-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(45deg, rgba(0, 0, 0, 0.1) 25%, transparent 25%), 
                linear-gradient(-45deg, rgba(0, 0, 0, 0.1) 25%, transparent 25%), 
                linear-gradient(45deg, transparent 75%, rgba(0, 0, 0, 0.1) 75%), 
                linear-gradient(-45deg, transparent 75%, rgba(0, 0, 0, 0.1) 75%);
            background-size: 20px 20px;
            background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
            z-index: -1;
        }
        
        /* Spider web corners */
        .web-corner {
            position: absolute;
            width: 150px;
            height: 150px;
            z-index: 0;
            opacity: 0.15;
            pointer-events: none;
        }
        .web-corner-tl {
            top: -30px;
            left: -30px;
        }
        .web-corner-tr {
            top: -30px;
            right: -30px;
            transform: scaleX(-1);
        }
        .web-corner-bl {
            bottom: -30px;
            left: -30px;
            transform: scaleY(-1);
        }
        .web-corner-br {
            bottom: -30px;
            right: -30px;
            transform: scale(-1);
        }
        
        .upload-form {
            padding: 40px;
            position: relative;
            z-index: 2;
            color: white;
        }
        
        /* File upload styling */
        .file-upload-container {
            position: relative;
            margin-bottom: 30px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            padding: 5px;
            border: 3px solid var(--spiderman-black);
            box-shadow: 5px 5px 0 rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        .file-upload-input {
            position: relative;
            z-index: 2;
            width: 100%;
            height: 60px;
            padding: 15px 70px 15px 20px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(5px);
            border: none;
            border-radius: 6px;
            font-family: 'VT323', monospace;
            font-size: 22px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .file-browse-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            height: 50px;
            width: 50px;
            border-radius: 50%;
            background: var(--spiderman-accent);
            color: var(--spiderman-black);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid var(--spiderman-black);
            font-size: 22px;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 3;
            transform-origin: center;
        }
        .file-browse-btn:hover {
            transform: scale(1.1) rotate(15deg);
            box-shadow: 0 0 15px rgba(255, 193, 7, 0.7);
        }
        .selected-files {
            margin-top: 10px;
            font-family: 'VT323', monospace;
            font-size: 18px;
            color: rgba(255, 255, 255, 0.8);
            text-shadow: 1px 1px 0 rgba(0, 0, 0, 0.3);
            animation: pulsate 2s infinite;
            word-break: break-all;
        }
        
        @keyframes pulsate {
            0% { opacity: 0.7; }
            50% { opacity: 1; }
            100% { opacity: 0.7; }
        }
        
        /* Comic style button */
        .btn-comic {
            font-family: 'Bangers', cursive;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 2px;
            padding: 12px 30px;
            background: var(--spiderman-red);
            color: white;
            border: 4px solid var(--spiderman-black);
            border-radius: 8px;
            box-shadow: 5px 5px 0 rgba(0, 0, 0, 0.5);
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
            text-shadow: 2px 2px 0 rgba(0, 0, 0, 0.3);
            transform: rotate(-2deg);
        }
        .btn-comic::before {
            content: '';
            position: absolute;
            top: -10px;
            left: -10px;
            width: 20px;
            height: 20px;
            background: white;
            opacity: 0.3;
            border-radius: 50%;
            transition: all 0.5s ease;
            transform: scale(1);
        }
        .btn-comic:hover {
            transform: translateY(-5px) rotate(0deg);
            box-shadow: 8px 8px 0 rgba(0, 0, 0, 0.5);
            background: var(--spiderman-blue);
        }
        .btn-comic:hover::before {
            transform: scale(20);
            opacity: 0;
        }
        .btn-comic:active {
            transform: translateY(3px) rotate(-1deg);
            box-shadow: 3px 3px 0 rgba(0, 0, 0, 0.5);
        }
        
        /* Progress Bar Comic Style */
        .progress-container {
            margin-top: 40px;
            position: relative;
        }
        .progress-comic {
            height: 30px;
            background: white;
            border: 3px solid var(--spiderman-black);
            border-radius: 8px;
            box-shadow: 5px 5px 0 rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
            margin-bottom: 15px;
        }
        .progress-bar-comic {
            height: 100%;
            background-image: repeating-linear-gradient(
                45deg,
                var(--spiderman-red),
                var(--spiderman-red) 10px,
                var(--spiderman-blue) 10px,
                var(--spiderman-blue) 20px
            );
            border-right: 3px solid var(--spiderman-black);
            transition: width 0.3s ease;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .progress-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-family: 'Press Start 2P', cursive;
            font-size: 14px;
            color: var(--spiderman-black);
            text-shadow: 1px 1px 0 rgba(255, 255, 255, 0.5);
            white-space: nowrap;
            z-index: 10;
        }
        .progress-status {
            text-align: center;
            font-family: 'VT323', monospace;
            font-size: 24px;
            color: white;
            text-shadow: 2px 2px 0 rgba(0, 0, 0, 0.5);
            margin-top: 10px;
        }
        
        /* Alert messages */
        .comic-alert {
            position: relative;
            padding: 15px 20px;
            margin-bottom: 25px;
            border: 3px solid var(--spiderman-black);
            border-radius: 8px;
            font-family: 'VT323', monospace;
            font-size: 22px;
            background: white;
            color: var(--spiderman-black);
            box-shadow: 5px 5px 0 rgba(0, 0, 0, 0.3);
            transform: rotate(-1deg);
        }
        .comic-alert::before {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 20px;
            width: 30px;
            height: 15px;
            background: white;
            border-right: 3px solid var(--spiderman-black);
            border-bottom: 3px solid var(--spiderman-black);
            transform: rotate(45deg);
        }
        .alert-success {
            background: #c8e6c9;
            border-color: #388e3c;
        }
        .alert-success::before {
            background: #c8e6c9;
            border-color: #388e3c;
        }
        .alert-danger {
            background: #ffcdd2;
            border-color: #d32f2f;
        }
        .alert-danger::before {
            background: #ffcdd2;
            border-color: #d32f2f;
        }
    </style>
</head>
<body>
    <!-- Botón de la arañita como botón interactivo -->
    <div class="spider-icon-container">
        <button id="spiderButton" class="spider-button">
            <i class="fas fa-spider"></i>
        </button>
    </div>
    
    <!-- Comic action words -->
    <div class="action-word" style="top: 20%; left: 15%;">¡POW!</div>
    <div class="action-word" style="top: 40%; right: 10%; animation-delay: 1.5s;">¡ZAP!</div>
    <div class="action-word" style="bottom: 20%; left: 25%; animation-delay: 3s;">¡BOOM!</div>
    
    <div class="container upload-container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <!-- SaveBot Logo -->
                <div class="text-center mb-4">
                    <h1 class="savebot-logo swing">
                        <span class="save">Save</span><span class="bot">Bot</span>
                    </h1>
                </div>
                
                <div class="upload-card">
                    <!-- Spider web corners -->
                    <svg class="web-corner web-corner-tl" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0,0 L100,100 M0,20 L80,100 M0,40 L60,100 M0,60 L40,100 M0,80 L20,100 M20,0 L100,80 M40,0 L100,60 M60,0 L100,40 M80,0 L100,20" stroke="white" stroke-width="2" fill="none"/>
                    </svg>
                    <svg class="web-corner web-corner-tr" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0,0 L100,100 M0,20 L80,100 M0,40 L60,100 M0,60 L40,100 M0,80 L20,100 M20,0 L100,80 M40,0 L100,60 M60,0 L100,40 M80,0 L100,20" stroke="white" stroke-width="2" fill="none"/>
                    </svg>
                    <svg class="web-corner web-corner-bl" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0,0 L100,100 M0,20 L80,100 M0,40 L60,100 M0,60 L40,100 M0,80 L20,100 M20,0 L100,80 M40,0 L100,60 M60,0 L100,40 M80,0 L100,20" stroke="white" stroke-width="2" fill="none"/>
                    </svg>
                    <svg class="web-corner web-corner-br" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0,0 L100,100 M0,20 L80,100 M0,40 L60,100 M0,60 L40,100 M0,80 L20,100 M20,0 L100,80 M40,0 L100,60 M60,0 L100,40 M80,0 L100,20" stroke="white" stroke-width="2" fill="none"/>
                    </svg>
                    
                    <div class="upload-form">
                        <!-- Message container for alerts -->
                        <div id="message"></div>
                        
                        <!-- Upload Form -->
                        <form id="uploadForm" action="{{ route('upload.file') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label" style="font-family: 'Press Start 2P', cursive; color: #fff;">
                                    ¡SELECCIONA TUS ARCHIVOS, HÉROE!
                                </label>
                                <div class="file-upload-container">
                                    <input type="text" class="file-upload-input" id="fileDisplay" value="Arrastra archivos aquí..." disabled>
                                    <input type="file" class="d-none" name="files[]" id="files" multiple required>
                                    <div class="file-browse-btn" id="browseBtn">
                                        <i class="fas fa-folder-open"></i>
                                    </div>
                                </div>
                                <div class="selected-files" id="selectedFiles"></div>
                            </div>
                            
                            <div class="text-center">
                                <button type="submit" class="btn-comic">
                                    <i class="fas fa-bolt me-2"></i>¡SUBIR AHORA!
                                </button>
                            </div>
                        </form>
                        
                        <!-- Progress container -->
                        <div class="progress-container">
                            <div class="progress-comic">
                                <div class="progress-bar-comic" id="progressBar" style="width: 0%;"></div>
                                <div class="progress-text" id="progressPercent">0%</div>
                            </div>
                            <div class="progress-status" id="progressStatus">¡LISTO PARA SALVAR EL DÍA!</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        $(document).ready(function(){
            // Muestra los nombres de los archivos seleccionados
            $('#files').on('change', function(){
                let fileNames = Array.from(this.files).map(file => file.name).join(', ');
                $('#fileDisplay').val(fileNames);
                $('#selectedFiles').html(fileNames);
            });
            
            // Abre el diálogo de selección de archivos al hacer clic en el botón
            $('#browseBtn').on('click', function(){
                $('#files').click();
            });
            
            // Función para animar el botón de la araña
            $('#spiderButton').on('click', function(){
                $(this).addClass('active');
                setTimeout(() => {
                    $(this).removeClass('active');
                }, 300);
            });
            
            // Envía el formulario vía AJAX con seguimiento de progreso
            $('#uploadForm').on('submit', function(e){
                e.preventDefault();
                var formData = new FormData(this);
  
                $.ajax({
                    xhr: function(){
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener('progress', function(e){
                            if(e.lengthComputable){
                                var percent = Math.round((e.loaded / e.total) * 100);
                                $('#progressBar').css('width', percent + '%');
                                $('#progressPercent').text(percent + '%');
                            }
                        });
                        return xhr;
                    },
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response){
                        $('#message').html('<div class="alert alert-success">' + response.message + '</div>');
                        $('#progressBar').css('width','0%');
                        $('#progressPercent').text('0%');
                    },
                    error: function(xhr){
                        let errorMsg = "Ocurrió un error al subir los archivos.";
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            if(Array.isArray(xhr.responseJSON.errors)){
                                errorMsg = xhr.responseJSON.errors.join("<br>");
                            } else if(typeof xhr.responseJSON.errors === "object"){
                                errorMsg = Object.values(xhr.responseJSON.errors).flat().join("<br>");
                            } else {
                                errorMsg = xhr.responseJSON.errors;
                            }
                        }
                        $('#message').html('<div class="alert alert-danger">' + errorMsg + '</div>');
                        $('#progressBar').css('width','0%');
                        $('#progressPercent').text('0%');
                    }
                });
            });
        });
    </script>
    
    <!-- CSS adicional para el botón de la araña -->
    <style>
        .spider-icon-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        .spider-button {
            background: transparent;
            border: none;
            outline: none;
            cursor: pointer;
            transition: transform 0.3s ease, color 0.3s ease;
            font-size: 35px;
            color: var(--spiderman-red);
        }
        .spider-button.active {
            transform: scale(1.2) rotate(20deg);
            color: var(--spiderman-blue);
        }
    </style>
</body>
</html>
