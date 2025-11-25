<x-app-layout>
    <div class="dashboard">
        <div class="dashboard-content">

            <h1>Bem-vindo ao IFSimTech</h1>
            <p class="subtitle">
                Crie simulados, acompanhe seu desempenho e receba sugestões de estudo inteligentes.
            </p>

            <div class="dashboard-buttons">
                <a href="{{ route('exams.create') }}" class="btn btn-primary" aria-label="Criar simulado">
                    Simulados
                </a>

                <a href="{{ route('results.history') }}" class="btn btn-outline" aria-label="Ver histórico de resultados">
                    Histórico
                </a>

                <a href="{{ route('suggestions.index') }}" class="btn btn-outline" aria-label="Ver sugestões de estudo">
                    Sugestões
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
