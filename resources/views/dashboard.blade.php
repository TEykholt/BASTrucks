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
                <th>subject</th>
                <th>status id</th>
            </tr>
            @foreach($results as $result)
                <tr>
                    <td>{{$result['id']}}</td>
                    <td>{{$result['username']}}</td>
                    <td>{{$result['subject']}}</td>
                    <td>{{$result['status_id']}}</td>
                </tr>
            @endforeach
        </table>
    </div>

    </body>
</html>
