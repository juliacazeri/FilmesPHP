🎬 Sistema de Cadastro de Filmes
Este é um sistema web simples de gerenciamento de filmes, que permite aos usuários se cadastrarem, fazerem login e gerenciarem seus próprios filmes (cadastrar, listar, editar e excluir). O projeto foi desenvolvido com foco em boas práticas de segurança e estruturação de código em PHP, utilizando MySQL com interface via phpMyAdmin.

🛠 Tecnologias Utilizadas
PHP (versão 7.4+)

MySQL (gerenciado via phpMyAdmin)

HTML5 + CSS3

Bootstrap 5

Sessões (para controle de login)

Prepared Statements (para segurança contra SQL Injection)

Funcionalidades
👤 Usuário
Cadastro com validação de login, senha e e-mail

Login com verificação de credenciais

Sessão de usuário iniciada após login

Logout para encerrar a sessão

🎥 Filmes
Cadastro de filmes com título e ano

Listagem dos filmes cadastrados pelo usuário logado

Edição e exclusão de filmes (com verificação de permissão)

Validações como ano válido e campos obrigatórios

🔐 Segurança
Uso de Prepared Statements para evitar SQL Injection

Verificação de sessão ativa para acesso a páginas restritas

Restrições por usuário (cada um só pode alterar ou excluir seus filmes)

📦 Requisitos
Servidor local com suporte a PHP e MySQL (como XAMPP, WAMP ou MAMP)

Acesso ao phpMyAdmin

⚙️ Como Usar:
