<?php

namespace SWPER\GitHubRepoLiveSearch\API;

class GetData
{
    use \SWPER\GitHubRepoLiveSearch\Log\Logger;

    private const DEBUG = 0;
    private const LOGGING = 1;
    private const TRANSIENT_NAME = 'swper_github_repo_live_search_results';
    
    public function apiCall(
        string $keyword,
        string $username,
        array $args = array(
            'sort'      => 'stars',
            'order'     => 'desc',
            'per_page'  => 30
        )
    ) {
        $keyword = wp_strip_all_tags(trim($keyword));
        $keyword = esc_html($keyword);
        $username = wp_strip_all_tags(trim($username));
        $username = esc_html($username);
        $queryString = null;
        $baseurlUser = null;

        if($username == null || $username == '') {
            $baseurl = 'https://api.github.com/search/repositories';
            $queryString = '?q=' . $keyword . '+language=' . $keyword . '&' . $keyword . '+description';
        } else {
            $baseurl = 'https://api.github.com/search/repositories';
            $queryString = '?q=' . $keyword . '+user:' . $username . '&' . $keyword . '+description';
            $baseurlUser = 'https://api.github.com/users/' . $username;
        }

        if ($args['sort']) {
            $queryString .= '&sort=' . esc_html($args['sort']);
        }
        if ($args['order']) {
            $queryString .= '&order=' . esc_html($args['order']);
        }
        if ($args['per_page']) {
            $queryString .= '&per_page=' . esc_html($args['per_page']);
        }

        $requestUrl = $baseurl . $queryString;

        error_reporting(E_ALL & ~E_WARNING);

        try {
            $header = array( 'headers' => array(
                "Authorization" => "token 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"
            ));
            $response = wp_safe_remote_get($requestUrl, $header);

            $statusCode = wp_remote_retrieve_response_code($response);
            $response_message = wp_remote_retrieve_response_message($response);

            if (is_wp_error($response) || $statusCode !== 200) {
                throw new APIQueryException($response_message);
            }

            $repos = wp_remote_retrieve_body($response);

            if($baseurlUser != null) {
                $responseUser = wp_safe_remote_get($baseurlUser);
    
                $statusCodeUser = wp_remote_retrieve_response_code($responseUser);
                $responseUser_message = wp_remote_retrieve_response_message($responseUser);
    
                if (is_wp_error($response) || $statusCodeUser !== 200) {
                    throw new APIQueryException($responseUser_message);
                }
    
                $reposUser = wp_remote_retrieve_body($responseUser);
            }
        } catch (APIQueryException $ex) {
            echo '<div class="notice notice-error"><p>' . $ex->getMessage() . '</p></div>';
            $this->exceptionLogger(\SWPER_GIT_REPO_SEARCH_LOGGING, $ex);
        } catch (\Exception $ex) {
            echo '<div class="notice notice-error"><p>' . $ex->getMessage() . '</p></div>';
            $this->exceptionLogger(\SWPER_GIT_REPO_SEARCH_LOGGING, $ex);
        }
        // report all errors
        error_reporting(E_ALL);

        $reposArr = json_decode($repos);
        $reposArr->owner = json_decode($reposUser);
        $reposArr->currentUser = get_current_user_id();

        return $reposArr;
    }

    public function apiRepoByIdCall(
        int $repoId,
        string $repoOwner,
        string $repoName
    ) {
        // sanitize
        $repoId = wp_strip_all_tags(trim($repoId));
        $repoId = esc_html($repoId);
        $repoOwner = wp_strip_all_tags(trim($repoOwner));
        $repoOwner = esc_html($repoOwner);
        $repoName = wp_strip_all_tags(trim($repoName));
        $repoName = esc_html($repoName);
        $baseurl = null;

        $baseurl = 'https://api.github.com/repos/'.$repoOwner.'/'.$repoName;

        $requestUrl = $baseurl;

        error_reporting(E_ALL & ~E_WARNING);

        try {
            $header = array( 'headers' => array(
                "Authorization" => "token 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"
            ));
            $response = wp_safe_remote_get($requestUrl, $header);

            $statusCode = wp_remote_retrieve_response_code($response);
            $response_message = wp_remote_retrieve_response_message($response);

            if (is_wp_error($response) || $statusCode !== 200) {
                throw new APIQueryException($response_message);
            }

            $repos = wp_remote_retrieve_body($response);
        } catch (APIQueryException $ex) {
            echo '<div class="notice notice-error"><p>' . $ex->getMessage() . '</p></div>';
            $this->exceptionLogger(\SWPER_GIT_REPO_SEARCH_LOGGING, $ex);
        } catch (\Exception $ex) {
            echo '<div class="notice notice-error"><p>' . $ex->getMessage() . '</p></div>';
            $this->exceptionLogger(\SWPER_GIT_REPO_SEARCH_LOGGING, $ex);
        }

        error_reporting(E_ALL);

        $reposArr = json_decode($repos);
        $reposArr->currentUser = get_current_user_id();

        return $reposArr;
    }
}
