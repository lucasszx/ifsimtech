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

Blade / Tailwind CSS

Node.js + NPM

Vite (build de assets)

📦 Requisitos para Rodar o Sistema

Antes de instalar, certifique-se de que possui:

PHP 8.1 ou superior

Composer

MySQL 5.7+ ou 8+

Node.js (>= 16)

NPM

🚀 Instalação do Sistema
1. Baixar o projeto

Via Git:

git clone https://github.com/lucasszx/ifsimtech.git
cd ifsimtech


Ou faça o download do .zip pelo GitHub e extraia.

2. Instalar dependências PHP
composer install

3. Instalar dependências do frontend
npm install
npm run build


Se desejar modo desenvolvimento:

npm run dev

4. Criar o arquivo .env

O projeto já fornece um modelo de configuração.

Crie o .env:

cp .env.example .env

Agora ANTES DE QUALQUER OUTRA COISA, editar o .env e incluir:

CACHE_DRIVER=file
SESSION_DRIVER=file

Edite os dados do banco conforme seu ambiente:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ifsimtech
DB_USERNAME=root
DB_PASSWORD=

5. Gerar a chave da aplicação
php artisan key:generate

🗄️ Banco de Dados

O sistema inclui o arquivo:

ifsimtech.sql


Esse arquivo possui o banco de dados limpo e organizado para testes.

5.1 Criar o banco no MySQL

Crie o banco com:

CREATE DATABASE ifsimtech CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

5.2 Importar o arquivo .sql

No phpMyAdmin:

Selecione o banco ifsimtech

Clique em Importar

Selecione o arquivo ifsimtech.sql

Clique em Executar

O banco estará pronto para uso.

▶️ Executando o Sistema

Após todas as etapas:

php artisan serve


Acesse no navegador:

http://localhost:8000

🔐 Credenciais de Acesso
Administrador

E-mail: admin@if.com

Senha: 123456

Permissão: Administrador (is_admin = 1)

📚 Estrutura Resumida
Pasta / Arquivo	Função
app/	Aplicação Laravel (Models, Controllers, etc.)
resources/views/	Views Blade
routes/web.php	Rotas da aplicação
public/	Arquivos públicos e assets compilados
database/	Migrations e seeds
ifsimtech.sql	Banco de dados preparado para importação
.env.example	Arquivo modelo de configuração
vite.config.js	Configuração do Vite
🧪 Testes (opcional)

Para executar testes, caso deseje:

php artisan test

📄 Sobre o Projeto

Este sistema foi desenvolvido como parte de um Trabalho de Conclusão de Curso (TCC), cujo objetivo é:

Criar uma plataforma de simulados para auxiliar candidatos no processo seletivo dos Institutos Federais, oferecendo prática, análise de desempenho e sugestões de estudo.

Autor: Lucas S.

📝 Licença

Este projeto é destinado exclusivamente a fins acadêmicos.
Sua utilização, modificação ou redistribuição deve respeitar os créditos ao autor.
