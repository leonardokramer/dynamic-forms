# Dynamic Forms

Sistema de formulários dinâmicos desenvolvido com Laravel 12 e Filament 3.

## Requisitos

- PHP 8.2 ou superior
- Composer
- Node.js e npm
- MySQL, SQLite ou outro banco de dados suportado pelo Laravel

## Dependências

### Backend (PHP)
- **Laravel Framework 12.0** - Framework PHP principal
- **Filament 3.0** - Painel administrativo

## Instalação

1. **Clone o repositório**
   ```bash
   git clone <url-do-repositorio>
   cd dynamic-forms
   ```

2. **Instale as dependências PHP**
   ```bash
   composer install
   ```

3. **Instale as dependências JavaScript**
   ```bash
   npm install
   ```

4. **Configure o ambiente**
   ```bash
   cp .env.example .env
   ```

5. **Configure o banco de dados**
   - Edite o arquivo `.env` e configure as variáveis de banco de dados

6. **Execute as migrações**
   ```bash
   php artisan migrate
   ```

7. **Rode o projeto**
  ```bash
   php artisan serve
   ```

## Comandos Disponíveis

### Desenvolvimento
```bash
php artisan serve
```

### Banco de Dados
```bash
# Executa as migrações
php artisan migrate

# Executa o seeder
php artisan db:seed
```

## Seeders

O projeto inclui um seeder que cria usuários padrão para desenvolvimento:

### Comando do Seeder
```bash
php artisan db:seed
```

### Contas Criadas pelo Seeder

**Administrador:**
- Email: `admin@email.com`
- Senha: `secret123`
- Tipo: Administrador (`isAdmin: true`)

**Usuário Padrão:**
- Email: `user@email.com`
- Senha: `secret123`
- Tipo: Usuário comum (`isAdmin: false`)

## Comando Customizado

### Criar Usuário Administrador Manualmente

Se preferir não usar o seeder, você pode criar um usuário administrador manualmente:

```bash
php artisan make:admin
```

Este comando irá solicitar:
- Nome do administrador
- Email do administrador
- Senha do administrador

**Observação:** Se o email já existir, o usuário será atualizado para administrador.

## Tecnologias Utilizadas

- **Laravel 12** - Framework PHP
- **Filament 3** - Painel administrativo
