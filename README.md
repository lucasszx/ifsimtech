📘 IFSIMTECH — Plataforma de Simulados para o IFSul

IFSIMTECH é uma plataforma web desenvolvida para auxiliar candidatos interessados em ingressar no Instituto Federal, oferecendo simulados personalizados, análise de desempenho, sugestões de estudo e um painel administrativo completo para gerenciamento de questões.

Este sistema foi criado como parte de um Trabalho de Conclusão de Curso (TCC) no eixo de Desenvolvimento de Sistemas.

🧩 Funcionalidades Principais

✏️ Geração de simulados personalizados

🧠 Resolução de questões com correção automática

📊 Feedback detalhado de desempenho

🕒 Histórico de simulados realizados

🎯 Metas de estudo baseadas nos pontos fracos

📚 Organização por matérias e tópicos

🛠️ Painel administrativo para:

cadastrar questões

editar alternativas

administrar tópicos

gerenciar matérias

🛠️ Tecnologias Utilizadas

PHP 8+

Laravel 10

MySQL 8

Blade + Tailwind CSS

Node.js + NPM

Vite (build front-end)

📦 Requisitos para Rodar o Sistema

Antes de instalar, certifique-se de ter instalado:

PHP 8.1 ou superior

Composer

MySQL 5.7+ ou 8+

Node.js 16+

NPM

🚀 Instalação do Sistema
1. Baixar o projeto

Via Git:

git clone https://github.com/lucasszx/ifsimtech.git
cd ifsimtech


Ou baixe o .zip pelo GitHub e extraia.

2. Instalar dependências PHP
composer install

3. Instalar dependências do frontend
npm install
npm run build


Se desejar modo desenvolvimento:

npm run dev

4. Criar e configurar o arquivo .env

O projeto já fornece um .env.example.

Crie o .env:

cp .env.example .env

ANTES DE QUALQUER OUTRA COISA, adicione:
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync


(isso evita erros de cache e sessão em instalações novas)

Agora edite os dados do banco conforme seu ambiente local:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ifsimtech
DB_USERNAME=root
DB_PASSWORD=


💡 Observações:

No XAMPP/Laragon/WAMP → senha normalmente vazia (DB_PASSWORD=)

Em Linux, se você configurou uma senha para root → coloque aqui.

5. Gerar a chave da aplicação
php artisan key:generate

🗄️ Banco de Dados

O projeto inclui:

ifsimtech.sql → banco completo preparado para testes.

5.1 Criar o banco no MySQL

Você pode criar de duas formas:

✔️ Opção 1 — Pelo terminal

Acessar MySQL:

mysql -u root -p


Criar o banco:

CREATE DATABASE ifsimtech
   CHARACTER SET utf8mb4
   COLLATE utf8mb4_unicode_ci;


Sair:

EXIT;


Importar o arquivo:

mysql -u root -p ifsimtech < ifsimtech.sql

✔️ Opção 2 — Pelo phpMyAdmin

Acesse: http://localhost/phpmyadmin

Clique em Novo

Nome do banco:

ifsimtech


Collation:

utf8mb4_unicode_ci


Criar.

Com o banco selecionado, vá na aba Importar

Selecione o arquivo ifsimtech.sql

Clique em Executar

Pronto — todas as tabelas estarão criadas.

▶️ Executando o Sistema

Após configurar tudo:

php artisan serve


Acesse:

http://localhost:8000

🔐 Credenciais de Acesso
Administrador

E-mail: admin@if.com

Senha: 123456

Permissão: Administrador (is_admin = 1)

📚 Estrutura Resumida
Pasta / Arquivo	Função
app/	Lógica da aplicação (Models, Controllers etc.)
resources/views/	Templates Blade
routes/web.php	Rotas da aplicação
public/	Arquivos públicos / assets compilados
database/	Migrations e seeds
ifsimtech.sql	Banco preparado para importação
.env.example	Arquivo modelo de configuração
vite.config.js	Configuração do Vite
🧪 Testes (opcional)
php artisan test

📄 Sobre o Projeto

Este sistema foi desenvolvido como parte de um Trabalho de Conclusão de Curso (TCC), com o objetivo de:

oferecer uma plataforma de simulados,

auxiliar candidatos no processo seletivo dos Institutos Federais,

fornecer análise de desempenho e sugestões de estudo.

Autor: Lucas S.

📝 Licença

Este projeto é destinado exclusivamente a fins acadêmicos.
Sua utilização, modificação ou redistribuição deve manter os créditos ao autor.
