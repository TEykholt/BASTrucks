@include("incl/header")
<input class="form-control" id="input" type="text" placeholder="Enter ticket number" onchange="checkArchive();">

<div id="divContent"></div>
<script>
    function checkArchive(){
        var data = {input: $('#input').val()}
        console.log(data);

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            dataType : 'json',
            url: "/checkArchive",
            data: data,
            success: function (data) {
                $("#divContent").html(data);
            },
            error: function(xhr, status, error) {
                $("#divContent").html(xhr.responseText)
                console.log(error);
            }
        })
    }
</script>
