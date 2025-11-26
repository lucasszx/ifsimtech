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
      <h1>Editar Tópico</h1>
      <p class="card-subtitle">Atualize o nome ou matéria vinculada.</p>

      <form action="{{ route('admin.topics.update', $topic) }}" method="POST" class="form-grid">
        @csrf
        @method('PUT')

        <div class="form-group">
          <label class="form-label">Nome</label>
          <input type="text" name="name" value="{{ $topic->name }}" class="input" required>
        </div>

        <div class="form-group">
          <label class="form-label">Matéria</label>
          <select name="subject_id" class="select" required>
            @foreach($subjects as $s)
              <option value="{{ $s->id }}" @if($topic->subject_id == $s->id) selected @endif>
                {{ $s->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="form-actions">
          <button class="btn-primary-lg">Salvar alterações</button>
          <a href="{{ route('admin.topics.index') }}" class="btn-ghost-lg">Voltar</a>
        </div>
      </form>
    </div>

  </div>
</x-app-layout>
