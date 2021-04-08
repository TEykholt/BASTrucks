@include("incl/header")
<h1>Insert Ticket</h1>
        <form method="POST" action="/ticketInput/addTicket">
            @csrf

            <?php 
                $results = array(
                    array(
                        "id" => 1,
                        "name" => "ICT",
                    )
                );
            
                $TicketTypes = array(
                    "Feature",
                    "Bug",
                    "Change",
                    "Reporting error"
                );

            ?>

            <div class="form-group">
                <label for="ticket_type">Type</label>
                <select id="ticket_type" class="form-control" name="ticket_type" id="ticket_type">
                    @foreach($TicketTypes as $type)
                        <option value="{{$type}}">{{$type}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="department_id">Department</label>
                <select id="department_id" class="form-control" name="department_id" id="Department">
                    @foreach($results as $result)
                        <option value="{{$result['id']}}">{{$result['name']}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="subject">Subject</label>
                <input id="subject" type="text" name="subject" class="form-control">
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <input id="message" type="text" name="message" class="form-control">
            </div>
            <div class="form-group">
                <label for="Attachments">Attachments</label>
                <input type="file" accept="image/*" name="Attachments" id="Attachments" class="form-control" multiple>
            </div>

            <div class="form-group">
                <label for="person_id">Person_id</label>
                <input id="person_id" type="text" name="person_id" class="form-control">
            </div>    


            <input type="submit" class="btn btn-primary">
        </form>
        </div>
    </body>
</html>
