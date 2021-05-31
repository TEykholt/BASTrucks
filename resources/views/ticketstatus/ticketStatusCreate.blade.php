@include("incl/header")

<h1>Create Type</h1>

<form method="POST" id="input_form" action="/ticketStatus/add" enctype="multipart/form-data">
@csrf
<div class="form-group">
    <div class="form-group">
        <label for="status">status</label>
        <input required id="status" type="text" name="status" class="form-control">
    </div>
</div>
<input type="submit" class="btn btn-primary" value="Submit">
</form>

</div>
</body>
</html>