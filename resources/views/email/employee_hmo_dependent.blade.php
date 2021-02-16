
<h4>Hi Compensation and Benefits Team,</h4>

<p>
    Please be notified that Employee Name : {{ $employee_name }} ({{$position}}) update his/her HMO dependents.
</p>
@if($dependents)
<center>
    <table style="border:1px solid black;border-collapse:collapse;width:60%" >
        <tr>
            <td colspan="10" style="border:1px solid black;padding:3px">
                <center>
                    <font color="#000000" face="arial" style="FONT-SIZE:10pt">
                        <b>HMO Dependents Update</b>
                    </font>
                </center>
            </td>
        </tr>
        <tr>
            <td style="border:1px solid black;padding:3px">Full Name</td>
            <td style="border:1px solid black;padding:3px">First Name</td>
            <td style="border:1px solid black;padding:3px">Last Name</td>
            <td style="border:1px solid black;padding:3px">Middle Name</td>
            <td style="border:1px solid black;padding:3px">Relation</td>
            <td style="border:1px solid black;padding:3px">Birthdate</td>
            <td style="border:1px solid black;padding:3px">Gender</td>
            <td style="border:1px solid black;padding:3px">Action</td>
            <td style="border:1px solid black;padding:3px">Civil Status</td>
            <td style="border:1px solid black;padding:3px">HMO Enrollment</td>
        </tr>
        
        @foreach ($dependents as $item)
        <tr>
            <td style="border:1px solid black;padding:3px">{{$item->dependent_name}}</td>
            <td style="border:1px solid black;padding:3px">{{$item->first_name}}</td>
            <td style="border:1px solid black;padding:3px">{{$item->last_name}}</td>
            <td style="border:1px solid black;padding:3px">{{$item->middle_name}}</td>
            <td style="border:1px solid black;padding:3px">{{$item->relation}}</td>
            <td style="border:1px solid black;padding:3px">{{$item->bdate}}</td>
            <td style="border:1px solid black;padding:3px">{{$item->dependent_gender}}</td>
            <td style="border:1px solid black;padding:3px">{{$item->dependent_status}}</td>
            <td style="border:1px solid black;padding:3px">{{$item->civil_status}}</td>
            <td style="border:1px solid black;padding:3px">{{$item->hmo_enrollment}}</td>
        </tr>
        @endforeach
        
    </table>
</center>
<br>
@endif
@if($deleted_dependents)
<center>
    <table style="border:1px solid black;border-collapse:collapse;width:60%" >
        <tr>
            <td colspan="10" style="border:1px solid black;padding:3px">
                <center>
                    <font color="#000000" face="arial" style="FONT-SIZE:10pt">
                        <b>Deleted HMO Dependents </b>
                    </font>
                </center>
            </td>
        </tr>
        <tr>
            <td style="border:1px solid black;padding:3px">Full Name</td>
            <td style="border:1px solid black;padding:3px">First Name</td>
            <td style="border:1px solid black;padding:3px">Last Name</td>
            <td style="border:1px solid black;padding:3px">Middle Name</td>
            <td style="border:1px solid black;padding:3px">Relation</td>
            <td style="border:1px solid black;padding:3px">Birthdate</td>
            <td style="border:1px solid black;padding:3px">Gender</td>
            <td style="border:1px solid black;padding:3px">Action</td>
            <td style="border:1px solid black;padding:3px">Civil Status</td>
            <td style="border:1px solid black;padding:3px">HMO Enrollment</td>
        </tr>
        
        @foreach ($deleted_dependents as $item)
        <tr>
            <td style="border:1px solid black;padding:3px">{{$item->dependent_name}}</td>
            <td style="border:1px solid black;padding:3px">{{$item->first_name}}</td>
            <td style="border:1px solid black;padding:3px">{{$item->last_name}}</td>
            <td style="border:1px solid black;padding:3px">{{$item->middle_name}}</td>
            <td style="border:1px solid black;padding:3px">{{$item->relation}}</td>
            <td style="border:1px solid black;padding:3px">{{$item->bdate}}</td>
            <td style="border:1px solid black;padding:3px">{{$item->dependent_gender}}</td>
            <td style="border:1px solid black;padding:3px">{{$item->dependent_status}}</td>
            <td style="border:1px solid black;padding:3px">{{$item->civil_status}}</td>
            <td style="border:1px solid black;padding:3px">{{$item->hmo_enrollment}}</td>
        </tr>
        @endforeach
        
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

