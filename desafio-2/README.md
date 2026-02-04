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

Obs: Logs de todas as operações são salvos em [storage/logs/laravel.log](./storage/logs/laravel.log)

### Modelos

Abaixo os modelos esperados para as requisições:

`POST /api/auth/register`
```json
{
    "name": "string",
    "email": "string",
    "password": "string"
}
```

`POST /api/auth/login`
```json
{
    "email": "string",
    "password": "string"
}
```

`POST /api/members`
`PUT /api/members/{id}`
`PATCH /api/members/{id}`
```json
{
    "cpf": "string",
    "name": "string",
    "phone": "string",
    "email": "string",
    "state": "string",
    "city": "string"
}
```


Os modelos esperados para cada requisição e resposta também podem ser conferidos a partir das rotas de [documentação](#documentação) e da [coleção do postman](./postman/).

## Dependências

O código foi desenvolvido e testado com **PHP 8.4.16** e **Laravel 12**, ambos instalados e configurados utilizando [Laravel Herd](https://herd.laravel.com).

Além de Laravel, o código também possui as seguintes dependências:
- [jwt-auth](https://github.com/PHP-Open-Source-Saver/jwt-auth): biblioteca para implementação de autenticação JWT.
- [laravel-pt-BR-localization](https://github.com/lucascudo/laravel-pt-BR-localization): pacote para localização das mensagens do laravel em português.
- [Scramble](https://github.com/dedoc/scramble): gerador de documentação OpenAPI.

## Como executar (Localmente)

Considerar a execução de todos os comandos abaixo a partir da pasta do projeto como raíz ([desafio-2](./)).

Para preparar o ambiente de maneira simples, execute o comando abaixo:

```bash
composer setup
```

Se tudo funcionar corretamente, pode-se pular para a [execução da api](#4-executando-a-api). Caso contrário, os passos [1](#1-instalando-dependências), [2](#2-preparando-o-ambiente) e [3](#3-base-de-dados) abaixo guiam a preparação manual do ambiente.

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
php artisan migrate:fresh --force
```

### 4. Executando a API

Com o ambiente preparado, basta executar o servidor da API com o comando:

```bash
php artisan serve
```

ou

```bash
composer dev
```

Caso o servidor não consiga rodar em nenhuma porta, o comando abaixo deve resolver:

```bash
php -d variables_order=GPCS artisan serve
```

O servidor será executado por padrão em `http://localhost:8000`.


## Como executar (Docker):

Considerar a execução de todos os comandos abaixo a partir da pasta do projeto como raíz ([desafio-2](./))

O repositório contêm os arquivos [Dockerfile](./Dockerfile) e [docker-compose](./docker-compose.yml), permitindo a execução da API em ambiente de containers com [Docker](https://www.docker.com). A versão da API executada em container utiliza uma base de dados MySQL. 

Para executar a orquestração dos serviços da API e da base de dados MySQL em containers, execute o seguinte comando:

```bash
docker compose up --build
```

Com os serviços rodando, basta [testar a api](#testar-a-api).

Posteriormente, para parar a execução dos serviços e remover os containers, imagens e volumes criados, basta executar:

```bash
docker compose down --rmi all --volumes
```

## Testar a API

A API pode ser testada a partir de qualquer cliente HTTP de maneira simples, enviando requisições para as rotas descritas [acima](#rotas).

Apesar disso, recomenda-se o uso do [Postman](https://www.postman.com), para o qual existe uma coleção pronta em [postman/](./postman/). A coleção pode ser importada pela interface do Postman, e traz exemplos de requisições para todas as rotas, com o fluxo de autenticação já configurado.

### Testes unitários e de integração

A API conta com uma cobertura de testes unitários e de integração para garantir robustez e corretude. Os testes podem ser executados com o comando abaixo:

```bash
php artisan test
```

ou

```bash
composer test
```

## Documentação

A API é documentada com a especificação OpenAPI. Para acessar a documentação interativa na web, basta [executar o servidor da API](#como-executar) e acessar o endereço: `http://localhost:8000/docs/api`. A especificação OpenAPI em formato JSON pode ser acessada em `http://localhost:8000/docs/api.json`

### Base de dados

A base de dados é modelada conforme o diagrama abaixo.

[![](https://mermaid.ink/img/pako:eNqtU01PwzAM_SuVz1XVpV8hVxCXCYkzqjSFxmsjLUmVpMDY-t_J2o0BQwIkfLKfYz_7ydlBYwQCA7Q3kreWq1pHwQaH1kW7OTiY1B5btJEU0f3yDD9x23TcRporvERRcbm5hHvu3LOx4pwR3KOXCqPGYnDFivtvkkMvPiXHWs8OF8Kic_iXiZ0PvS7hRvrtl-4K1ePf1Gj69W8l6jujf1buxHdcdBV4b5f_Jt9pwXGfJPv9BzVZVMOZsgaIobVSAFvzjcMYFNowZ4hh0qYG32FYEg51Atd82Pgaaj2Gup7rB2MUMG-HUGnN0HanYB7seH_vL1ALtNdm0B7YgqRTC2A7eAlhVSZFXhQpKa8WFcnyKoYtMLIok5TSrCxIRkhepfkYw-vEmiZlnpU0L2lGK0pJQWNAIb2xd_P9T99gfANbX-7Z?type=png)](https://mermaid.live/edit#pako:eNqtU01PwzAM_SuVz1XVpV8hVxCXCYkzqjSFxmsjLUmVpMDY-t_J2o0BQwIkfLKfYz_7ydlBYwQCA7Q3kreWq1pHwQaH1kW7OTiY1B5btJEU0f3yDD9x23TcRporvERRcbm5hHvu3LOx4pwR3KOXCqPGYnDFivtvkkMvPiXHWs8OF8Kic_iXiZ0PvS7hRvrtl-4K1ePf1Gj69W8l6jujf1buxHdcdBV4b5f_Jt9pwXGfJPv9BzVZVMOZsgaIobVSAFvzjcMYFNowZ4hh0qYG32FYEg51Atd82Pgaaj2Gup7rB2MUMG-HUGnN0HanYB7seH_vL1ALtNdm0B7YgqRTC2A7eAlhVSZFXhQpKa8WFcnyKoYtMLIok5TSrCxIRkhepfkYw-vEmiZlnpU0L2lGK0pJQWNAIb2xd_P9T99gfANbX-7Z)