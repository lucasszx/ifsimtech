<style>
    /* ------------------------------------------------------------------- */
    /* 1. Estiliza o contêiner principal (Borda clara) */
    /* ------------------------------------------------------------------- */
    .form-group .select2-container--default .select2-selection--multiple {
        /* Cor da borda do campo "Matéria" */
        border: 1px solid #7ed5b8 !important; 
        border-radius: 6px !important; 
        min-height: 44px;
        padding: 6px 12px;
        background-color: white; 
    }

    /* Estilo quando o Select2 está em foco (Borda de foco mais escura) */
    .form-group .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #38c172 !important; 
    }

    /* ------------------------------------------------------------------- */
    /* 2. Cor do Placeholder e Texto Selecionado (AJUSTE PARA O VERDE) */
    /* ------------------------------------------------------------------- */

    /* Regra para o texto selecionado (se houver texto no input) - #38c172 é o verde escuro */
    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        color: #38c172 !important; 
    }

    /* Regra específica para o Placeholder (o texto cinza "Selecione um ou mais tópicos") */
    .select2-container--default .select2-selection--multiple .select2-selection__placeholder {
        /* Define a cor do placeholder para o verde escuro do seu tema */
        color: #38c172 !important; 
    }
    
    /* Esta regra é mantida para caso o texto já tenha sido digitado/selecionado */
    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        color: #38c172 !important; 
    }

    /* ------------------------------------------------------------------- */
    /* 3. Estiliza os itens (tags) selecionados (Contorno verde) */
    /* ------------------------------------------------------------------- */
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: transparent !important; 
        border: 1px solid #38c172; 
        color: #38c172 !important; 
        font-weight: bold; 
        border-radius: 4px; 
        padding: 2px 8px;
        margin-top: 4px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #38c172 !important; 
        margin-right: 5px;
    }
</style>

<x-app-layout>
    <div class="page page-wide">
        <br>
        <h1 class="page-title">Editar questão #{{ $question->id }}</h1>

        <form method="POST" action="{{ route('admin.questions.update', $question) }}" enctype="multipart/form-data"
            class="card form">

            @csrf @method('PUT')

            <!-- TOP 4 CAMPOS -->
            <div class="grid-top-fields">

                <div class="form-group">
                    <label class="form-label">Matéria</label>
                    <select id="subject_id" name="subject_id" class="select">
                        @foreach($subjects as $s)
                            <option value="{{ $s->id }}" @selected($question->subject_id == $s->id)>
                                {{ $s->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Conteúdos (tópicos)</label>
                    {{-- MANTIDO: O atributo 'multiple' e 'name="topics[]"' garantem a seleção múltipla --}}
                    <select id="topics" name="topics[]" multiple class="select">
                        @foreach ($topics as $topic)
                            <option value="{{ $topic->id }}"
                                {{-- NOVO: Adicione o data-subject-id para o filtro do JS --}}
                                data-subject-id="{{ $topic->subject_id }}" 
                                @if (!empty(old('topics')) && in_array($topic->id, old('topics', [])))
                                    selected
                                @elseif (isset($question) && $question->topics->contains($topic->id))
                                    selected
                                @endif
                            >
                                {{ $topic->name }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <div class="form-group">
                    <label class="form-label">Ano</label>
                    <input type="number" name="year" class="input" value="{{ $question->year }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Origem</label>
                    <input type="text" name="source" class="input" value="{{ $question->source }}">
                </div>

            </div>

            <!-- IMAGEM + ENUNCIADO -->
            <div class="img-enun-grid">

                <div class="form-group">
                    <label class="form-label">Imagem atual</label>
                    @if($question->image_path)
                        <img src="{{ asset('storage/' . $question->image_path) }}" class="img-preview">
                    @endif
                </div>

                <div style="display: grid; gap: 1rem;">

                    <div class="form-group">
                        <label class="form-label">Trocar imagem (opcional)</label>
                        <input type="file" name="image" class="file" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Enunciado</label>
                        <textarea name="statement" class="textarea enunciado">
                                {{ $question->statement }}
                            </textarea>
                    </div>

                </div>
            </div>

            <!-- ALTERNATIVAS -->
            <div class="form-group">
                <label class="form-label">Alternativas</label>

                @php $opts = $question->options->keyBy('label'); @endphp

                <div class="alt-grid">
                    @foreach(['A', 'B', 'C', 'D', 'E'] as $opt)
                        <div class="alt-item">
                            <span class="alt-label">{{ $opt }}.</span>
                            <input type="text" class="input" name="{{ $opt }}" value="{{ $opts[$opt]->text ?? '' }}">
                        </div>
                    @endforeach

                    <!-- ✔ ALTERNATIVA CORRETA -->
                    <div class="alt-item">
                        <span class="alt-label">✔</span>
                        <select name="correct_label" class="select flex1">
                            @foreach(['A', 'B', 'C', 'D', 'E'] as $opt)
                                <option value="{{ $opt }}" @selected(optional($question->options->firstWhere('is_correct', true))->label == $opt)>
                                    {{ $opt }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>

            <div class="form-actions">
                <button class="btn btn-primary">Salvar</button>
                <a href="{{ route('admin.questions.index') }}" class="btn btn-outline">Voltar</a>
            </div>

        </form>
    </div>
</x-app-layout>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
$(document).ready(function () {

    // Salva todas as opções originais do select
    const allTopics = $("#topics option").clone();

    function applyFilter() {
        const subjectId = $("#subject_id").val();

        // IDs já selecionados
        const selected = $("#topics").val() || [];

        // Limpa o select
        $("#topics").empty();

        if (!subjectId) {
            // Se não tem matéria, adiciona tudo
            $("#topics").append(allTopics);
        } else {
            // Filtra apenas os tópicos da matéria escolhida
            const filtered = allTopics.filter(function () {
                return $(this).data("subject-id") == subjectId;
            });

            $("#topics").append(filtered);
        }

        // Restaura seleção mantida
        $("#topics").val(selected).trigger("change");
    }

    // Inicializa o select2 (apenas uma vez)
    $("#topics").select2({
        placeholder: "Selecione um ou mais tópicos",
        width: "100%"
    });

    // Aplica o filtro ao mudar matéria
    $("#subject_id").on("change", applyFilter);

    // Aplica o filtro ao carregar página (EDIT)
    applyFilter();
});
</script>
