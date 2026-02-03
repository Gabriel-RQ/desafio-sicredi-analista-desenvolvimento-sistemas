# Desafio 2 - Backend

API RESTful desenvolvida com PHP (Laravel), implementando as seguintes funcionalidades:

- Registro de usuários
- Autenticação JWT (login e logout)
- Conexão com banco de dados relacional
- Registro de *logs* das operações
- Documentação OpenAPI/Swagger
- CRUD de associado, contendo os dados:
  - CPF
  - Nome
  - Cidade
  - Estado
  - Telefone
  - E-mail

## Rotas

As seguintes rotas são disponibilizadas pela API:

```bash
# Rotas de autenticação
POST /api/auth/register
POST /api/auth/login
POST /api/auth/logout

# Rotas de associado
POST /api/members
GET /api/members
GET /api/members/{id}
PUT /api/members/{id}
PATCH /api/members/{id}
DELETE /api/members/{id}

# Rotas de documentação
GET /docs/api
GET /docs/api.json
```

## Dependências

O código foi desenvolvido e testado com **PHP 8.4.16** e **Laravel 12**, ambos instalados e configurados utilizando [Laravel Herd](https://herd.laravel.com).

Além de Laravel, o código também possui as seguintes dependências:
- [jwt-auth](https://github.com/PHP-Open-Source-Saver/jwt-auth): biblioteca para implementação de autenticação JWT.
- [laravel-pt-BR-localization](https://github.com/lucascudo/laravel-pt-BR-localization): pacote para localização das mensagens do laravel em português.
- [Scramble](https://github.com/dedoc/scramble): gerador de documentação OpenAPI.

## Como executar

Considerar a execução de todos os comandos abaixo a partir da pasta do [projeto](./).

### 1. Instalando dependências

Utilizar o comando abaixo para instalar as dependências do projeto:

```bash
composer install
```

### 2. Preparando o ambiente

Para execução, é necessário criar um arquivo `.env` na raíz do projeto, e copiar os conteúdos do arquivo de exemplo ([.env.example](./.env.example)).

Feito isso, é necessário gerar a chave da aplicação:

```bash
php artisan key:generate
```

Também é necessário gerar o segredo para os tokens JWT:

```bash
php artisan jwt:secret
```

Obs: Caso alguma confirmação seja solicitada, aceite.

### 3. Base de dados

A API utiliza uma base de dados relacional SQLite (já configurada), a fim de facilitar o desenvolvimento e testes. Apesar disso, configurá-la para o uso de uma base de dados mais robusta, como MySQL, é muito simples, e pode ser feito alterando os seguintes campos no arquivo de variáveis de ambiente ([.env](./.env)):

```conf
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=api-desafio-2-sicredi
DB_USERNAME=root # Preencher com usuário da base de dados
DB_PASSWORD= # Preencher com senha da base de dados
```

Obs: A base de dados deve ser criada no servidor MySQL previamente.

Com a base de dados configurada (SQLite, MySQL, etc...), executar o seguinte comando para gerar o schema:

```bash
php artisan migrate:fresh
```

### 4. Executando a API

Com o ambiente preparado, basta executar o servidor da API com o comando:

```bash
php artisan serve
```

Caso o servidor não consiga rodar em nenhuma porta, o comando abaixo deve resolver:

```bash
php -d variables_order=GPCS artisan serve
```

O servidor será executado por padrão em `http://localhost:8000`.


## Testar a API

A API pode ser testada a partir de qualquer cliente HTTP de maneira simples, enviando requisições para as rotas descritas [acima](#rotas).

Apesar disso, recomenda-se o uso do [Postman](https://www.postman.com), para o qual existe uma coleção pronta em [postman/](./postman/). A coleção pode ser importada pela interface do Postman, e traz exemplos de requisições para todas as rotas, com o fluxo de autenticação já configurado.

## Documentação

A API é documentada com a especificação OpenAPI. Para acessar a documentação interativa na web, basta acessar o endereço: `http://localhost:8000/docs/api`. A especificação OpenAPI em formato JSON pode ser acessada em `http://localhost:8000/docs/api.json`
