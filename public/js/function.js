$(document).ready(function() {
    $(".btn-submit").click(function(e){
        e.preventDefault();
        var street = $("input[name='street']").val();
        var parcel_number =$("input[name='parcel_number']").val();
        var post_code = $("input[name='post_code']").val();
        var country = $("input[name='country']").val();

        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });

        $.ajax({
            url: "../addNewAddress",
            type:'POST',
            data: { street:street, parcel_number:parcel_number, post_code:post_code,country:country},
            success: function(data) {
              alert(data);
            }
        });
    });
});
