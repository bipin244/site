function setHeight(divs) {
    var totalHeight = 0;
    divs.each(function () {
        var height = 0;
        $(this).children().each(function () {
            height = height + $(this).outerHeight(true);
        });
        if (height > totalHeight) {
            totalHeight = height;
        }
    });
    divs.each(function () {
        if ($(this).tagName === 'div') {
            $(this).parent().height(totalHeight);
        }
        $(this).height(totalHeight);
    })
}