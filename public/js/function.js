$(document).ready(function() {
    $(".btn-submit").click(function(e){
        e.preventDefault();

        alert("działa");
        /*
        var _token = $("input[name='_token']").val();
        var email = $("#email").val();
        var pswd = $("#pwd").val();
        var address = $("#address").val();

        $.ajax({
            url: "{{ route('ajax.request.store') }}",
            type:'POST',
            data: {_token:_token, email:email, pswd:pswd,address:address},
            success: function(data) {
              printMsg(data);
            }
        });
        */
    });
});
