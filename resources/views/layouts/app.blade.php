<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'TuniVert - Environnement & Nature')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="">
    <meta name="description" content="">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600&family=Roboto&display=swap" rel="stylesheet">

    <!-- Icon Font -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries CSS -->
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/lightbox/css/lightbox.min.css') }}" rel="stylesheet">

    <!-- Bootstrap & Custom CSS -->
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <style>
        /* Styles chatbot */
        #chatbot-window {
            display: none;
            width: 300px;
            height: 400px;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            margin-top: 10px;
            flex-direction: column;
            overflow: hidden;
            position: relative;
        }
        #chatbot-messages {
            flex: 1;
            padding: 10px;
            overflow-y: auto;
            font-size: 14px;
        }
        #chatbot-input {
            width: 100%;
            padding: 5px;
            box-sizing: border-box;
        }
        #chatbot-widget button {
            cursor: pointer;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Spinner -->
    <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>

    {{-- Navbar --}}
    @include('layouts.navbar')

    {{-- Main content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.footer')

    <!-- Back to Top -->
    <a href="#" class="btn btn-primary btn-primary-outline-0 btn-md-square back-to-top"><i class="fa fa-arrow-up"></i></a>

    <!-- JS Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('lib/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('lib/lightbox/js/lightbox.min.js') }}"></script>

    <!-- Template JS -->
    <script src="{{ asset('js/main.js') }}"></script>

    {{-- Chatbot HTML --}}
    <div id="chatbot-widget" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000; display:flex; flex-direction:column; align-items:flex-end;">
        <button id="chatbot-toggle" class="btn btn-primary rounded-circle" style="width:60px; height:60px; font-size:24px;">💬</button>

        <div id="chatbot-window">
            <div style="background:#007bff; color:#fff; padding:10px; text-align:center; font-weight:bold;">
                Chatbot
            </div>
            <div id="chatbot-messages"></div>
            <div style="padding:10px; border-top:1px solid #ccc;">
                <input type="text" id="chatbot-input" placeholder="Posez votre question..." />
                <button id="chatbot-send" class="btn btn-primary btn-sm mt-2 w-100">Envoyer</button>
            </div>
        </div>
    </div>

    {{-- Chatbot JS --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('chatbot-toggle');
        const chatWindow = document.getElementById('chatbot-window');

        toggleBtn.addEventListener('click', () => {
            chatWindow.style.display = chatWindow.style.display === 'none' ? 'flex' : 'none';
        });

        document.getElementById('chatbot-send').addEventListener('click', sendMessage);
        document.getElementById('chatbot-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') sendMessage();
        });

        async function sendMessage() {
            let input = document.getElementById('chatbot-input');
            let message = input.value.trim();
            if (!message) return;

            let messagesContainer = document.getElementById('chatbot-messages');
            let userMsg = document.createElement('div');
            userMsg.innerHTML = `<strong>Vous:</strong> ${message}`;
            userMsg.style.marginBottom = '10px';
            messagesContainer.appendChild(userMsg);

            input.value = '';
            messagesContainer.scrollTop = messagesContainer.scrollHeight;

            // AJAX vers backend
            let response = await fetch("{{ route('chatbot.ask') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message })
            });

            let data = await response.json();

            let botMsg = document.createElement('div');
            botMsg.innerHTML = `<strong>Bot:</strong> ${data.response}`;
            botMsg.style.marginBottom = '10px';
            botMsg.style.color = '#007bff';
            messagesContainer.appendChild(botMsg);

            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    });
    </script>

    @stack('scripts')
</body>
</html>
