<?php

namespace SWPER\GitHubRepoLiveSearch;

use \SWPER\GitHubRepoLiveSearch\Shortcodes\Shortcodes as ShortCodes;

defined('ABSPATH') or die();

/**
 * Classe desenvolvida para acessar a API de busca de Repositórios do GitHub
 * A busca é feita no nome do repositório, descrição e no arquivo README
 * Author: Claudio Hessel 2021
 */
final class GitHubRepoLiveSearch
{
    private const TEXT_DOMAIN = 'swper-github-repo-live-search';
    private const OPTION_NAME = 'swper_github_repo_live_search_version';
    private const OPTION_VERSION = '0.1';
    private const TRANSIENT_NAME = 'swper_github_repo_live_search_results';

    private static $instance;
    private static $shortcodes;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self(
                new ShortCodes()
            );
        }
        return self::$instance;
    }

    private function __construct(Shortcodes $shortcodes)
    {
        self::$shortcodes = $shortcodes;
        add_action('plugins_loaded', array($this, 'loadTextdomain'));

        add_shortcode('github_repo_search_form', array(self::$shortcodes, 'generateSearchForm'));

        add_action('admin_menu', array($this, 'addAdminMenu'));

        add_action('admin_enqueue_scripts', array($this, 'adminLoadScripts'));

        add_action('wp_enqueue_scripts', array($this, 'addCSS'));

        add_action('wp_enqueue_scripts', function () {
            wp_enqueue_script('github-repo-live-search-js', plugin_dir_url(dirname(__FILE__)) . 'js/gitHubRepoLiveSearchWidget.js', array('jquery'));
            wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js');

            wp_localize_script('github-repo-live-search-js', 'GitHubRepoLiveSearchAjax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'security' => wp_create_nonce('githubrepolivesearchajax-t6zhsyddea')
            ));
        });

        add_action('wp_ajax_swper_github_repos_ajax_action', array($this, 'githubAjaxHandler'));
        add_action('wp_ajax_swper_github_reposbyid_ajax_action', array($this, 'githubRepoByIdAjaxHandler'));
        add_action('wp_ajax_nopriv_swper_github_repos_ajax_action', array($this, 'githubAjaxHandler'));
        add_action('wp_ajax_swper_github_add_favorite_ajax_action', array($this, 'addFavoriteAjaxHandler'));
        add_action('wp_ajax_swper_github_remove_favorite_ajax_action', array($this, 'removeFavoriteAjaxHandler'));
        add_action('wp_ajax_swper_github_get_favorite_ajax_action', array($this, 'getFavoriteAjaxHandler'));
        add_action('wp_ajax_swper_github_get_all_favorite_ajax_action', array($this, 'getAllFavoritesAjaxHandler'));
    }

    public function __destruct()
    {
    }

    public function __get($property)
    {
    }

    public function __set($property, $value)
    {
    }

    public static function loadTextdomain(): void
    {
        $domain = self::TEXT_DOMAIN;
        $locale = apply_filters('plugin_locale', get_locale(), $domain);

        load_textdomain($domain, trailingslashit(\WP_LANG_DIR) . $domain . '/' . $domain . '-' . $locale . '.mo');
        load_plugin_textdomain($domain, false, basename(dirname(__FILE__, 2)) . '/languages/');
    }

    public function addAdminMenu(): void
    {
        global $swper_github_repo_live_search_settings_page;
        $swper_github_repo_live_search_settings_page = add_options_page(
            __('GitHub Repo Search Admin'),
            __('GitHub API Search Docs'),
            'manage_options',
            'git_repo_search_docs',
            array($this, 'createOptionsPage')
        );
    }

    public function adminLoadScripts($hook)
    {
        global $swper_github_repo_live_search_settings_page;

        if ($hook != $swper_github_repo_live_search_settings_page) {
            return;
        }

        wp_enqueue_style(
            'swper_github_repo_live_search_css',
            plugins_url('css/github-repo-live-search.css', dirname(__FILE__, 1))
        );
        
        wp_enqueue_style(
            'swper_bootstrap',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css'
        );
        // wp_enqueue_script('custom-js', plugins_url('js/custom.js', dirname(__FILE__, 2)));
    }

    public function createOptionsPage()
    {
        require SWPER_GIT_REPO_SEARCH_PLUGIN_DIR . 'pages/settingsPage.php';
    }

    /**
     * Add some styling to the plugin's admin and shortcode UI
     * @return void
     */
    public function addCSS(): void
    {
        wp_enqueue_style(
            'swper_github_repo_live_search_css',
            plugins_url() . '/github-repo-live-search-master/css/github-repo-live-search.css'
        );
        wp_enqueue_style(
            'swper_bootstrap',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css'
        );
    }

    /**
     * Add add an option with the version when activated
     */
    public static function activatePlugin(): void
    {
        $option = self::OPTION_NAME;
        // check if option exists, then delete
        if (!get_option($option)) {
            add_option($option, self::OPTION_VERSION);
        }
    }

    public static function createDb(): void
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'swper_github_live_search_favorites';
    
	    if( $wpdb->get_var( "show tables like '$table_name'" ) != $table_name ) {
            $sql = "CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
                repo_id bigint NOT NULL,
                repo_owner varchar(255) NOT NULL,
                repo_name varchar(255) NOT NULL,
                user_id bigint NOT NULL,
                UNIQUE KEY id (id)
            ) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
        }
    }

    // This code will only run when plugin is deleted
    // it will drop the custom database table, delete wp_option record (if exists)
    public static function uninstallPlugin()
    {
        // check if option exists, then delete
        if (get_option(self::OPTION_NAME)) {
            delete_option(self::OPTION_NAME);
        }

        // delete settings option created via Settings API
        if (get_option('swper_github_repo_live_search_options')) {
            delete_option('swper_github_repo_live_search_options');
        }

        // delete transient if exists
        if (get_transient(self::TRANSIENT_NAME)) {
            delete_transient(self::TRANSIENT_NAME);
        }
    }

    public static function removeDb(): void
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'swper_github_live_search_favorites';
    
        $sql = "DROP TABLE IF EXISTS $table_name;";

        $wpdb->query($sql);
    }

    /**
     * Register the new widget.
     *
     * @see 'widgets_init'
     */
    public function registerWidgets()
    {
    }

    public function githubAjaxHandler()
    {
        if (check_ajax_referer('githubrepolivesearchajax-t6zhsyddea', 'security')) {
            $keyword = $_REQUEST['keyword'];
            $username = $_REQUEST['username'];
            $args = $_REQUEST['args'];
            $getData = new \SWPER\GitHubRepoLiveSearch\API\GetData();

            $responseData = $getData->apiCall($keyword, $username, $args);

            wp_send_json_success($responseData, 200);
        } else {
            wp_send_json_error();
        }
        wp_die();
    }

    public function githubRepoByIdAjaxHandler()
    {
        if (check_ajax_referer('githubrepolivesearchajax-t6zhsyddea', 'security')) {
            $repoId = $_REQUEST['repoId'];
            $repoOwner = $_REQUEST['repoOwner'];
            $repoName = $_REQUEST['repoName'];
            $getData = new \SWPER\GitHubRepoLiveSearch\API\GetData();

            $responseData = $getData->apiRepoByIdCall($repoId, $repoOwner, $repoName);

            wp_send_json_success($responseData, 200);
        } else {
            wp_send_json_error();
        }
        wp_die();
    }

    public function addFavoriteAjaxHandler()
    {
        if (check_ajax_referer('githubrepolivesearchajax-t6zhsyddea', 'security')) {
            $repoId = $_REQUEST['repoId'];
            $repoOwner = $_REQUEST['repoOwner'];
            $repoName = $_REQUEST['repoName'];

            $userId = get_current_user_id();
            $database = new \SWPER\GitHubRepoLiveSearch\Database\Database();

            $responseData = $database->addFavorite($repoId, $repoOwner, $repoName, $userId);

            wp_send_json_success($responseData, 200);
        } else {
            wp_send_json_error();
        }
        wp_die();
    }

    public function removeFavoriteAjaxHandler()
    {
        if (check_ajax_referer('githubrepolivesearchajax-t6zhsyddea', 'security')) {
            $repoId = $_REQUEST['id'];
            $database = new \SWPER\GitHubRepoLiveSearch\Database\Database();

            $responseData = $database->removeFavorite($repoId);

            wp_send_json_success($responseData, 200);
        } else {
            wp_send_json_error();
        }
        wp_die();
    }

    public function getFavoriteAjaxHandler()
    {
        if (check_ajax_referer('githubrepolivesearchajax-t6zhsyddea', 'security')) {
            $repoId = $_REQUEST['id'];
            $userId = $_REQUEST['userId'];
            $database = new \SWPER\GitHubRepoLiveSearch\Database\Database();

            $responseData = $database->getFavorite($repoId, $userId);

            wp_send_json_success($responseData, 200);
        } else {
            wp_send_json_error('teste');
        }
        wp_die();
    }

    public function getAllFavoritesAjaxHandler()
    {
        if (check_ajax_referer('githubrepolivesearchajax-t6zhsyddea', 'security')) {
            $userId = $_REQUEST['userId'];
            $database = new \SWPER\GitHubRepoLiveSearch\Database\Database();

            $responseData = $database->getAllFavorites($userId);

            wp_send_json_success($responseData, 200);
        } else {
            wp_send_json_error();
        }
        wp_die();
    }
}
