@include("incl/header")

<h1>Edit</h1>

<form method="POST" id="input_form" action="/admin/department/{{$id}}" enctype="multipart/form-data">
@csrf
<div class="form-group">
    <div class="form-group">
        <label for="name"></label>
        <input required id="name" type="text" name="name" class="form-control" value=" {{$departments->name}} ">
    </div>
    <div class="form-group">
        <label for="description"></label>
        <input required id="description" type="text" name="description" class="form-control" value=" {{$departments->description}} ">
    </div>
</div>
<input type="submit" class="btn btn-primary" value="Submit">
</form>

</div>
</body>
</html>