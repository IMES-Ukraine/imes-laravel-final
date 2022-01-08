$(document).ready(function () {
    window.addEventListener("load", function(event) {
        $(document).on('click', '.articlesTabsNav .articles_tabs-item', function () {
            $(".articlesTabsNav .articles_tabs-item").removeClass("active").eq($(this).index()).addClass("active");
            $(".articlesTabsContent > div").hide().eq($(this).index()).show();
        });
        $(document).on('click', '.articleStatisticsBtn', function () {
            let btn = $(this);
            let block = btn.parents(".articles_list2__block");
            let overlay = block.find(".articles_list2__block-overlay");
            let content = block.find(".articles_list2__block-content--statistics");
            if (btn.hasClass("active")) {
                btn.removeClass("active");
                overlay.fadeOut(300);
                content.removeClass("show");
            } else {
                btn.addClass("active");
                overlay.fadeIn(300);
                content.addClass("show");
            }
        });
        $(document).on('click', '.articleDeleteBtn', function () {
            let btn = $(this);
            let block = btn.parents(".articles_list2__block");
            let overlay = block.find(".articles_list2__block-overlay");
            let content = block.find(".articles_list2__block-content--delete");
            if (btn.hasClass("active")) {
                btn.removeClass("active");
                overlay.fadeOut(300);
                content.removeClass("show");
            } else {
                btn.addClass("active");
                overlay.fadeIn(300);
                content.addClass("show");
            }
        });
        $(document).on('click', '.articleCloseBtn', function () {
            let btn = $(this);
            let block = btn.parents(".articles_list2__block");
            let buttons = block.find(".articles_list2__block-button");
            let contents = block.find(".articles_list2__block-content");
            let overlay = block.find(".articles_list2__block-overlay");
            overlay.fadeOut(300);
            buttons.removeClass("active");
            contents.removeClass("show");
        });
        $(document).on('click', '.articleOverlay', function () {
            let overlay = $(this);
            let block = overlay.parents(".articles_list2__block");
            let buttons = block.find(".articles_list2__block-button");
            let contents = block.find(".articles_list2__block-content");
            overlay.fadeOut(300);
            buttons.removeClass("active");
            contents.removeClass("show");
        });

        buttonAddPlaceholder($(".buttonAddFile"));

        $(document).on('change', '.buttonAddFile input', function(e) {
            let field = $(this);
            let block = field.parents(".buttonAddFile");
            let text = block.find("p span");
            let fileName = e.target.files[0].name;
            block.addClass("has_file");
            text.text(fileName);
        });

        $(document).on('click', '.deleteFile', function () {
            let btn = $(this);
            let block = btn.parents(".buttonAddFile");
            let field = block.find("input");
            field.val("");
            block.removeClass("has_file");
            buttonAddPlaceholder(block);
        });

        $( "#buttonAddTest input" ).change(function() {
            buttonAddPlaceholder($(".buttonAddFile"));
        });
    });

    function buttonAddPlaceholder(block) {
        let text = block.find("p span");
        let textPlaceholder = text.attr("data-placeholder");
        text.text(textPlaceholder);
    }

});
