$(document).ready(function () {
    window.addEventListener("load", function(event) {
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
