<div>
    <form action="" method="">
        <label for="search">
        <?php
            $inputSearch = null;
            $favorite = (isset($_GET['favorite']) && $_GET['favorite'] == true)? $_GET['favorite'] : null;
            $owner = isset($_GET['owner'])? $_GET['owner'] : null;

            if($favorite != null) {
                _e('Favoritos');
            } else {
                if($owner != null) {
                    _e('Consultar Repositórios do Usuário "' . $owner . '": ');
                } else {
                    _e('Consultar Repositórios: ');
                }
                $inputSearch = '<input readonly style="width: 298px; height: 44px; font-size: 16px;" type="search" name="search" id="github-search-field" placeholder="PHP, JavaScript"><br /><br />';
            }
            echo "</label><br>";
            echo $inputSearch;
        ?>
        <div class="accordion" id="accordionOwnerData" style="display: none;">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOwnerData" aria-expanded="true" aria-controls="collapseOne">
                        Clique aqui para exibir os dados do usuário selecionado: <?= $_GET['owner']? $_GET['owner'] : ''; ?>
                    </button>
                </h2>
                <div id="collapseOwnerData" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionOwnerData" display="none">
                    <div class="accordion-body">
                        <label for="owner"><?php _e('Usuário:'); ?></label><br>
                        <input readonly style="width: 298px; height: 44px; font-size: 16px;" type="name" name="name" id="github-owner-field" placeholder="owner">
                        <br />
                        <label for="name"><?php _e('Nome:'); ?></label><br>
                        <input readonly style="width: 298px; height: 44px; font-size: 16px;" type="name" name="name" id="github-name-field" placeholder="name">
                        <br />
                        <label for="company"><?php _e('Companhia:'); ?></label><br>
                        <input readonly style="width: 298px; height: 44px; font-size: 16px;" type="company" name="company" id="github-company-field" placeholder="company">
                        <br />
                        <label for="blog"><?php _e('Blog:'); ?></label><br>
                        <input readonly style="width: 298px; height: 44px; font-size: 16px;" type="blog" name="blog" id="github-blog-field" placeholder="blog">
                        <br />
                        <label for="location"><?php _e('Local:'); ?></label><br>
                        <input readonly style="width: 298px; height: 44px; font-size: 16px;" type="location" name="location" id="github-location-field" placeholder="location">
                        <br />
                        <label for="email"><?php _e('Email:'); ?></label><br>
                        <input readonly style="width: 298px; height: 44px; font-size: 16px;" type="email" name="email" id="github-email-field" placeholder="email">
                        <br />
                        <label for="hireable"><?php _e('Hireable:'); ?></label><br>
                        <input readonly style="width: 298px; height: 44px; font-size: 16px;" type="hireable" name="hireable" id="github-hireable-field" placeholder="hireable">
                        <br />
                        <label for="bio"><?php _e('Bio:'); ?></label><br>
                        <input readonly style="width: 298px; height: 44px; font-size: 16px;" type="bio" name="bio" id="github-bio-field" placeholder="bio">
                        <br />
                        <label for="twitter_username"><?php _e('Twitter:'); ?></label><br>
                        <input readonly style="width: 298px; height: 44px; font-size: 16px;" type="twitter_username" name="twitter_username" id="github-twitter_username-field" placeholder="twitter_username">
                        <br />
                        <label for="public_repos"><?php _e('Repositórios Públicos:'); ?></label><br>
                        <input readonly style="width: 298px; height: 44px; font-size: 16px;" type="public_repos" name="public_repos" id="github-public_repos-field" placeholder="public_repos">
                        <br />
                        <label for="public_gists"><?php _e('Gists Públicos:'); ?></label><br>
                        <input readonly style="width: 298px; height: 44px; font-size: 16px;" type="public_gists" name="public_gists" id="github-public_gists-field" placeholder="public_gists">
                        <br />
                        <label for="followers"><?php _e('Seguidores:'); ?></label><br>
                        <input readonly style="width: 298px; height: 44px; font-size: 16px;" type="followers" name="followers" id="github-followers-field" placeholder="followers">
                        <br />
                        <label for="following"><?php _e('Seguindo:'); ?></label><br>
                        <input readonly style="width: 298px; height: 44px; font-size: 16px;" type="following" name="following" id="github-following-field" placeholder="following">
                        <br />
                        <label for="created_at"><?php _e('Criado em:'); ?></label><br>
                        <input readonly style="width: 298px; height: 44px; font-size: 16px;" type="created_at" name="created_at" id="github-created_at-field" placeholder="created_at">
                        <br />
                        <label for="updated_at"><?php _e('Última atualização em:'); ?></label><br>
                        <input readonly style="width: 298px; height: 44px; font-size: 16px;" type="updated_at" name="created_at" id="github-updated_at-field" placeholder="updated_at">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<p>
    <?php
        $owner = isset($_GET['owner'])? $_GET['owner'] : null;
        _e('Resultado: ');
        if($favorite != null) {
            echo '<a href="' . esc_url(get_the_permalink()) . '">Voltar</a>';
        } else {
            echo '<a href="' . esc_url(get_the_permalink()) . '?favorite=true&userId='.get_current_user_id().'">Favoritos</a>';
            if($owner != null) {
                echo '<span class="backspace"></span>';
                echo '<a href="' . esc_url(get_the_permalink()) . '">Voltar</a>';
            }
        }
    ?>
</p>
<div id="swper-github-repo-live-search-results"></div>