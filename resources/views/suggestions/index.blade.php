<x-app-layout>
    <div class="max-w-3xl mx-auto p-6">
        <h1 class="text-2xl font-semibold mb-4">Sugestões de estudo</h1>
        <p class="text-sm text-gray-600">
            Aqui você verá recomendações geradas a partir dos seus últimos simulados.
            (Por enquanto é uma página placeholder — quando finalizar um simulado, as sugestões detalhadas aparecem na tela de resultado.)
        </p>
        <div class="mt-6">
            <a href="{{ route('results.history') }}" class="inline-flex px-4 py-2 rounded-lg border hover:bg-gray-50">
                Ver meus resultados
            </a>
        </div>
    </div>
</x-app-layout>
