# github-repo-live-search
Plugin Wordpress que implementa Live Search nos Repositórios do GitHub usando a API do GitHub, jQuery e Bootstrap

# Importante
Tive problemas para utilizar a API do GitHub, para resolver tive que criar uma chave privada e com ela fazer os testes, é provável que ela esteja inválida, então é imporante criar uma chave nova para fazer os testes e evitar o mesmo problema, o locar onde a chave deve ser inserida é no arquivo que fica no caminho abaixo:

github-repo-live-search-master/inc/API/GetData.php

Na linha 12

private const GITHUB_PERSONAL_ACCESS_TOKEN = 'SUA CHAVE PRIVAD VEM AQUI';

Para criar a chave pode utilizar a documentação abaixo:

https://docs.github.com/pt/authentication/keeping-your-account-and-data-secure/creating-a-personal-access-token

# Proposta
1. página 1: Desenvolver uma página com input de texto de pesquisa(buscar repositórios enquanto digita), bem simples mesmo, estilo página do google com layout default do wordpress.
2. página 2: Listar repositórios do próprio usuário (dono do repositório que foi selecionado na página 1).
3. página 3: LIVRE: Utilize sua melhor técnica ou faça algo que na sua opinião seja relevante.

# Implementações inacabadas
Iniciei a criação dos widgets e tradução, mas não tive tempo para terminar, irei fazer assim que possível.

# Dificuldades
Este foi o primeiro plugin que criei para o Wordpress, tive dificuldade de saber como começar o projeto e no meio do projeto acabei me perdendo com tantas possibilidades de criação, mas acredito que o resultado final foi bem interessante.

# Referências utilizadas para criação do plugin
https://docs.github.com/en/rest/overview/endpoints-available-for-github-apps
https://docs.github.com/pt/authentication/keeping-your-account-and-data-secure/creating-a-personal-access-token
https://github.com/SalsaBoy990/git-repo-search
https://github.com/SalsaBoy990/git-repo-search
https://wpmudev.com/blog/creating-database-tables-for-plugins/
https://wordpress.org/
https://stackoverflow.com/
https://wordpress.stackexchange.com/
E outras

GitHub REST API: https://docs.github.com/en/rest

