<style>
    .form-premium {
    display: flex;
    flex-direction: column;
    gap: 20px;
    }

    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 4px;
    }

    .form-actions {
        margin-top: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        justify-content: flex-end;
    }

    .btn-primary-lg {
        padding: 12px 30px;
        background-color: #10b981;
        color: #fff;
        border-radius: 10px;
        font-weight: 600;
        transition: 0.2s;
    }

    .btn-ghost-lg {
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        color: #555;
        background: transparent;
        border: 2px solid #ccc;
        transition: 0.2s;
    }

    .btn-ghost-lg:hover {
        border-color: #10b981;
        color: #10b981;
    }

</style>

<x-app-layout>
  <div class="admin-page premium-admin">

    <div class="create-card">
      <h1>Novo Tópico</h1>
      <p class="card-subtitle">Cadastre um novo tópico vinculado a uma matéria.</p>

      <form action="{{ route('admin.topics.store') }}" method="POST" class="form-premium">
        @csrf

        <div class="form-group">
          <label class="form-label">Nome</label>
          <input type="text" name="name" class="input" required>
        </div>

        <div class="form-group">
          <label class="form-label">Matéria</label>
          <select name="subject_id" class="select" required>
            <option value="">Selecione</option>
            @foreach($subjects as $s)
              <option value="{{ $s->id }}">{{ $s->name }}</option>
            @endforeach
          </select>
        </div>

        {{-- BOTÕES --}}
        <div class="form-actions">
          <button class="btn-primary-lg">Salvar tópico</button>
          <a href="{{ route('admin.topics.index') }}" class="btn-ghost-lg">Voltar</a>
        </div>
      </form>
    </div>

  </div>
</x-app-layout>
