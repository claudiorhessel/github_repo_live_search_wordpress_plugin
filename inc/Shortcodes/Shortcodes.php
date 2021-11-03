<?php

namespace SWPER\GitHubRepoLiveSearch\Shortcodes;

defined('\ABSPATH') or die();

use SWPER\GitHubRepoLiveSearch\API\GetData as GetData;


/**
 * Classe com as funcionalidades do Shortcode bem simples, basta incluir o
 * shortcode na página ou post que o formulário de busca será exibido
 * Author: Claudio Hessel 2021
 */
class ShortCodes extends GetData
{
    private const DEBUG = 0;
    private const LOGGING = 1;

    public function __construct()
    {
    }
    public function __destruct()
    {
    }

    public function generateSearchForm(string $content = null): string
    {
        $this->logger(self::DEBUG, self::LOGGING);

        global $post;

        ob_start();

        require SWPER_GIT_REPO_SEARCH_PLUGIN_DIR . 'pages/gitHubRepoLiveSearchFormTemplate.php';
      
        $content = ob_get_clean();

        return $content;
    }
}