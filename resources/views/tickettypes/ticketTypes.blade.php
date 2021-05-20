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
            <td> <p><a href="#" class="btn btn-dark btn-large">Edit</a><p> </td>
            <td> <p><a href="#" class="btn btn-dark btn-large">Delete</a><p> </td>
        </tr>
        @endforeach
    </tbody>
</table>


</div>
</body>
</html>