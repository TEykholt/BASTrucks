@include("incl/header")

<h1>Department</h1>
<table class="table">
    <thead>
        <tr>
            <th>department</th>
            <th>description</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        @foreach($departments as $result)
        <tr>
            <td> {{$result->name}} </td>
            <td> {{$result->description}} </td>
            <td> <p><a href="/admin/department/{{$result->id}}" class="btn btn-dark btn-large">Edit</a><p></td>
            <td> <p><a href="/admin/department/delete/{{$result->id}}" class="btn btn-dark btn-large">Delete</a><p></td>
        </tr>
        @endforeach
    </tbody>
</table>
<p><a href="/admin/department/create" class="btn btn-dark btn-large">Add New</a><p>

</div>
</body>
</html>