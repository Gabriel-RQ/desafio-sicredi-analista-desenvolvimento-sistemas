from csv import DictWriter
from playwright.sync_api import sync_playwright
from itertools import zip_longest

NOME_ARQUIVO = "./produtos-sicredi.csv"
CATEGORIAS = ["você", "empresa", "agronegócio"]


def extrair_dados() -> dict[str, list[str]]:
    """Utiliza Playwright para extrair as informações dos produtos e serviços listados nas categorias VOCÊ, EMPRESA e AGRONEGÓCIO."""

    dados_coletados = {}

    with sync_playwright() as p:
        browser = p.chromium.launch()
        page = browser.new_page()
        page.goto("https://sicrediconexao.com.br", wait_until="load")

        link_produtos = page.locator(".nav__item", has_text="Produtos")

        # Verifica a necessidade de aceitar cookies
        btn_cookies = page.get_by_test_id("all-cookies-button")
        if btn_cookies.is_visible():
            btn_cookies.click()

        # Me parece desnecessário clicar no link, visto que as informações estão apenas "escondidas", mas já renderizadas
        # Mesmo assim, optei por seguir as orientações do desafio.
        link_produtos.click()

        secoes = link_produtos.locator(".nav-dropdown__item")

        for secao in secoes.all():
            titulo_secao = (
                secao.locator(".nav-dropdown__heading")
                .text_content(timeout=1000)
                .strip()
            )

            if not titulo_secao.lower() in CATEGORIAS:
                continue

            dados_coletados[titulo_secao] = [
                l.text_content().strip()
                for l in secao.locator(".nav-dropdown__link").all()
            ]

        browser.close()

    return dados_coletados


def escrever_csv(dados_coletados: dict[str, list[str]], nome_arquivo: str):
    """Escreve os dados extraídos para um arquivo CSV em formato UTF-8."""

    with open(nome_arquivo, mode="w", encoding="utf-8", newline="") as saida:
        cabecalho = dados_coletados.keys()
        escritor = DictWriter(saida, fieldnames=cabecalho)
        escritor.writeheader()

        # Transforma os dados coletados no formato de linha do DictWriter -> { cabecalho1: dado1, cabecalho2: dado2, cabecalhon: dadon}
        linhas = [dados_coletados[secao] for secao in cabecalho]
        for itens_linha in zip_longest(*linhas, fillvalue=""):
            escritor.writerow(dict(zip(cabecalho, itens_linha)))


if __name__ == "__main__":
    try:
        print("Iniciando extração dos dados...")
        dados = extrair_dados()
        print("Dados extraídos.")
        print("Escrevendo arquivo CSV...")
        escrever_csv(dados, NOME_ARQUIVO)
        print("Arquivo escrito com sucesso.")
    except Exception as e:
        print(f"Ocorreu um erro ao extrair os dados. Detalhe: {e}")
