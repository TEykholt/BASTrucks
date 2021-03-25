<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    </head>
    <body>
        <table>
            <tr>
                <th>Id</th>
                <th>subject</th>
                <th>status id</th>
            </tr>
            @foreach($results as $result)
                <tr>
                    <td>{{$result['id']}}</td>
                    <td>{{$result['subject']}}</td>
                    <td>{{$result['status_id']}}</td>
                </tr>
            @endforeach
        </table>
    </body>
</html>
