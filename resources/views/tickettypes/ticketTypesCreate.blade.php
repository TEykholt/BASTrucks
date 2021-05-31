@include("incl/header")

<h1>Create Type</h1>

<form method="POST" id="input_form" action="/admin/ticketType/add" enctype="multipart/form-data">
@csrf
<div class="form-group">
    <div class="form-group">
        <label for="name">Name</label>
        <input required id="name" type="text" name="name" class="form-control">
    </div>
</div>
<input type="submit" class="btn btn-primary" value="Submit">
</form>

</div>
</body>
</html>