<x-app-layout>
  <div class="admin-page premium-admin">

    {{-- HEADER --}}
    <header class="admin-header">
      <div class="header-left">
        <h1>Tópicos</h1>
        <p>Gerencie, edite ou exclua tópicos cadastrados no sistema.</p>
      </div>

      <div class="header-right">
        <a href="{{ route('admin.topics.create') }}" class="btn-hero">
          + Novo tópico
        </a>
      </div>
    </header>

    {{-- TABELA --}}
    <div class="table-card" style="margin-top: 20px;">
      <table class="table">
        <thead>
          <tr>
            <th style="width: 40%;">Nome</th>
            <th style="width: 30%;">Matéria</th>
            <th style="width: 20%; text-align:center;">Ações</th>
          </tr>
        </thead>

        <tbody>
          @forelse($topics as $t)
            <tr>
              <td class="col-name">{{ $t->name }}</td>
              <td class="col-subject">{{ $t->subject->name }}</td>

              <td class="table-actions" style="text-align: center;">
                <a href="{{ route('admin.topics.edit', $t) }}" class="action-link edit">
                  Editar
                </a>

                <form action="{{ route('admin.topics.destroy', $t) }}" method="POST" style="display:inline">
                  @csrf @method('DELETE')
                    <button type="button"
                            class="action-link delete"
                            onclick="confirmDelete({{ $t->id }})">
                        Excluir
                    </button>

                    <form id="delete-form-{{ $t->id }}"
                        action="{{ route('admin.topics.destroy', $t) }}"
                        method="POST"
                        style="display:none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="3" class="empty-row">
                Nenhum tópico cadastrado.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

  </div>
</x-app-layout>

<script>
  function confirmDelete(id) {
    Swal.fire({
      title: "Confirmar exclusão",
      text: "Deseja realmente excluir este tópico?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Excluir",
      cancelButtonText: "Cancelar",
      confirmButtonColor: "#10b981",
      cancelButtonColor: "#6b7280",
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById(`delete-form-${id}`).submit();
      }
    });
  }
</script>
