<div class="wrap">

  <div id="icon-options-general" class="icon32"></div>
  <h1><?php esc_attr_e('Documentação - GitHub API Repo Live Search'); ?></h1>

  <p>
    <?php esc_attr_e('Leia este manual para usar os shortcodes.'); ?>
  </p>
  <p>
    <a href="https://docs.github.com/en/rest/reference/search" target="_blank"><?php esc_attr_e('Mais informção sobre a API do GitHub.'); ?></a>
  </p>

  <div id="poststuff">

    <div id="post-body" class="metabox-holder columns-2">

      <!-- main content -->
      <div id="post-body-content">

        <div class="meta-box-sortables ui-sortable">

          <div class="postbox">

            <h2><span><?php esc_attr_e('SWPER - GitHub API Repo Live Search Shortcodes'); ?></span></h2>

            <div class="inside">
              <p><?php esc_attr_e(
                    'Adicione o shortcode do formulário da Live Search.'
                  ); ?></p>
              <br />
              <h3><?php esc_attr_e('Inserir o formulário de busca uma uma página ou post:'); ?></h3>
              <code>[github_repo_search_form]</code>
              <br />
              <br />
              <br />
              <h4>Proposta</h4>
              <ol>
                <li>página 1: Desenvolver uma página com input de texto de pesquisa(buscar repositórios enquanto digita), bem simples mesmo, estilo página do google com layout default do wordpress.</li>
                <li>página 2: Listar repositórios do próprio usuário (dono do repositório que foi selecionado na página 1).</li>
                <li>página 3: LIVRE: Utilize sua melhor técnica ou faça algo que na sua opinião seja relevante.</li>
              </ol>
              <br />
              <h4>Implementações inacabadas</h4>
              <p>Iniciei a criação dos widgets e tradução, mas não tive tempo para terminar, irei fazer assim que possível.
              <br />
              <br />
              <h4>Dificuldades</h4>
              <p>Este foi o primeiro plugin que criei para o Wordpress, tive dificuldade de saber como começar o projeto
                e no meio do projeto acabei me perdendo com tantas possibilidades de criação, mas acredito que o resultado
                final foi bem interessante.</p>
              <br />
              <br />
              <h4>Referências utilizadas para criação do plugin</h4>
              <ul>
                <li>https://docs.github.com/en/rest/overview/endpoints-available-for-github-apps</li>
                <li>https://docs.github.com/pt/authentication/keeping-your-account-and-data-secure/creating-a-personal-access-token</li>
                <li>https://github.com/SalsaBoy990/git-repo-search</li>
                <li>https://github.com/SalsaBoy990/git-repo-search</li>
                <li>https://wpmudev.com/blog/creating-database-tables-for-plugins/</li>
                <li>https://wordpress.org/</li>
                <li>https://stackoverflow.com/</li>
                <li>https://wordpress.stackexchange.com/</li>
                <li>E outras</li>
              </ul>
            </div>
            <!-- .inside -->

          </div>
          <!-- .postbox -->

        </div>
        <!-- .meta-box-sortables .ui-sortable -->

      </div>
      <!-- post-body-content -->
    </div>
    <!-- #post-body .metabox-holder .columns-2 -->

    <br class="clear">
  </div>
  <!-- #poststuff -->

</div> <!-- .wrap -->