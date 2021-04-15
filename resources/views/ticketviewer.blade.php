@include("incl/header")
        <h1 class="mt-2">Dashboard</h1>
        <table class="table">
            <tr class="thead">
                <th>Id</th>
                <th>username</th>
                <th>type</th>
                <th>subject</th>
                <th>message</th>
                <th>expertise</th>
                <th>status</th>
            </tr>

            @foreach($results as $result)
                <tr class="trow">
                    <td>{{$result['id']}}</td>
                    <td>{{$result['username']}}</td>
                    <td>{{$result['type']}}</td>
                    <td>{{$result['subject']}}</td>
                    <td>{{$result['message']}}</td>
                    <td>{{$result['name']}}</td>
                    <td>{{$result['status']}}</td>
                </tr>
            @endforeach
        </table>
    </div>

    </body>
</html>
