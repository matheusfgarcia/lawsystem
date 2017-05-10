<header>
    <h1 class="title">Cardillo e Associados</h1>

    <div class="options">
        @if (Auth::check())
            <div class="dropdown">
              <button class="dropbtn"><i class="fa fa-plus"></i>Outros cadastros</button>
              <div class="dropdown-content">
                    <a href="{{ url('/clientes/criar') }}">Cliente</a>
                    <a href="{{ url('/advogados/criar') }}">Advogado</a>
                    <a href="{{ url('/contrarios/criar') }}">Processos</a>
                    <a href="{{ url('/tribunais/criar') }}">Tribunais</a>
                    <a href="{{ url('/varas/criar') }}">Varas</a>
              </div>
            </div>
        @endif

        @if (Route::has('login'))
            <div class="links">
                @if (Auth::check())
                    <a href="{{ url('/logout') }}"><i class="fa fa-sign-out"></i>Sair</a>
                @else
                    <a href="{{ url('/login') }}">Login</a>
                    <a href="{{ url('/register') }}">Register</a>
                @endif
            </div>
        @endif
    </div>
</header>