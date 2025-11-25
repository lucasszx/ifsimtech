<x-app-layout>
    <div class="dashboard">
        <div class="dashboard-content">
            <h1>Bem-vindo ao IFSimTech</h1>
            <p>Crie simulados, acompanhe seus resultados e receba sugestões de estudo personalizadas.</p>

            <div class="dashboard-buttons">
                <a href="{{ route('exams.create') }}" class="btn btn-primary">
                    Simulados
                </a>
                <a href="{{ route('results.history') }}" class="btn btn-outline">
                    Histórico
                </a>
                <a href="{{ route('suggestions.index') }}" class="btn btn-outline">
                    Sugestões
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
