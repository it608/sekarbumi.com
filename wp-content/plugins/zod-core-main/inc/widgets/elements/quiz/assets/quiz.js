(function($){

    const progressBarHandle = (parent) => {
        let progressBar = $('.zod-quiz__progressbar', parent);
        if( progressBar.is(':hidden') ){
            progressBar.css('display', 'block');
        }
    }

    const answerHandle = (parent) => {
        let paginationWrapper = parent.find('.forminator-pagination:visible');
        let answer = paginationWrapper.find('.forminator-answer--design');

        $(answer).each( (i, el) => {
            $(el).on('click', function(e){
                $('.forminator-button-next').trigger('click');
            });
        });
    }

    $(document).ready( () => {
        let count = 0;
        $('body').on('click', function(e) {
            count++;
            e.preventDefault();
            progressBarHandle($(this));
            answerHandle($(this));
            alert( 'total count' + count );
        });
    });
    
})(jQuery)