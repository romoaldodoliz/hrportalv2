
<h4>Hi HR Team,</h4>

<p>
    Please be notified that Employee Name : {{ $employee_name }} ({{$position}}) update his/her HMO dependents.
</p>
@if($dependents)
<center>
    <table style="border:1px solid black;border-collapse:collapse;width:60%" >
        <tr>
            <td colspan="5" style="border:1px solid black;padding:3px">
                <center>
                    <font color="#000000" face="arial" style="FONT-SIZE:10pt">
                        <b>Dependents Update</b>
                    </font>
                </center>
            </td>
        </tr>
        <tr>
            <td style="border:1px solid black;padding:3px">Name</td>
            <td style="border:1px solid black;padding:3px">Relation</td>
            <td style="border:1px solid black;padding:3px">Birthdate</td>
            <td style="border:1px solid black;padding:3px">Gender</td>
            <td style="border:1px solid black;padding:3px">Status</td>
        </tr>
        <tr>
            @foreach ($dependents as $item)
                <td>{{$item->dependent_name}}</td>
                <td>{{$item->relation}}</td>
                <td>{{$item->bdate}}</td>
                <td>{{$item->dependent_gender}}</td>
                <td>{{$item->dependent_status}}</td>
            @endforeach
        </tr>
    </table>
</center>
<br>
@endif
@if($deleted_dependents)
<center>
    <table style="border:1px solid black;border-collapse:collapse;width:60%" >
        <tr>
            <td colspan="5" style="border:1px solid black;padding:3px">
                <center>
                    <font color="#000000" face="arial" style="FONT-SIZE:10pt">
                        <b>Deleted Dependents </b>
                    </font>
                </center>
            </td>
        </tr>
        <tr>
            <td style="border:1px solid black;padding:3px">Name</td>
            <td style="border:1px solid black;padding:3px">Relation</td>
            <td style="border:1px solid black;padding:3px">Birthdate</td>
            <td style="border:1px solid black;padding:3px">Gender</td>
            <td style="border:1px solid black;padding:3px">Status</td>
        </tr>
        <tr>
            @foreach ($deleted_dependents as $item)
                <td>{{$item->dependent_name}}</td>
                <td>{{$item->relation}}</td>
                <td>{{$item->bdate}}</td>
                <td>{{$item->dependent_gender}}</td>
                <td>{{$item->dependent_status}}</td>
            @endforeach
        </tr>
    </table>
</center>
@endif

<p>
    Thank you,
</p>
<p>
    Group Human Resources
</p>
<p>
    ************ This is a system generated email. Please do not reply. ************
</p>

<small>
    This email contains confidential information for the sole use of the intended recipient/s. If you are not the intended recipient, please contact the sender, delete this email and maintain the confidentiality of what you may have read. Any views or opinions expressed in this email are those of the individual and not necessarily of LFUG-GOC.
</small>

