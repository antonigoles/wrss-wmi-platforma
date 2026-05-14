(async function () {
    // function: is_logged_in should be defined already
    const is_user_logged_in = is_logged_in();

    let article = (new URLSearchParams(window.location.search)).get('article');
    if (article === null) {
        article = 'home'
    }
    let response, content;

    response = await fetch(`/articles/${article}.md`);
    if (response.status == 404) {
        document.querySelector('.article-target').innerHTML = "<h1 class='center'> Uh oh </h1>";
        document.querySelector('.article-target').innerHTML += "<p class='center'> Nie ma takiego artykułu : ( </p>";
        return;
    }
    content = await response.text();

    showdown.extension('custom-tags', 
        function() {
            return [
                {
                    type: 'lang',
                    regex: /\[auth\]([\s\S]*?)(?:\[else\]([\s\S]*?))?\[\/auth\]/g,
                    
                    replace: function(match, loggedInContent, guestContent) {
                        if (is_user_logged_in) {
                            return loggedInContent;
                        } else {
                            return guestContent ? guestContent : '';
                        }
                    }
                },
                {
                    type: 'lang',
                    regex: /\[text-justify\]([\s\S]*?)\[\/text-justify\]/g,
                    
                    replace: '<span class="text-justify">$1</span>'
                }
            ];
        }
    );

    const converter = new showdown.Converter({
        extensions: ['custom-tags']
    });

    const compiled_html = converter.makeHtml(content);
    document.querySelector('.article-target').innerHTML = compiled_html;
})();

