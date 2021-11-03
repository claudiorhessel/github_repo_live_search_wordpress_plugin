<?php

namespace SWPER\GitHubRepoLiveSearch\Log;

/**
 * trait auxiliar apra o log, também a peguei pronta, corrigi erros e não tive
 * tempo de finalizá-la como eu queria
 * Author: Claudio Hessel 2021
 */
trait Logger
{

    public function logger(int $debug = 0, int $logging = 1): void
    {
        if ($debug) {
            $info_text = "Entering - " . __FILE__ . ":" . __FUNCTION__ . ":" . __LINE__;
            echo '<div class="notice notice-info is-dismissible">' . $info_text . '</p></div>';
        }
        if ($logging) {
            global $swper_github_repo_live_search_log;
            $swper_github_repo_live_search_log->logInfo("Entering - " . __FILE__ . ":" . __FUNCTION__ . ":" . __LINE__);
        }
    }

    public function exceptionLogger(int $logging = 1, object $ex = null): void
    {
        if ($logging) {
            global $swper_github_repo_live_search_log;
            $swper_github_repo_live_search_log->logInfo(
                $ex->getMessage() . " - " . __FILE__ . ":" . __FUNCTION__ . ":" . __LINE__
            );
        }
    }
}
