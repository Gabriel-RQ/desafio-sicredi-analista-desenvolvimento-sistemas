# Desafio 1

Automação desenvolvida em Python para coletar informações do site da Cooperativa Sicredi Conexão.

O script acessa a página [sicrediconexao.com.br](https://sicrediconexao.com.br), extrai os dados dos produtos e serviços descritos nas categorias **VOCÊ**, **EMPRESA** e **AGRONEGÓCIO** no menu **PRODUTOS**, e então os salva em um arquivo CSV estruturado.

## Dependências

O código foi desenvolvido e testado com **[Python 3.14.2](https://www.python.org/downloads/release/python-3142)**.  
O código utiliza os seguintes módulos para seu funcionamento:

- [csv](https://docs.python.org/3/library/csv.html): módulo da biblioteca padrão do Python para escrita e leitura de arquivos CSV;
- [itertools](https://docs.python.org/3/library/itertools.html): módulo da biblioteca padrão do Python para manipulação de iteradores;
- [Playwright](https://playwright.dev/python): framework de automação e testes web.

## Como executar

### 1. Configuração inicial

Considerar que os comandos a seguir são executados sempre tomando a pasta do projeto como raiz ([desafio-1](./)).

Recomenda-se a criação de um [ambiente virtual python](https://docs.python.org/pt-br/3/library/venv.html) para instalação local de pacotes. O ambiente virtual pode ser gerado com o seguinte comando:

```sh
python -m venv .env
```

A ativação do ambiente virtual criado é feita com os comandos abaixo.

Windows:

```sh
.\.env\Scripts\Activate.ps1
```

Linux:

```sh
source ./.env/bin/activate
```

OBS: O ambiente virtual pode ser desativado quando não for mais necessário com o comando `deactivate`.

### 2. Instalação e configuração do Playwright

Para instalar e configurar o framework de automação Playwright, execute os seguintes comandos:

```sh
pip install playwright
playwright install chromium
```

Mais informações podem ser encontradas na [documentação](https://playwright.dev/python/docs/library).

### 3. Executando o projeto

Com tudo configurado, o código pode ser executado com o comando:

```sh
python main.py
```

O progresso da extração será registrado no terminal, e, ao finalizar, um arquivo CSV nomeado `produtos-sicredi.csv` será salvo na pasta do projeto contendo as informações extraídas.
