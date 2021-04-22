@include("incl/header")
<h1 class="mt-2">Dashboard</h1>

<div class="Filter-List">
    <div class="Filter-Item">
        <!--Filter-->
        <label for="Status" class="Filter-Label">
            Status:
        </label>
        <select class="Filter-Value">
            <!--ToDo fill up with different status types-->
            <option selected value="None">None</option>
            <option value="Open">Open</option>
            <option value="In Progress">In Progress</option>
            <option value="Closed">Closed</option>
        </select>
    </div>
    <div class="Filter-Button" onclick="filter(event)">
        Filter
    </div>
</div>

<table id="tickets-table" class="table">
    <tr name="head" class="thead">
        <th>Id</th>
        <th>ticket holder</th>
        <th>type</th>
        <th>subject</th>
        <th>message</th>
        <th>expertise</th>
        <th>status</th>
    </tr>

    @foreach($results as $result)
    <tr class="trow" onclick="ToTicketViewer(event);">
        <td name="id">{{$result['id']}}</td>
        <td name="person_name">{{$result['person_name']}}</td>
        <td name="type">{{$result['type']}}</td>
        <td name="subject">{{$result['subject']}}</td>
        <td name="message">{{ \Illuminate\Support\Str::limit($result['message'], $limit = 75, $end = '...')}}</td>
        <td name="department_name">{{$result['department_name']}}</td>
        <td name="status">{{$result['status']}}</td>

        <form name="toticketviewer" action="\ticketviewer" method="POST" style="display: none;">
            @csrf
            <input hidden name="id" value="{{$result['id']}}">
        </form>
    </tr>
    @endforeach


</table>
</div>
<script>
    function ToTicketViewer(event) {
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

    function getFilterItemColumnValue(element) {
        var FilterItemChildren = element.children;
        var Item = {};

        for (let j = 0; j < FilterItemChildren.length; j++) {
            var nodeName = FilterItemChildren[j].nodeName;
            nodeName = nodeName.toUpperCase();
            switch (nodeName) {
                case "SELECT":
                    var SelectionChildren = FilterItemChildren[j].children;
                    var ItemValue = null

                    for (let z = 0; z < SelectionChildren.length; z++) {

                        if (SelectionChildren[z].nodeName == "OPTION") {
                            if (SelectionChildren[z].selected) {

                                var Attribute = SelectionChildren[z].attributes.getNamedItem("value")
                                if (Attribute) {
                                    Item["Value"] = Attribute.value.toLowerCase();
                                    break;
                                }
                            }
                        }

                    }

                    break;

                case "LABEL":
                    var Attribute = FilterItemChildren[j].attributes.getNamedItem("for")
                    if (Attribute) {
                        Item["Column"] = Attribute.value.toLowerCase();
                    }
                    break;
            }
        }

        //Fill up the columns and values
        return Item;
    }

    function getFilterList(ListOfElements) {
        var FilterItems = [];

        for (let i = 0; i < ListOfElements.length; i++) {
            const element = ListOfElements[i];
            if (element.className == "Filter-Item") {
                ListOfElements.push(getFilterItemColumnValue(element));
            }
        }
    }

    function filter(event) {
        var FilterButton, FilterList, FilterListItems;
        FilterButton = event.target;
        if (!FilterButton) {
            console.error("Filter-Button was not defined");
            return null;
        }
        FilterList = FilterButton.parentNode

        if (!FilterList) {
            console.error("Filter-List was not defined");
            return null;
        }
        if (!FilterList.className == "Filter-List") {
            console.error("Filter-List was not defined");
            return null;
        }
        var FilterItems = getFilterList(FilterList.children);

        // Debug: Print the filter variables
        for (let index = 0; index < FilterItems.length; index++) {
            const element = FilterItems[index];
            console.log(element["Value"]);
            console.log(element["Column"]);
        }

        //ToDo: Hide non matching items
    }
</script>
</body>

</html>