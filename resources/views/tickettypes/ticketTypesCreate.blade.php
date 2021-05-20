<h1>Create Type</h1>

<form method="POST" id="input_form" action="/ticketTypes/Create" enctype="multipart/form-data">
@csrf
<div class="form-group">
    <label for="name">Name</label>
    <div class="form-group">
        <label for="name">Name</label>
        <input required id="name" type="text" name="name" class="form-control">
    </div>
</div>
<input type="button" class="btn btn-primary" value="Submit">
</form>

</div>
</body>
</html>