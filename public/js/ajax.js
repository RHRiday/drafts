$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
})

function addContent(type, id) {
    console.log(type);
    let content = $('#' + type + 'Content');
    $.ajax({
        type: "post",
        url: "/dokkoblog/" + id + "/update",
        data: {
            type: type,
            content: content.val()
        },
        success: function (response) {
            content.val('');
            $('trix-editor').html('');
            appendContent(response.type, response.content);
            $('.btn-close').trigger('click');
        }
    });
}

function appendContent(type, content) {
    switch (type) {
        case 'text':
            toAppend = `<div class="bg-light p-2 fs-5 overflow-auto mt-3 rounded ff-merryweather">` + content + `</div>`;
            break;

        case 'header':
            toAppend = `<div class="bg-light p-2 fs-5 overflow-auto mt-3 rounded ff-catamaran"><h3>` + content + `<h3></div>`;
            break;

        case 'image':
            toAppend = `<img src="` + content + `" alt="Not a valid image" class="mt-3 w-100">`;
            break;

        case 'code':
            toAppend = `<div class="bg-dark text-warning p-2 overflow-auto text-nowrap mt-3 ff-source-code">` + content + `</div>`;
            break;

        default:
            break;
    }

    appendFinal =
        `<div class="col-12 my-2 position-relative">
            <div class="position-absolute top-0 end-0">
                <button onclick="window.location.reload()" class="btn btn-sm btn-link badge bg-info reload-btn" type="button">
                    Refresh to edit
                </button>
            </div>` + toAppend +
        `</div>`;

    $('#contents').append(appendFinal);
    console.log('success');
}
