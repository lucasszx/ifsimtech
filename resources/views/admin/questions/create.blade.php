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
        <h1 class="page-title">Cadastrar questão (imagem + alternativas)</h1>

        <form method="POST" action="{{ route('admin.questions.store') }}" enctype="multipart/form-data"
            class="card form">
            @csrf

            <div class="grid-top-fields">
                <div class="form-group">
                    <label class="form-label">Matéria</label>
                    <select name="subject_id" id="subject_id" class="select" required>
                        <option value="">Selecione</option>
                        @foreach($subjects as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
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
                    <input type="number" name="year" class="input" placeholder="2023">
                </div>

                <div class="form-group">
                    <label class="form-label">Origem</label>
                    <input type="text" name="source" class="input" placeholder="IFSul">
                </div>
            </div>

            <div class="grid-2">

                <!-- Imagem -->
                <div class="form-group">
                    <label class="form-label">Imagem da questão (print)</label>
                    <input type="file" name="image" accept="image/*" class="file">
                    <span class="hint inline-hint">PNG/JPG até 4 MB</span>
                    @error('image') <p class="error-msg">{{ $message }}</p> @enderror
                </div>

                <!-- Enunciado -->
                <div class="form-group">
                    <label class="form-label">Enunciado (opcional — use se quiser texto além da imagem)</label>
                    <textarea name="statement" class="textarea enunciado"></textarea>
                </div>

            </div>

            <div class="form-group">
                <label class="form-label">Alternativas</label>

                <div class="alt-grid">

                    @foreach (['A', 'B', 'C', 'D', 'E'] as $opt)
                        <div class="alt-item">
                            <span class="alt-label">{{ $opt }}.</span>
                            <input type="text" name="{{ $opt }}" class="input flex1" required>
                        </div>
                    @endforeach

                    <!-- Campo alternativa correta -->
                    <div class="alt-item">
                        <span class="alt-label">✔</span>
                        <select name="correct_label" class="select flex1" required>
                            @foreach (['A', 'B', 'C', 'D', 'E'] as $opt)
                                <option value="{{ $opt }}">{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                @foreach (['A', 'B', 'C', 'D', 'E'] as $opt)
                    @error($opt) <p class="error-msg">{{ $message }}</p> @enderror
                @endforeach
                @error('correct_label') <p class="error-msg">{{ $message }}</p> @enderror
            </div>

            <div class="form-actions">
                <button class="btn btn-primary">Salvar questão</button>
                <a href="{{ route('exams.create') }}" class="btn btn-outline">Gerar Simulado</a>
            </div>
        </form>
    </div>
</x-app-layout>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    // É essencial garantir que o jQuery esteja carregado ANTES deste script,
    // juntamente com os arquivos CSS e JS do Select2.

    // Armazena todas as opções de tópico originais como clones para referência futura.
    let allTopicOptions = [];

    // Função para inicializar/atualizar o Select2
    function initializeSelect2() {
        if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
            // Destrói a instância anterior, se houver
            if ($('#topics').data('select2')) {
                $('#topics').select2('destroy');
            }
            
            // Inicializa o Select2 com o visual de tags
            $('#topics').select2({
                placeholder: "Selecione um ou mais tópicos",
                allowClear: true,
                width: '100%',
                dropdownAutoWidth: true 
                // REMOVIDO: theme: "classic" -> Voltamos ao tema "default" para manter a estrutura
            });
        }
    }

    // Função para filtrar e atualizar as opções do <select>
    function updateTopics() {
        const subjectSelect = document.getElementById("subject_id");
        const topicSelect = document.getElementById("topics");
        const subjectId = subjectSelect.value;
        
        // Salva os IDs dos tópicos atualmente selecionados (útil ao mudar de matéria)
        const previouslySelectedTopicIds = Array.from(topicSelect.options)
                                                .filter(opt => opt.selected)
                                                .map(opt => opt.value);
        
        // Limpa o select
        topicSelect.innerHTML = '';
        
        // Destrói o Select2 para manipular o <select> nativo
        if (typeof jQuery !== 'undefined' && $('#topics').data('select2')) {
            $('#topics').select2('destroy');
        }
        
        if (subjectId) {
            // Filtra as opções cujos data-subject-id correspondem ao ID da matéria
            const filteredOptions = allTopicOptions.filter(
                opt => opt.dataset.subjectId === subjectId
            );

            if (filteredOptions.length > 0) {
                filteredOptions.forEach(opt => {
                    // Cria uma cópia da opção para inserir no DOM
                    const newOption = opt.cloneNode(true); 
                    
                    // Re-seleciona os que estavam selecionados, se aplicável
                    if (previouslySelectedTopicIds.includes(newOption.value)) {
                        newOption.selected = true;
                    } else {
                        newOption.selected = false;
                    }
                    topicSelect.appendChild(newOption);
                });
            } else {
                const empty = document.createElement("option");
                empty.value = "";
                empty.textContent = "Nenhum tópico disponível para esta matéria";
                empty.disabled = true;
                topicSelect.appendChild(empty);
            }
        }
        
        // Re-inicializa o Select2 após a atualização das opções
        initializeSelect2();
    }


    document.addEventListener("DOMContentLoaded", function () {
        const topicSelect = document.getElementById("topics");
        const subjectSelect = document.getElementById("subject_id");
        
        // 1. Preenche a lista de referência 'allTopicOptions' com clones
        // Mapeia todas as opções existentes no carregamento inicial para a variável de referência
        allTopicOptions = Array.from(topicSelect.options).map(opt => opt.cloneNode(true));

        // 2. Limpa o select nativo e inicializa o Select2
        topicSelect.innerHTML = '';
        initializeSelect2(); 

        // 3. Adiciona o listener para o filtro
        subjectSelect.addEventListener("change", updateTopics);

        // 4. Dispara o filtro na carga (útil para edição ou recarga do formulário)
        if (subjectSelect.value) {
            updateTopics();
        }
    });
</script>