# Controle de Ponto Acadêmico (PHP + MySQL)

Projeto base para controle de frequência de alunos com:

- Cadastro de alunos
- Cadastro de grupos
- Associação de alunos aos grupos
- Cadastro de escalas
- Associação de grupos às escalas
- Registro de ponto (entrada e saída)

## Requisitos

- PHP 8.1+
- MySQL 8+
- Extensão PDO MySQL habilitada

## Configuração

1. Crie o banco e tabelas:

```bash
mysql -u root -p < database/schema.sql
```

2. Configure variáveis de ambiente (opcional):

```bash
export DB_HOST=127.0.0.1
export DB_PORT=3306
export DB_NAME=controle_ponto
export DB_USER=root
export DB_PASS=senha
```

3. Inicie servidor local:

```bash
php -S 0.0.0.0:8000
```

4. Acesse:

```text
http://localhost:8000/index.php
```

## Estrutura

- `index.php`: roteamento simples por parâmetro `page`
- `pages/`: telas da aplicação
- `includes/`: funções e layout comum
- `config/database.php`: conexão PDO
- `database/schema.sql`: criação de banco e tabelas

