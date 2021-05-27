@include("incl/header")

<h1>Types</h1>
<table class="table">
    <thead>
        <tr>
            <th>Type</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        @foreach($types as $result)
        <tr>
            <td> {{$result->name}} </td>
            <td> <p><a href="/ticketType/{{$result->id}}" class="btn btn-dark btn-large">Edit</a><p></td>
            <td> <p><a href="/ticketType/delete/{{$result->id}}" class="btn btn-dark btn-large">Delete</a><p></td>
        </tr>
        @endforeach
    </tbody>
</table>
<p><a href="/ticketType/create" class="btn btn-dark btn-large">Add New</a><p>

</div>
</body>
</html>