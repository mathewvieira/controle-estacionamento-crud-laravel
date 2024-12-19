# Teste Prático - Mathew Vieira

## Para Executar o Projeto

Com o "Comando Make (Makefile)" e o Docker instalado, executar em qualquer terminal o seguinte comando:

```bash
make setup
```

---

OBS: Caso esteja no Windows, o comando `make` pode ser instalado utilizando o Chocolatey:

```bash
choco install make
make setup
```

---

I. Após o comando `make setup` terminar de executar, você verá que no terminal foi gerada a seguinte mensagem:

```bash
Admin Token saved to storage/app/private/admin_token.txt

Authorization: Bearer 1|oAX1AdDE5N...
```

II. Você deve copiar a linha completa de Authorization e adicionar na seção de Headers do seu REST Client: PostMan, Insomnia, etc, para que seja possível acessar as rotas de GET (Listar todos), POST, PUT, PATCH e DELETE como gestor/gerente/admin, já que a única rota pública é a GET (Listar pesquisando por ID).

Dica: Pelo PostMan, você pode ir na tela inicial da sua Collection e selecionar a aba de Authorization. Após isso, marcar em Auth Type a opção Bearer Token e preencher o input de token com o código gerado no arquivo `storage/app/private/admin_token.txt`

## CRUD

Documentação:

<https://www.postman.com/mathewvieira/controle-estacionamento/example/40535769-e7e05ee9-bd7f-421d-9385-2c3c316091b4>

Rota Principal da API:

<http://localhost:8080/api/vehicles>

Endpoints e Verbos:

```text
GET|HEAD   api/vehicles       VehicleController@index
GET|HEAD   api/vehicles/{id}                ...@show
POST       api/vehicles                     ...@store
PUT|PATCH  api/vehicles/{id}                ...@update
DELETE     api/vehicles/{id}                ...@destroy
```

Para rodar os testes unitários:

```bash
make test
```
