(function($) {

    questions = $( ".questions" );

    if ( ! questions.length ) {
        return;
    }

    questions.accordion({
        collapsible: true,
        header: "h3",
        heightStyle: "content"
    });

})( jQuery );
