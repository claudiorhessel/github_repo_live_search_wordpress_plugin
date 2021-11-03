/**
 * É neste arquivo onde a maior parte das coisas são feitas, a exibição
 * do formulário, ação de incluir e excluir favorito, exibição dos dados
 * do owner do repositório selecionado, requisição AJAX, exibição em tela 
 * dos dados retornados pela API ETC
 * Author: Claudio Hessel 2021
 */

jQuery(document).ready(function ($) {
  var owner = '';
  var userId = '';
  var favorite = false;
  var url = window.location.search.substring(1);
  var urlVariables = url.split('&');

  for (var i = 0; i < urlVariables.length; i++)
  {
    var parameterName = urlVariables[i].split('=');
    if (parameterName[0] == 'owner')
    {
      owner = parameterName[1];
      jQuery("#accordionOwnerData").css("display", "block");
    }

    if (parameterName[0] == 'favorite')
    {
      favorite = true;
    }
    
    if (parameterName[0] == 'userId')
    {
      userId = parameterName[1];
    }
  }

  $("#github-owner-field").val(owner);

  if(favorite == true && userId != '') {
    ajaxGetGitFavoriteRepos(
      userId,
      "#swper-github-repo-live-search-results",
      "table"
    );
  }
  
  if ($("#github-search-field").length > 0 || owner != '') {
    ajaxGetGitRepos(
      "#github-search-field",
      "#github-owner-field",
      "#swper-github-repo-live-search-results",
      "table"
    );
  }

  function ajaxGetGitFavoriteRepos(userId, containerId, outputType) {
    userId = sanitize(userId);
    getFavoriteData(userId, containerId, outputType);
  }

  function ajaxGetGitRepos(inputId, ownerId, containerId, outputType) {
    // get keyword
    let keyword = '';

    // get owner
    let owner = $(ownerId).val();;

    // sanitize para evitar ataque XSS
    owner = sanitize(owner);

    $(inputId).on("keyup", function (count) {
      if ($(containerId).length > 0) {
        if ($(inputId).val() != false) {
          keyword = $(inputId).val();
    
          owner = $(ownerId).val();;
    
          keyword = sanitize(keyword);
          owner = sanitize(owner);

          getData(keyword, owner, containerId, outputType);
        } else {
          if ($(ownerId).val() != false) {            
            getData('', owner, containerId, outputType);
          }
        }
      }
    });

    if ($(ownerId).val() != false) {      
      getData('', owner, containerId, outputType);
    }
  }

  function getData(keyword, owner, containerId, outputType) {
    var data = {
      action: "swper_github_repos_ajax_action",
      security: GitHubRepoLiveSearchAjax.security,
      keyword: keyword,
      username: owner,
      args: {
        sort: "stars",
        order: "desc",
        per_page: 10,
      },
    };

    console.log(GitHubRepoLiveSearchAjax.ajax_url);

    $.ajax({
      type: "GET",
      url: GitHubRepoLiveSearchAjax.ajax_url,
      data: data,
      dataType: "json",
    })
      .done(function (response) {
        console.table(response);
        var currentUser = response.data.currentUser;
        var repos = response.data.items;
        var ownerData = response.data.owner? response.data.owner : '';

        if (repos.length === 0) {
          $(containerId).html(
            '<p>Nenhum resultado encontrado para a consulta ("' +
              keyword +
              '").</p>'
          );
          return;
        } else {
          var myHtml = generateHtml(repos, outputType, currentUser);
          if(ownerData != '') {
            replaceOwnerData(ownerData);
          }
          $(containerId).html(myHtml);
        }
      })
      .fail(function () {
        console.log("GitHub Repo Live Search AJAX error response.");
      })
      .always(function () {
        console.log("GitHub Repo Live Search AJAX finished.");
      });
  }

  function getFavoriteData(userId, containerId, outputType) {
    var favoriteData = getAllFavorite(userId);
    console.table(favoriteData);
    var repos = [];
    var cont = 0;
    favoriteData.forEach((element) => {      
      var data = {
        action: "swper_github_reposbyid_ajax_action",
        security: GitHubRepoLiveSearchAjax.security,
        repoId: element.repo_id,
        repoOwner: element.repo_owner,
        repoName: element.repo_name,
      };

      $.ajax({
        type: "GET",
        url: GitHubRepoLiveSearchAjax.ajax_url,
        data: data,
        dataType: "json",
        async: false,
      })
        .done(function (response) {
          repos[cont] = response.data;
          cont++;
        })
        .fail(function () {
          console.log("GitHub Repo Live Search AJAX error response.");
        })
        .always(function () {
          console.log("GitHub Repo Live Search AJAX finished.");
        });
    });

    if (repos.length === 0) {
      $(containerId).html(
        '<p>Nenhum favorito encontrado.</p>'
      );
      return;
    } else {
      var myHtml = generateHtml(repos, outputType, userId);
      $(containerId).html(myHtml);
    }
  }

  function sanitize(input) {
    var output = input
      .replace(/<script[^>]*?>.*?<\/script>/gi, "")
      .replace(/<[\/\!]*?[^<>]*?>/gi, "")
      .replace(/<style[^>]*?>.*?<\/style>/gi, "")
      .replace(/<![\s\S]*?--[ \t\n\r]*>/gi, "");
    return output;
  }

  function replaceOwnerData(ownerData) {
    console.log(ownerData);
    jQuery("#github-name-field").val(ownerData.name);
    jQuery("#github-company-field").val(ownerData.company);
    jQuery("#github-blog-field").val(ownerData.blog);
    jQuery("#github-location-field").val(ownerData.location);
    jQuery("#github-email-field").val(ownerData.email);
    jQuery("#github-hireable-field").val(ownerData.hireable);
    jQuery("#github-twitter_username-field").val(ownerData.twitter_username);
    jQuery("#github-public_repos-field").val(ownerData.public_repos);
    jQuery("#github-public_gists-field").val(ownerData.public_gists);
    jQuery("#github-followers-field").val(ownerData.followers);
    jQuery("#github-following-field").val(ownerData.following);
    jQuery("#github-created_at-field").val(ownerData.created_at);
    jQuery("#github-updated_at-field").val(ownerData.updated_at);
  }

  function generateHtml(repos, type, currentUser) {
    if (type === "table") {
      var html =
        '<table border="1" class="table table-striped table-hover">' +
        "<thead>" +
        "<tr>" +
        "<th scope='col'>Proprietário</th>" +
        "<th scope='col'>Repositório</th>" + 
        "<th scope='col'>Descrição</th>" +
        "<th scope='col'>Ano</th>" +
        "<th scope='col'>Estrelas</th>" +
        "<th scope='col'>Favorito</th>" +
        "</tr>" +
        "</thead>" +
        "<tbody>";

      repos.forEach((element) => {
        var repoId = element.id;
        var owner = element.owner ? element.owner.login : element.login;
        var description = element.description ? element.description.slice(0, 255) : element.description;
        var points = element.description ? (element.description.length < 255 ? "" : "...") : "";
        description += points;

        var year = new Date(element.created_at).getFullYear();
        var url = window.location.href;
        html += "<tr>";
        if(jQuery("#accordionOwnerData").css("display") != 'block') {
          html +=
            '<th scope="row">' +
              '<a href="' + url + '/?owner=' +
              owner +
              '">' +
              owner +
              "</a>" +
            "</th>";
        } else {
          html += '<th scope="row">' + owner + '</th>';
        }
        html +=
          '<td><a href="' +
          element.html_url +
          '">' +
          element.name +
          "</a></td>";
        html += "<td>" + description + "</td>";
        html += "<td>" + year + "</td>";
        html += "<td>" + element.stargazers_count + "</td>";
        
        var onFavorite = getFavorite(repoId, currentUser);
        var addRemoveFavorite = "Adicionar";
        if(onFavorite > 0) {
          addRemoveFavorite = "Remover";
        }

        if(currentUser > 0) {
          html += "<td class='favorite' id='favorite_" + repoId + "'><a href=\"javascript:favorite('" + repoId + "','" + owner + "','" + element.name + "','" + currentUser + "');\"'>" + addRemoveFavorite + "</a></td>";
        } else {
          html += "<td>Usuário não logado</td>";
        }
        html += "</tr>";
      });

      html += "</tbody>";
      html += "</table>";
    } else if (type === "list") {
      var html = '<ul style="margin-top: 30px;">';

      repos.forEach((element) => {
        var description = element.description.slice(0, 255);
        var points = element.description.length < 255 ? "" : "...";
        description += points;

        var year = new Date(element.created_at).getFullYear();

        html += "<li>";
        html +=
          '<a href="' +
          element.html_url +
          '">' +
          element.name +
          " (" +
          element.stargazers_count +
          ")</a>";
        html += "<p>" + description + "</p>";
        html += "</li>";
      });

      html += "</ul>";
    }

    return html;
  }
});

function getAllFavorite(currentUser) {
  var data = {
    action: "swper_github_get_all_favorite_ajax_action",
    security: GitHubRepoLiveSearchAjax.security,
    userId: currentUser,
  };
  
  var result = null;

  jQuery.ajax({
    type: "GET",
    url: GitHubRepoLiveSearchAjax.ajax_url,
    data: data,
    dataType: "json",
    async: false,
    success: function(data) {
      result = data.data;
    },
  });

  return result;
}

function getFavorite(repoId, currentUser) {
  var data = {
    action: "swper_github_get_favorite_ajax_action",
    security: GitHubRepoLiveSearchAjax.security,
    id: repoId,
    userId: currentUser,
  };

  var result = null;

  jQuery.ajax({
    type: "GET",
    url: GitHubRepoLiveSearchAjax.ajax_url,
    data: data,
    dataType: "json",
    async: false,
    success: function(data) {
      result = data.data.length;
    },
  });

  return result;
}

function favorite(repoId, repoOwner, repoName, currentUser) {
  var onFavorite = getFavorite(repoId, currentUser);

  if(onFavorite > 0) {
    removeFromFavorite(repoId);
    jQuery("#favorite_" + repoId).html("<a href=\"javascript:favorite('" + repoId + "','" + repoOwner + "','" + repoName + "','" + currentUser + "');\"'>Adicionar</a>");
  } else {
    addToFavorites(repoId, repoOwner, repoName);
    jQuery("#favorite_" + repoId).html("<a href=\"javascript:favorite('" + repoId + "','" + repoOwner + "','" + repoName + "','" + currentUser + "');\"'>Remover</a>");
  }
}

function addToFavorites(repoId, repoOwner, repoName) {
  var data = {
    action: "swper_github_add_favorite_ajax_action",
    security: GitHubRepoLiveSearchAjax.security,
    repoId: repoId,
    repoOwner: repoOwner,
    repoName: repoName,
  };
  
  var result = null;

  jQuery.ajax({
    type: "GET",
    url: GitHubRepoLiveSearchAjax.ajax_url,
    data: data,
    dataType: "json",
    success: function(data) {
        result = 'OK';
    },
  });

  return result;
}

function removeFromFavorite(repoId) {
  var data = {
    action: "swper_github_remove_favorite_ajax_action",
    security: GitHubRepoLiveSearchAjax.security,
    id: repoId,
  };

  var result = null;

  jQuery.ajax({
    type: "GET",
    url: GitHubRepoLiveSearchAjax.ajax_url,
    data: data,
    dataType: "json",
    async: false,
    success: function(data) {
        result = 'OK';
    },
  });

  return result;
}
