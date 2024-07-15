
function display_post_data( data ){
    let booksList = jQuery('#books-list');
    jQuery.each(data, function(index, book) {
        let url = myInfoVars.site_url+"book/?id="+book.id;
        let bookElement = `
                <div class="book">
                    <h2><a href="${url}">${book.title}</a></h2>
                    <img src="${book.image}" alt="${book.title}">
                    <p>${book.content}</p>
                </div>
            `;
        booksList.append(bookElement);
    });
}
async function set_books_limit_per_page_in_options(setUrl, type, search_data) {
    try {
        const response = await jQuery.ajax({
            type: type,
            url: setUrl,
            contentType: 'application/json',
            headers: {
                'X-WP-Nonce': search_data.nonce
            },
            data: JSON.stringify(search_data),
        });
        // display_post_data( response );
        return response;

    } catch (error) {
        console.error('AJAX request failed:', error);
        // Optionally handle the error here
    }
}

jQuery(document).ready(  function( ) {
    let search_data = {
        'nonce': myInfoVars.rest_nonce,
    }
    window.onload = async function() {
        let data = await set_books_limit_per_page_in_options('http://localhost/plugin_check/wp-json/get_books/v1/books', 'get', search_data);
        if (data) {
            display_post_data(data);
        }
    };

});