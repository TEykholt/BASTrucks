@include("incl/header")
        <h1 class="mt-2">Dashboard</h1>
        <table class="table">
            <tr class="thead">
                <th>Id</th>
                <th>name</th>
                <th>type</th>
                <th>subject</th>
                <th>message</th>
                <th>expertise</th>
                <th>status</th>
            </tr>

            @foreach($results as $result)
                <tr class="trow" onclick="submitForm(event);">
                    <td>{{$result['id']}}</td>
                    <td>{{$result['name']}}</td>
                    <td>{{$result['type']}}</td>
                    <td>{{$result['subject']}}</td>
                    <td>{{ \Illuminate\Support\Str::limit($result['message'], $limit = 75, $end = '...')}}</td>
                    <td>{{$result['name']}}</td>
                    <td>{{$result['status']}}</td>

                    <form name="toticketviewer" action="\ticketviewer" method="POST" style="display: none;">
                        @csrf
                        <input hidden name="id" value="{{$result['id']}}">
                    </form>
                </tr>
            @endforeach


        </table>
    </div>
    <script>
        function submitForm(event){
            var TrChildren = event.target.parentNode.children
            var toticketviewerForm = null

            for (let index = 0; index < TrChildren.length; index++) {
                const element = TrChildren[index];
                if (element.nodeName == "FORM") {
                    toticketviewerForm = element
                    break;
                }
            }
            if (toticketviewerForm) {
                toticketviewerForm.submit();
            }
        }
    </script>
    </body>
</html>
