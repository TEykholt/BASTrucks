<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>Dashboard</title>
        <link href="/css/app.css" rel="stylesheet">
    </head>
    <body>
    <div class="container">
        <h1 class="mt-2">Dashboard</h1>
        <table class="table">
            <tr>
                <th>Id</th>
                <th>username</th>
                <th>message</th>
                <th>expertise</th>
                <th>status</th>
            </tr>

            @foreach($results as $result)
                <tr>
                    <td>{{$result['id']}}</td>
                    <td>{{$result['username']}}</td>
                    <td>{{$result['message']}}</td>
                    <td>{{$result['name']}}</td>
                    <td>{{$result['status']}}</td>
                </tr>
            @endforeach
        </table>
    </div>

    </body>
</html>
