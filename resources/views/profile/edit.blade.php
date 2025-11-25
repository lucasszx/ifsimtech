<x-app-layout>
  <div class="page-profile">
    <!-- Cabeçalho -->
    <div class="profile-header">
      <h1>Perfil</h1>
      <p>Gerencie suas informações pessoais, senha e configurações da conta.</p>
    </div>

    <!-- Conteúdo -->
    <div class="profile-grid">
      <div class="profile-card">
        <div class="card-content">
          @include('profile.partials.update-profile-information-form')
        </div>
      </div>

      <div class="profile-card">
        <div class="card-content">
          @include('profile.partials.update-password-form')
        </div>
      </div>

      <div class="profile-card">
        <div class="card-content">
          @include('profile.partials.delete-user-form')
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
