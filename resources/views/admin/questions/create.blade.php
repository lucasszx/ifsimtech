<x-app-layout>
    <div class="page page-narrow">
        <h1 class="page-title">Cadastrar questão (imagem + alternativas)</h1>

        <form method="POST"
              action="{{ route('admin.questions.store') }}"
              enctype="multipart/form-data"
              class="card form">
            @csrf

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Matéria</label>
                    <select name="subject_id" class="select" required>
                        <option value="">Selecione</option>
                        @foreach($subjects as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                    @error('subject_id') <p class="error-msg">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Conteúdo (tópico)</label>
                    <select name="topic_id" class="select">
                        <option value="">Sem tópico</option>
                        @foreach($topics as $t)
                            <option value="{{ $t->id }}">{{ $t->name }} — {{ $t->subject->name }}</option>
                        @endforeach
                    </select>
                    @error('topic_id') <p class="error-msg">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid-3">
                <div class="form-group">
                    <label class="form-label">Ano</label>
                    <input type="number" name="year" class="input" placeholder="2023">
                    @error('year') <p class="error-msg">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Origem</label>
                    <input type="text" name="source" class="input" placeholder="IFSul">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Imagem da questão (print)</label>
                <input type="file" name="image" accept="image/*" class="file">
                <p class="hint">PNG/JPG até 4 MB</p>
                @error('image') <p class="error-msg">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Enunciado (opcional — use se quiser texto além da imagem)</label>
                <textarea name="statement" rows="3" class="textarea"></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Alternativas</label>
                <div class="alt-grid">
                    @foreach (['A','B','C','D','E'] as $opt)
                        <div class="alt-item">
                            <span class="alt-label">{{ $opt }}.</span>
                            <input type="text" name="{{ $opt }}" class="input flex1" required>
                        </div>
                    @endforeach
                </div>
                @foreach (['A','B','C','D','E'] as $opt)
                    @error($opt) <p class="error-msg">{{ $message }}</p> @enderror
                @endforeach
            </div>

            <div class="form-group">
                <label class="form-label">Alternativa correta</label>
                <select name="correct_label" class="select w-40" required>
                    @foreach (['A','B','C','D','E'] as $opt)
                        <option value="{{ $opt }}">{{ $opt }}</option>
                    @endforeach
                </select>
                @error('correct_label') <p class="error-msg">{{ $message }}</p> @enderror
            </div>

            <div class="form-actions">
                <button class="btn btn-primary">Salvar questão</button>
                <a href="{{ route('exams.create') }}" class="btn btn-outline">Gerar Simulado</a>
            </div>
        </form>
    </div>
</x-app-layout>
