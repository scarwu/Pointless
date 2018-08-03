'use strict';

require([
    '//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js',
    '//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.0/highlight.min.js'
], function() {
    $('pre').each(function() {
        var td = $('<td>').append($(this).clone()),
            tr = $('<tr>').append(td),
            table = $('<table>').append(tr),
            code = $('<div>').attr('class', 'code').append(table);

        var count = $(this).find('code').text().split('\n').length + 1,
            line = $('<div>').attr('class', 'line').html(Array(count).join('<span></span>'));

        var viewer = $('<div>').attr('class', 'viewer').append(line).append(code);

        $(this).after(viewer);
        $(this).remove();

        hljs.highlightBlock(viewer[0]);
    });

    $('#container .content a').attr('target', '_blank');

    $(window).keydown(function(e) {
        switch(e.keyCode) {
            case 37:
            case 72:
                if($('#paging .new a')[0] != undefined)
                    $('#paging .new a')[0].click();
                break;
            case 39:
            case 76:
                if($('#paging .old a')[0] != undefined)
                    $('#paging .old a')[0].click();
                break;
            case 74:
                scrollBy(0, 40);
                break;
            case 75:
                scrollBy(0, -40);
                break;
        }
    });
});
