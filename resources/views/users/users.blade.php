@include("incl/header")

<h1>Users</h1>
<p><a href="/admin/department/create" class="btn btn-dark btn-large">Add New</a><p>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>E-mail</th>
            <th>edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $result)
        <tr>
            <td> {{$result->id}} </td>
            <td> {{$result->username}} </td>
            <td> {{$result->email}} </td>
            <td> <p><a href="/admin/users/{{$result->id}}" class="btn btn-dark btn-large">Edit</a><p></td>
            <td> <p><a href="/admin/users/delete/{{$result->id}}" class="btn btn-dark btn-large">Delete</a><p></td>
        </tr>
        @endforeach
    </tbody>
</table>


</div>
</body>
</html>