<?php

namespace SWPER\GitHubRepoLiveSearch\Database;

/**
 * Classe com as iterações com o banco de dados
 * Author: Claudio Hessel 2021
 */
class Database
{
    use \SWPER\GitHubRepoLiveSearch\Log\Logger;

    private const DEBUG = 0;
    private const LOGGING = 1;
    private const TRANSIENT_NAME = 'swper_github_repo_live_search_results';

    public function addFavorite(
        int $repoId,
        string $repoOwner,
        string $repoName,
        int $userId
    ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'swper_github_live_search_favorites';
        $return = $wpdb->insert($table_name, array(
           "repo_id" => $repoId,
           "repo_owner" => $repoOwner,
           "repo_name" => $repoName,
           "user_id" => $userId
        ));

        return json_encode($return);
    }

    public function getFavorite(
        int $repoId,
        int $userId
    ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'swper_github_live_search_favorites';
    
        $sql = "SELECT * FROM $table_name WHERE repo_id = " . $repoId . " AND user_id = " . $userId;

        $favoriteRepoData = $wpdb->get_results($sql);

        return $favoriteRepoData;
    }

    public function getAllFavorites($userId) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'swper_github_live_search_favorites';
    
        $sql = "SELECT * FROM $table_name WHERE user_id = " . $userId . ";";

        $favoriteRepoData = $wpdb->get_results($sql);

        return $favoriteRepoData;
    }

    public function removeFavorite(
        int $repoId
    ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'swper_github_live_search_favorites';
    
        $sql = "DELETE FROM $table_name WHERE repo_id = " . $repoId;

        $wpdb->query($sql);
    }
}