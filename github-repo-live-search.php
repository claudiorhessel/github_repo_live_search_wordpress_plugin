<?php

namespace SWPER\GitHubRepoLiveSearch;

defined('ABSPATH') or die();

/*
Plugin Name: SWPER - GitHub Repo Live Search
Plugin URI: https://github.com/claudiorhessel/github-repo-live-search
Description: GitHub Repo Live Search, search for Github repositories and users
Version: 0.1
Author: Cláudio Hessel
Author URI: https://github.com/claudiorhessel
License: GPLv2 or later
Text Domain: swper-github-live-search
Domain Path: /languages
*/

// Require do arquivode autoload
require_once 'autoload.php';

// Definição das constantes
define('SWPER_GIT_REPO_SEARCH_DEBUG', 0);
define('SWPER_GIT_REPO_SEARCH_LOGGING', 0);
define('SWPER_GIT_REPO_SEARCH_PLUGIN_DIR', plugin_dir_path(__FILE__));

use \SWPER\GitHubRepoLiveSearch\GitHubRepoLiveSearch as GitHubRepoLiveSearch;
use \SWPER\GitHubRepoLiveSearch\Log\Klogger as Klogger;

$swper_github_repo_live_search_log_file_path = plugin_dir_path(__FILE__) . '/log';

$swper_github_repo_live_search_log = new Klogger($swper_github_repo_live_search_log_file_path, Klogger::INFO);

// Classe principal
GitHubRepoLiveSearch::getInstance();

register_activation_hook(__FILE__, '\SWPER\GitHubRepoLiveSearch\GitHubRepoLiveSearch::activatePlugin');
register_activation_hook(__FILE__, '\SWPER\GitHubRepoLiveSearch\GitHubRepoLiveSearch::createDb');

register_deactivation_hook(__FILE__, '\SWPER\GitHubRepoLiveSearch\GitHubRepoLiveSearch::removeDb');

register_uninstall_hook(__FILE__, '\SWPER\GitHubRepoLiveSearch\GitHubRepoLiveSearch::uninstallPlugin');
