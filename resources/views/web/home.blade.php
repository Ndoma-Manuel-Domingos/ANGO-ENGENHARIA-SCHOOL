<!DOCTYPE html>
<html class="light" lang="pt">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>EduManage - Sobre Nós e Missão</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800&amp;display=swap" rel="stylesheet" />
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

        body {
            font-family: 'Lexend', sans-serif;
        }

    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-[#111318] dark:text-gray-100 transition-colors duration-200">
    <header class="sticky top-0 z-50 bg-white/80 dark:bg-background-dark/80 backdrop-blur-md border-b border-solid border-[#f0f2f4] dark:border-gray-800 px-4 md:px-20 lg:px-40 py-3">
        <div class="max-w-[1280px] mx-auto flex items-center justify-between whitespace-nowrap">
            <div class="flex items-center gap-2 text-primary">
                <span class="material-symbols-outlined text-3xl">school</span>
                <h2 class="text-[#111318] dark:text-white text-xl font-bold leading-tight tracking-[-0.015em]">EduManage</h2>
            </div>
            <div class="hidden md:flex flex-1 justify-end gap-8 items-center">
                <nav class="flex items-center gap-9">
                    <a class="text-[#111318] dark:text-gray-300 text-sm font-medium leading-normal hover:text-primary transition-colors" href="#">Funcionalidades</a>
                    <a class="text-[#111318] dark:text-gray-300 text-sm font-medium leading-normal hover:text-primary transition-colors" href="#">Preços</a>
                    <a class="text-primary dark:text-primary text-sm font-bold leading-normal" href="#">Sobre Nós</a>
                </nav>
                <div class="flex gap-3">
                    <a href="{{ route('login') }}" class="flex min-w-[84px] cursor-pointer items-center justify-center rounded-lg h-10 px-4 border border-gray-200 dark:border-gray-700 bg-transparent text-[#111318] dark:text-white text-sm font-bold hover:bg-gray-50 dark:hover:bg-gray-800 transition-all">
                        <span>Aceder ao Sistema</span>
                    </a>
                    <button class="flex min-w-[84px] cursor-pointer items-center justify-center rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold tracking-[0.015em] hover:bg-blue-700 transition-all shadow-md">
                        <span>Registar Minha Escola</span>
                    </button>
                </div>
            </div>
            <button class="md:hidden text-[#111318] dark:text-white">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </div>
    </header>
    <main class="max-w-[1280px] mx-auto overflow-hidden">
        <section class="relative px-4 py-20 md:py-32 flex flex-col items-center text-center overflow-hidden">
            <div class="absolute inset-0 opacity-10 dark:opacity-20 pointer-events-none">
                <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-primary to-transparent"></div>
            </div>
            <div class="relative z-10 max-w-4xl px-4">
                <span class="inline-block px-4 py-1.5 rounded-full bg-primary/10 text-primary text-sm font-bold uppercase tracking-wider mb-6">A Nossa Missão</span>
                <h1 class="text-4xl md:text-6xl font-black text-[#111318] dark:text-white leading-tight mb-8">
                    Digitalizing <span class="text-primary">Angolan</span> Education
                </h1>
                <p class="text-xl md:text-2xl text-[#616f89] dark:text-gray-400 font-light leading-relaxed mb-10">
                    Estamos a construir o futuro da educação em Angola, capacitando instituições com tecnologia de ponta para formar as próximas gerações de líderes.
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <div class="h-[400px] w-full max-w-5xl rounded-3xl bg-center bg-cover shadow-2xl overflow-hidden border-8 border-white dark:border-gray-800" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCZpH3zWaKgfctgeyBT5zdJD8t-pAAbgaKl8fZk0PgRHCguwj_38dKjBoLYE4VQ7OCwo5cpHjiHXZqHTjAvUwufb_sLorP8UJ9nVSnlLoMnUq4MSEqreIeBh0MtjZX0r4ZEtww-VkhiHHlDfm-gNUy3teFfdZXOusqSJhRbHrAIuTlmAIh2Jb8JXmpXwW4XmT23TwbIxzR7pIiGv47tN14vzMJFueK0GvB3OXZeNnDZXLcGsPeTYJg8-Iat9RWdvQ1mS8fg3mn0ACpX");'>
                        <div class="w-full h-full bg-gradient-to-t from-primary/30 to-transparent"></div>
                    </div>
                </div>
            </div>
        </section>
        <section class="px-4 py-16 bg-white dark:bg-gray-900 mx-4 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center items-center">
                <div class="flex flex-col gap-2">
                    <h3 class="text-5xl font-black text-primary">200+</h3>
                    <p class="text-[#616f89] dark:text-gray-400 font-medium uppercase tracking-widest text-sm">Escolas Conectadas</p>
                </div>
                <div class="flex flex-col gap-2">
                    <h3 class="text-5xl font-black text-primary">50k+</h3>
                    <p class="text-[#616f89] dark:text-gray-400 font-medium uppercase tracking-widest text-sm">Alunos Impactados</p>
                </div>
                <div class="flex flex-col gap-2">
                    <h3 class="text-5xl font-black text-primary">18</h3>
                    <p class="text-[#616f89] dark:text-gray-400 font-medium uppercase tracking-widest text-sm">Províncias Presentes</p>
                </div>
            </div>
        </section>
        <section class="px-4 py-24 @container">
            <div class="flex flex-col gap-4 mb-16 items-center text-center">
                <h2 class="text-[#111318] dark:text-white text-3xl font-black md:text-4xl">Os Nossos Valores</h2>
                <div class="w-20 h-1.5 bg-primary rounded-full"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 px-4">
                <div class="p-8 rounded-2xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 shadow-xl hover:shadow-2xl transition-all group">
                    <div class="w-16 h-16 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-4xl">lightbulb</span>
                    </div>
                    <h3 class="text-xl font-bold mb-4 dark:text-white">Inovação</h3>
                    <p class="text-[#616f89] dark:text-gray-400 leading-relaxed">
                        Buscamos constantemente novas formas de simplificar a educação através da tecnologia, antecipando as necessidades das escolas angolanas.
                    </p>
                </div>
                <div class="p-8 rounded-2xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 shadow-xl hover:shadow-2xl transition-all group">
                    <div class="w-16 h-16 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-4xl">visibility</span>
                    </div>
                    <h3 class="text-xl font-bold mb-4 dark:text-white">Transparência</h3>
                    <p class="text-[#616f89] dark:text-gray-400 leading-relaxed">
                        Promovemos uma gestão aberta e clara entre diretores, professores e encarregados de educação para um ecossistema escolar saudável.
                    </p>
                </div>
                <div class="p-8 rounded-2xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 shadow-xl hover:shadow-2xl transition-all group">
                    <div class="w-16 h-16 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-4xl">handshake</span>
                    </div>
                    <h3 class="text-xl font-bold mb-4 dark:text-white">Compromisso</h3>
                    <p class="text-[#616f89] dark:text-gray-400 leading-relaxed">
                        Dedicamo-nos diariamente ao sucesso de cada instituição parceira, garantindo suporte técnico e pedagógico de excelência.
                    </p>
                </div>
            </div>
        </section>
        <section class="px-4 py-24 bg-gray-50 dark:bg-gray-900/30 rounded-3xl mx-4 mb-20">
            <div class="flex flex-col gap-4 mb-16 px-4">
                <h2 class="text-[#111318] dark:text-white text-3xl font-black md:text-4xl">Nossa Equipa</h2>
                <p class="text-[#616f89] dark:text-gray-400 text-lg max-w-2xl">Liderada por especialistas apaixonados pela intersecção entre educação e tecnologia.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 px-4">
                <div class="flex flex-col gap-4 group">
                    <div class="aspect-square w-full rounded-2xl bg-center bg-cover shadow-lg border border-gray-200 dark:border-gray-700 grayscale group-hover:grayscale-0 transition-all duration-500 overflow-hidden" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCFtgEM5nXizQ8DrepYZ2rgGqJsPRsaRE6y1uVwCroCC9xvlVpKM9bDySlrN-_39ewHMvWWpaXqS7lYoCQDFQqrcjdo54xQa30Oa2tDhFsJh3a6F4zWAx-T_2is-_dhLGwpm-D6UiMBhPRcnRQk6UVr-QysSm5kctpbqhdg40x2KlyBBTk7qE3nHAaNnKltWcbBOv2TWZhd7o5RKKzn1wCJQBKFCHhyWoSJDVZypHzru0gS71bHKIlaz-lOOh6TctzL6s3Mes93q20I");'>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold dark:text-white">Dr. António Matos</h3>
                        <p class="text-primary text-sm font-medium">CEO &amp; Co-Fundador</p>
                        <div class="flex gap-2 mt-3 opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="material-symbols-outlined text-gray-400 text-lg cursor-pointer hover:text-primary">alternate_email</span>
                            <span class="material-symbols-outlined text-gray-400 text-lg cursor-pointer hover:text-primary">share</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-4 group">
                    <div class="aspect-square w-full rounded-2xl bg-center bg-cover shadow-lg border border-gray-200 dark:border-gray-700 grayscale group-hover:grayscale-0 transition-all duration-500 overflow-hidden" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDyKQZRim81eZaaghwkfhMHn-4UAWF3TRjZVSWGYPdX0PdJmJ4gYEQLMptcsmOXPZauUKha5WvwS-V3W3Ll_S0sR1Wj37Svg6rQZQP90MBKBmasns6mR3uqc_6R1byWaeNvO5gZkuvVfqS96rhhxEK1QdxUSLiDo_M2jsgbBjbwDgyXirRWsvMBxXmxTMJwQE-c_kaMzYAbd9XhjSOhwADKMSLeOiiceOXduVeGFivbtJXyaEy8wuLBV1Em4SJ9IGHL-Y2sX6CSneaK");'>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold dark:text-white">Engª. Maria Luísa</h3>
                        <p class="text-primary text-sm font-medium">CTO / Desenvolvimento</p>
                        <div class="flex gap-2 mt-3 opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="material-symbols-outlined text-gray-400 text-lg cursor-pointer hover:text-primary">alternate_email</span>
                            <span class="material-symbols-outlined text-gray-400 text-lg cursor-pointer hover:text-primary">share</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-4 group">
                    <div class="aspect-square w-full rounded-2xl bg-gray-200 dark:bg-gray-800 flex items-center justify-center grayscale group-hover:grayscale-0 transition-all duration-500 overflow-hidden">
                        <span class="material-symbols-outlined text-6xl text-gray-400">person</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold dark:text-white">Dr. Francisco Ngola</h3>
                        <p class="text-primary text-sm font-medium">Diretor Académico</p>
                        <div class="flex gap-2 mt-3 opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="material-symbols-outlined text-gray-400 text-lg cursor-pointer hover:text-primary">alternate_email</span>
                            <span class="material-symbols-outlined text-gray-400 text-lg cursor-pointer hover:text-primary">share</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-4 group">
                    <div class="aspect-square w-full rounded-2xl bg-gray-200 dark:bg-gray-800 flex items-center justify-center grayscale group-hover:grayscale-0 transition-all duration-500 overflow-hidden">
                        <span class="material-symbols-outlined text-6xl text-gray-400">person</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold dark:text-white">Dra. Beatriz Samba</h3>
                        <p class="text-primary text-sm font-medium">Sucesso do Cliente</p>
                        <div class="flex gap-2 mt-3 opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="material-symbols-outlined text-gray-400 text-lg cursor-pointer hover:text-primary">alternate_email</span>
                            <span class="material-symbols-outlined text-gray-400 text-lg cursor-pointer hover:text-primary">share</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="px-4 py-24 text-center">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-black mb-6 dark:text-white">Pronto para digitalizar a sua escola?</h2>
                <p class="text-[#616f89] dark:text-gray-400 text-lg mb-10">Junte-se à revolução educativa em Angola. Agende uma conversa com os nossos especialistas hoje mesmo.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button class="px-8 py-4 bg-primary text-white font-bold rounded-xl shadow-lg hover:bg-blue-700 transition-all">Começar Agora</button>
                    <button class="px-8 py-4 bg-white dark:bg-gray-800 text-primary dark:text-white border border-primary/20 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">Ver Demonstração</button>
                </div>
            </div>
        </section>
    </main>
    <footer class="bg-white dark:bg-[#0a1120] border-t border-gray-100 dark:border-gray-800 pt-16 pb-8">
        <div class="max-w-[1280px] mx-auto px-4 md:px-10 lg:px-40">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
                <div class="flex flex-col gap-6">
                    <div class="flex items-center gap-2 text-primary">
                        <span class="material-symbols-outlined text-3xl">school</span>
                        <h2 class="text-[#111318] dark:text-white text-xl font-bold leading-tight">EduManage</h2>
                    </div>
                    <p class="text-[#616f89] dark:text-gray-400 text-sm leading-relaxed">
                        Líder em soluções de tecnologia educativa em Angola. Transformando a educação através da digitalização inteligente.
                    </p>
                    <div class="flex gap-4">
                        <a class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-600 dark:text-gray-300 hover:bg-primary hover:text-white transition-all" href="#">
                            <span class="material-symbols-outlined text-lg">social_leaderboard</span>
                        </a>
                        <a class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-600 dark:text-gray-300 hover:bg-primary hover:text-white transition-all" href="#">
                            <span class="material-symbols-outlined text-lg">language</span>
                        </a>
                    </div>
                </div>
                <div class="flex flex-col gap-6">
                    <h3 class="text-[#111318] dark:text-white font-bold text-sm uppercase tracking-wider">Explorar</h3>
                    <nav class="flex flex-col gap-3">
                        <a class="text-[#616f89] dark:text-gray-400 text-sm hover:text-primary transition-colors" href="#">Funcionalidades</a>
                        <a class="text-[#616f89] dark:text-gray-400 text-sm hover:text-primary transition-colors" href="#">Preços</a>
                        <a class="text-[#616f89] dark:text-gray-400 text-sm hover:text-primary transition-colors" href="#">Segurança</a>
                    </nav>
                </div>
                <div class="flex flex-col gap-6">
                    <h3 class="text-[#111318] dark:text-white font-bold text-sm uppercase tracking-wider">Contactos</h3>
                    <div class="flex flex-col gap-4">
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary text-xl">location_on</span>
                            <span class="text-[#616f89] dark:text-gray-400 text-sm">Rua Luanda Sul, Talatona<br />Luanda, Angola</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-xl">call</span>
                            <span class="text-[#616f89] dark:text-gray-400 text-sm">+244 923 000 000</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-6">
                    <h3 class="text-[#111318] dark:text-white font-bold text-sm uppercase tracking-wider">Instituição</h3>
                    <button class="bg-primary text-white font-bold text-sm py-3 px-6 rounded-lg w-full shadow-md hover:bg-blue-700 transition-colors">
                        Fale Connosco
                    </button>
                    <p class="text-[#616f89] dark:text-gray-400 text-xs italic">
                        Junte-se à rede de educação inteligente.
                    </p>
                </div>
            </div>
            <div class="border-t border-gray-100 dark:border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-[#616f89] dark:text-gray-500 text-xs">© 2024 EduManage Angola. Todos os direitos reservados.</p>
                <div class="flex gap-6">
                    <a class="text-[#616f89] dark:text-gray-500 text-xs hover:underline" href="#">Privacidade</a>
                    <a class="text-[#616f89] dark:text-gray-500 text-xs hover:underline" href="#">Termos de Uso</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
