$(document).ready(function() {
    $(".btn-submit").click(function(e){
        e.preventDefault();
        var street = $("input[name='street']").val();
        var parcel_number =$("input[name='parcel_number']").val();
        var post_code = $("input[name='post_code']").val();
        var country = $("input[name='country']").val();
        var client_id = $("input[name='client_id']").val();
        var order_id = $("input[name='order_id']").val();
        var city = $("input[name='city']").val();

        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });

        $.ajax({
            url: "../addNewAddress",
            type:'POST',
            data: { street:street, parcel_number:parcel_number, post_code:post_code,country:country,client_id:client_id,order_id:order_id,city:city},
            success: function(data) {
              alert(data);
            }
        });
    });

    $(".editStatus").click(function(e){
        e.preventDefault();
        var oneDetail = $(this).attr('var');
        var twoDetail = $( "#status option:selected" ).val();
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });

        $.ajax({
            url: "../editStatus",
            type:'POST',
            data: { oneDetail:oneDetail, twoDetail:twoDetail },
            success: function(data) {
              alert(data);
            }
        });
    });

    $(".printCMR").click(function(e){
        e.preventDefault();
        $('#formCMR').css('display','block');
        $('#windowInfo').css('display','block');
        /*
        e.preventDefault();
        var oneDetail = $(this).attr('var');
        var twoDetail = $( "#status option:selected" ).val();
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });

        $.ajax({
            url: "../editStatus",
            type:'POST',
            data: { oneDetail:oneDetail, twoDetail:twoDetail },
            success: function(data) {
              alert(data);
            }
        });
        */
    });

    $(".close").click(function(e){
        e.preventDefault();
        $('#formCMR').css('display','none');
        $('#windowInfo').css('display','none');
        /*
        e.preventDefault();
        var oneDetail = $(this).attr('var');
        var twoDetail = $( "#status option:selected" ).val();
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });

        $.ajax({
            url: "../editStatus",
            type:'POST',
            data: { oneDetail:oneDetail, twoDetail:twoDetail },
            success: function(data) {
              alert(data);
            }
        });
        */
    });

});
