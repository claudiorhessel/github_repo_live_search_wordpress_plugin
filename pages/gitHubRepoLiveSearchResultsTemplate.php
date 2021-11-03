<table border="1" style="border-collapse: collapse; border-spacing: 0; margin-top: 30px;">
    <thead>
        <tr>
            <th><?php _e('Repositório');?></th>
            <th><?php _e('Descrição');?></th>
            <th><?php _e('Ano');?></th>
            <th><?php _e('Estrelas');?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($repos as $repo) {
            $owner = isset($repo->owner->login) ? $repo->owner->login : $repo->login;
            $description = mb_substr($repo->description, 0, 255);
            $dots = mb_strlen($repo->description) < 255 ? '' : '...';
            $description .= $dots;
            $year = date("Y", strtotime($repo->created_at));

            $urlToRepo = $repo->html_url;
            $repoName = $repo->name;
            $repoStars =  $repo->stargazers_count;

            echo <<<GETGITREPOS
 <tr>
    <td>$owner</td>
    <td><a href="/?owner=$urlToRepo" target="_blank">$repoName</a></td>
    <td>$description</td>
    <td>$year</td>
    <td>$repoStars</td>
 </tr>
GETGITREPOS;
        }
        ?>
    </tbody>
</table>