$(document).ready(function () {
    var urlString = window.location.href;
    var queryParams = getUrlParams(urlString);
    var $numberViews = $('#number-views');
    var $product = $('#product');
    var $createdAt = $('#created-at');
    var $limit = $('#limit');
    var $pagination = $('.pagination li');

    $createdAt.datepicker({
        format: 'mm/dd/yyyy',
    });

    if (typeof queryParams.views !== 'undefined' && queryParams.views.length > 0) {
        $numberViews.val(queryParams.views);
    }
    if (typeof queryParams.product !== 'undefined' && queryParams.product.length > 0) {
        $product.val(queryParams.product);
    }
    if (typeof queryParams.time_create !== 'undefined' && queryParams.time_create.length > 0) {
        $createdAt.val(queryParams.time_create);
    }
    if (typeof queryParams.limit !== 'undefined' && queryParams.limit.length > 0) {
        $('#limit option[value="' + queryParams.limit + '"]').prop('selected', true);
    }

    $numberViews.change(function () {
        var value = $(this).val() + '&';

        if (urlString.includes('views')) {
            urlString = urlString.replace(/(views=).*?(&)/, '$1' + $(this).val() + '$2');
        } else {
            if (urlString.indexOf('?') > -1) {
                urlString += 'views=' + value
            } else {
                urlString += '?views=' + value;
            }
        }
        window.location.href = urlString;
    });
    $product.change(function () {
        var value = $(this).val() + '&';

        if (urlString.includes('product')) {
            urlString = urlString.replace(/(product=).*?(&)/, '$1' + $(this).val() + '$2');
        } else {
            if (urlString.indexOf('?') > -1) {
                urlString += 'product=' + value
            } else {
                urlString += '?product=' + value;
            }
        }

        window.location.href = urlString;
    });
    $createdAt.change(function () {
        var value = $(this).val() + '&';

        if (urlString.includes('time_create')) {
            urlString = urlString.replace(/(time_create=).*?(&)/, '$1' + $(this).val() + '$2');
        } else {
            if (urlString.indexOf('?') > -1) {
                urlString += 'time_create=' + value
            } else {
                urlString += '?time_create=' + value;
            }
        }

        window.location.href = urlString;
    });
    $limit.change(function () {
        var value = $(this).val() + '&';

        if (urlString.includes('limit')) {
            urlString = urlString.replace(/(limit=).*?(&)/, '$1' + $(this).val() + '$2');
        } else {
            if (urlString.indexOf('?') > -1) {
                urlString += 'limit=' + value
            } else {
                urlString += '?limit=' + value;
            }
        }

        window.location.href = urlString;
    });
    $('.article-wrap').click(function (e) {
        e.preventDefault();

        var href = $(this).attr('href');

        $.ajax({
            url: href,
            method: 'GET',
            success: function (data) {
                data = JSON.parse(data);
                if (data.success === true) {
                    window.location.href = href;
                }
            },
            error: function (data) {
                alert('Произошла ошибка.');
            }
        });
    });
    $pagination.click(function () {
        var page = $(this).data('value');

        $.ajax({
            url: window.location.href,
            data: {
                page: page,
            },
            method: 'GET',
            success: function (data) {
                var article;
                data = JSON.parse(data);

                $('#articles').empty();
                data.articles.map(function (value, key) {
                    article = '<a class="article-wrap" href="/main/view/' + value.href + '">' +
                        '<div class="title"><h2>' + value.title + '</h2></div>' +
                        '<div class="description"><p>' + value.description + '</p></div>' +
                        '<div class="views"><span><i class="fa fa-eye"></i> ' + value.views + '</span></div>' +
                        '</a>';

                    $('#articles').append(article);
                });
                $('#page span').html(data.page);
            },
            error: function (data) {
                alert('Произошла ошибка.');
            }
        });
    });
});

function getUrlParams(url) {
    if (typeof url == 'undefined') {
        url = window.location.search
    }

    url = url.split('#')[0]
    var urlParams = {}
    var queryString = url.split('?')[1]
    if (!queryString) {
        if (url.search('=') !== false) {
            queryString = url
        }
    }
    if (queryString) {
        var keyValuePairs = queryString.split('&')
        for (var i = 0; i < keyValuePairs.length; i++) {
            var keyValuePair = keyValuePairs[i].split('=')
            var paramName = keyValuePair[0]
            var paramValue = keyValuePair[1] || ''
            urlParams[paramName] = decodeURIComponent(paramValue.replace(/\+/g, ' '))
        }
    }
    return urlParams
}