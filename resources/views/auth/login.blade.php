<!DOCTYPE html>

<html class="light" lang="pt-br">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>SOFTWARE DE GESTÃO ESCOLAR | {{ env('APP_NAME') }}</title>
    <!-- Google Fonts: Lexend -->
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet" />
    <!-- Material Symbols Outlined -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class"
            , theme: {
                extend: {
                    colors: {
                        "primary": "#135bec"
                        , "background-light": "#f6f6f8"
                        , "background-dark": "#101622"
                    , }
                    , fontFamily: {
                        "display": ["Lexend", "sans-serif"]
                    }
                    , borderRadius: {
                        "DEFAULT": "0.25rem"
                        , "lg": "0.5rem"
                        , "xl": "0.75rem"
                        , "full": "9999px"
                    }
                , }
            , }
        , }

    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL'0, 'wght'400, 'GRAD'0, 'opsz'24;
        }

        .bg-geometric {
            background-color: #f6f6f8;
            background-image: radial-gradient(#135bec15 1px, transparent 1px), radial-gradient(#135bec15 1px, #f6f6f8 1px);
            background-size: 40px 40px;
            background-position: 0 0, 20px 20px;
        }

        .dark .bg-geometric {
            background-color: #101622;
            background-image: radial-gradient(#ffffff08 1px, transparent 1px), radial-gradient(#ffffff08 1px, #101622 1px);
        }

    </style>
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-[#111318] dark:text-white transition-colors duration-300">
    <div class="relative flex min-h-screen w-full flex-col items-center justify-center bg-geometric overflow-hidden">
        <!-- Login Container -->
        <div class="z-10 w-full max-w-[440px] px-6">
            <!-- Logo Section -->
            <div class="flex flex-col items-center mb-8">
                <div class="bg-primary p-3 rounded-xl shadow-lg shadow-primary/20 mb-4">
                    <a href="{{ route('site.home-principal') }}">
                        <svg class="size-8 text-white" fill="none" viewbox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" d="M39.475 21.6262C40.358 21.4363 40.6863 21.5589 40.7581 21.5934C40.7876 21.655 40.8547 21.857 40.8082 22.3336C40.7408 23.0255 40.4502 24.0046 39.8572 25.2301C38.6799 27.6631 36.5085 30.6631 33.5858 33.5858C30.6631 36.5085 27.6632 38.6799 25.2301 39.8572C24.0046 40.4502 23.0255 40.7407 22.3336 40.8082C21.8571 40.8547 21.6551 40.7875 21.5934 40.7581C21.5589 40.6863 21.4363 40.358 21.6262 39.475C21.8562 38.4054 22.4689 36.9657 23.5038 35.2817C24.7575 33.2417 26.5497 30.9744 28.7621 28.762C30.9744 26.5497 33.2417 24.7574 35.2817 23.5037C36.9657 22.4689 38.4054 21.8562 39.475 21.6262ZM4.41189 29.2403L18.7597 43.5881C19.8813 44.7097 21.4027 44.9179 22.7217 44.7893C24.0585 44.659 25.5148 44.1631 26.9723 43.4579C29.9052 42.0387 33.2618 39.5667 36.4142 36.4142C39.5667 33.2618 42.0387 29.9052 43.4579 26.9723C44.1631 25.5148 44.659 24.0585 44.7893 22.7217C44.9179 21.4027 44.7097 19.8813 43.5881 18.7597L29.2403 4.41187C27.8527 3.02428 25.8765 3.02573 24.2861 3.36776C22.6081 3.72863 20.7334 4.58419 18.8396 5.74801C16.4978 7.18716 13.9881 9.18353 11.5858 11.5858C9.18354 13.988 7.18717 16.4978 5.74802 18.8396C4.58421 20.7334 3.72865 22.6081 3.36778 24.2861C3.02574 25.8765 3.02429 27.8527 4.41189 29.2403Z" fill="currentColor" fill-rule="evenodd"></path>
                        </svg>
                    </a>
                </div>
                <h1 class="text-[#111318] dark:text-white tracking-tight text-2xl font-bold leading-tight text-center">{{ env('APP_NAME') }}</h1>
                <p class="text-[#616f89] dark:text-gray-400 text-sm font-normal text-center mt-1">Bem-vindo! Por favor, entre na sua conta.</p>
            </div>
            <!-- Login Card -->
            <div class="bg-white dark:bg-[#1c2433] rounded-xl shadow-xl shadow-black/5 p-8 border border-[#e5e7eb] dark:border-gray-800">
                <form class="space-y-5" action="{{ route('login_sistem') }}" method="post">
                    @csrf
                    <!-- Email Field -->
                    <div class="flex flex-col gap-2">
                        <label for="user" class="text-[#111318] dark:text-gray-200 text-sm font-medium leading-normal">E-mail ou Usuário</label>
                        <div class="relative">
                            <input id="user" name="user" class="form-input flex w-full rounded-lg text-[#111318] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/20 border border-[#dbdfe6] dark:border-gray-700 bg-white dark:bg-[#242d3d] focus:border-primary h-12 placeholder:text-[#616f89] dark:placeholder:text-gray-500 px-4 text-sm font-normal leading-normal transition-all" placeholder="nome@escola.com" type="text" value="" />
                        </div>
                    </div>
                    <!-- Password Field -->
                    <div class="flex flex-col gap-2">
                        <label for="password" class="text-[#111318] dark:text-gray-200 text-sm font-medium leading-normal">Senha</label>
                        <div class="flex w-full items-stretch rounded-lg">
                            <input id="password" name="password" class="form-input flex w-full min-w-0 flex-1 rounded-l-lg text-[#111318] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/20 border border-[#dbdfe6] dark:border-gray-700 bg-white dark:bg-[#242d3d] focus:border-primary h-12 placeholder:text-[#616f89] dark:placeholder:text-gray-500 px-4 text-sm font-normal leading-normal transition-all border-r-0" placeholder="Sua senha segura" type="password" value="" />
                            <div class="text-[#616f89] flex border border-[#dbdfe6] dark:border-gray-700 bg-white dark:bg-[#242d3d] items-center justify-center px-3 rounded-r-lg border-l-0 cursor-pointer hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                            </div>
                        </div>
                    </div>
                    <!-- Actions Row -->
                    <div class="flex items-center justify-between py-1">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input class="rounded border-gray-300 dark:border-gray-700 text-primary focus:ring-primary bg-white dark:bg-[#242d3d] size-4" type="checkbox" />
                            <span class="text-sm text-[#616f89] dark:text-gray-400 group-hover:text-[#111318] dark:group-hover:text-white transition-colors">Lembrar de mim</span>
                        </label>
                        <a class="text-sm font-medium text-primary hover:underline" href="#">Esqueceu sua senha?</a>
                    </div>
                    <!-- Submit Button -->
                    <button class="w-full bg-primary hover:bg-primary/90 text-white font-semibold py-3 px-4 rounded-lg shadow-md shadow-primary/20 transition-all flex items-center justify-center gap-2 group" type="submit">
                        <span>Entrar</span>
                        <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">login</span>
                    </button>
                </form>
                <!-- Footer Separator -->
                <div class="relative my-8">
                    <div aria-hidden="true" class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-[#f0f2f4] dark:border-gray-800"></div>
                    </div>
                    <div class="relative flex justify-center text-xs uppercase">
                        @if(session()->has('danger'))
                        <span class="bg-white dark:bg-[#3a1c1c] px-2 text-[#e25555] dark:text-red-500 tracking-wider">{{ session()->get('danger') }}</span>
                        @else
                        <span class="bg-white dark:bg-[#1c2433] px-2 text-[#616f89] dark:text-gray-500 tracking-wider">Acesso Restrito</span>
                        @endif
                    </div>
                </div>
                <!-- Footer Note -->
                <p class="text-center text-xs text-[#616f89] dark:text-gray-500 leading-relaxed">
                    Precisa de ajuda? Entre em contato com o <a class="text-primary hover:underline" href="#">Suporte de TI</a> da sua instituição.
                </p>
            </div>
            <!-- Global Footer -->
            <div class="mt-8 flex flex-col items-center gap-4">
                <p class="text-[11px] text-[#616f89] dark:text-gray-500 font-medium tracking-wide uppercase">
                    © Angoengenharia e sistemas informático v2.4.0
                </p>
                <div class="flex gap-4">
                    <button class="size-8 rounded-full bg-white dark:bg-[#1c2433] border border-[#e5e7eb] dark:border-gray-800 flex items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors shadow-sm" onclick="document.documentElement.classList.toggle('dark')" title="Alternar Tema">
                        <span class="material-symbols-outlined text-[18px]">dark_mode</span>
                    </button>
                    <button class="size-8 rounded-full bg-white dark:bg-[#1c2433] border border-[#e5e7eb] dark:border-gray-800 flex items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors shadow-sm" title="Idioma">
                        <span class="material-symbols-outlined text-[18px]">language</span>
                    </button>
                </div>
            </div>
        </div>
        <!-- Decorative Elements -->
        <div class="absolute top-[-10%] left-[-5%] size-[400px] bg-primary/5 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-[-10%] right-[-5%] size-[400px] bg-primary/5 rounded-full blur-[100px]"></div>
    </div>
</body>
</html>
