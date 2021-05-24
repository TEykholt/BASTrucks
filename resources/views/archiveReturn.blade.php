<table id="tickets-table" class="table mt-4">
    <tr name="head" class="thead">
        <th>Id</th>
        <th>ticket holder</th>
        <th>type</th>
        <th>subject</th>
        <th>message</th>
        <th>department</th>
        <th>Worker</th>
        <th>status</th>
    </tr>
    @foreach($data as $_data)
        <tr class="trow" onclick="ToTicketViewer(event);">
            <td name="id">{{$_data['id']}}</td>
            <td name="person_name">{{$_data['person_name']}}</td>
            <td name="type">{{$_data['type']}}</td>
            <td name="subject">{{$_data['subject']}}</td>
            <td name="message">{{ \Illuminate\Support\Str::limit($_data['message'], $limit = 75, $end = '...')}}</td>
            <td name="department_name">{{$_data['department_name']}}</td>
            <td name="ticketHolder">TODO: ticket werkers werkend maken</td>
            <td name="status">{{$_data['status']}}</td>

                <form hidden name="toticketviewer" action="/ticketviewerArchive" method="POST" >
                    @csrf
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input hidden id="id"  name="id" value="{{$_data['id']}}">
                </form>

        </tr>
    @endforeach
</table>
