@include("incl/header")

<h1>Status</h1>
<table class="table">
    <thead>
        <tr>
            <th>Status</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        @foreach($status as $result)
        <tr>
            <td> {{$result->status}} </td>
            <td> <p><a href="/ticketStatus/{{$result->id}}" class="btn btn-dark btn-large">Edit</a><p></td>
            <td> <p><a href="/ticketStatus/delete/{{$result->id}}" class="btn btn-dark btn-large">Delete</a><p></td>
        </tr>
        @endforeach
    </tbody>
</table>
<p><a href="/ticketStatus/create" class="btn btn-dark btn-large">Add New</a><p>

</div>
</body>
</html>