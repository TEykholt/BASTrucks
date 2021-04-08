@include("incl/header")
        <form method="POST" action="/ticketInput/addTicket">        
            @csrf

            <label for="">subject</label><input type="text" name="subject">
            <label for="">message</label><input type="text" name="message">
            <label for="">person_id</label><input type="text" name="person_id">

            <?php $results = array(
                array(
                    "id" => 1,
                    "name" => "ICT",             
                )
            )?>
            <label for="">Department</label>
            <select name="department_id" id="Department">
                @foreach($results as $result)
                    <option value="{{$result['id']}}">{{$result['name']}}</option>
                @endforeach
            </select>
            <input type="submit">
        </form>
    </div>

    </body>
</html>
