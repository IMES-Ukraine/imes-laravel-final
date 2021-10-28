$(document).ready(function () {

    window.addEventListener("load", function(event) {
        $(".buttonAddFile").each(function () {
            let block = $(this);
            let field = block.find("input");
            let text = block.find("p span");
            let textPlaceholder = text.attr("data-placeholder");
            text.text(textPlaceholder);
            field.change(function (e) {
                let fileName = e.target.files[0].name;
                block.addClass("has_file");
                text.text(fileName);
            });
        });

        $(document).on('click', '.deleteFile', function () {
            let btn = $(this);
            let block = btn.parents(".buttonAddFile");
            let field = block.find("input");
            let text = block.find("p span");
            let textPlaceholder = text.attr("data-placeholder");
            field.val("");
            text.text(textPlaceholder);
            block.removeClass("has_file");
        });
    });

});
