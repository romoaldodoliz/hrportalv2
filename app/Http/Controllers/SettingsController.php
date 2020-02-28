<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Company;
use App\Department;
use App\Location;
use App\Address;
use App\Head;
use App\Level;
use App\MaritalStatus;
use App\Division;

use DB;
use Auth;

class SettingsController extends Controller
{
    public function index(){

        // dd(Auth::user()->roles[0]->name);
        session(['header_text' => 'Settings']);
        return view('settings.index');
    }


    //---------------Company

    public function allCompany()
    {
        $companies = Company::with('divisions')->orderBy('name','ASC')->get();
        return $companies;
    }

    public function getDivision($id){
        $divisions = Division::where('company_id', '=', $id)->pluck('name','name');
        return json_encode($divisions);
    }

    public function allDivision($id){
        $divisions = Division::where('company_id', '=', $id)->get();
        return json_encode($divisions);
    }
    

    public function storeCompany(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        
        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['domain'] = $request->domain ? $request->domain : "-"; 
            if($company = Company::create($data)){
                DB::commit();
                return Company::where('id',$company->id)->first();
            }
        }catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Update company database
     */
    public function updateCompany(Request $request,Company $company)
    {

        $this->validate($request, [
            'name' => 'required'
        ]);

        $data = $request->all();


        DB::beginTransaction();
        try {

            if(isset($request->company_image)){
                $delete = Storage::disk('public')->delete('id_image/company/' .$company->id . '.png');
                if($request->file('company_image')){
                    $attachment = $request->file('company_image');   
                    $filename = $company->id . '.png';
                    $path = Storage::disk('public')->putFileAs('id_image/company', $attachment , $filename);
                }
            }

            if($company->update(['name'=>$data['name']])){


                //Add Divisions
                if($data['company_divisions']){
                $divisions = json_decode($data['company_divisions']);
                    foreach($divisions as $division){
                            $data_division = [
                                'company_id'=>$company->id,
                                'name'=>$division->name
                            ];
                            if(!empty($division->id)){
                                $company->divisions()->where('id',$division->id)->update($data_division);
                            }else{
                                $company->divisions()->create($data_division);
                            }
                    }
                }

                //Delete Divisions
                if(!empty($data['deleted_divisions'])){
                    $deleted_divisions = json_decode($data['deleted_divisions']);
                    foreach($deleted_divisions as $deleted_division){
                            if(isset($deleted_division->id)){
                                $company->divisions()->where('id',$deleted_division->id)->delete();
                            }
                    }
                }

                DB::commit();
                return Company::where('id',$company->id)->first();
            }
        }catch (Exception $e) {
            DB::rollBack();
            return $company;
        }
        
    }

    public function destroyCompany(Company $company)
    {
        if($company->delete()){
            return $company;
        }
    }


    //--------------Departments

    public function allDepartment()
    {
        $departments = Department::orderBy('name','ASC')->get();
        return $departments;
    }

    public function storeDepartment(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        
        DB::beginTransaction();
        try {
            if($department = Department::create($request->all())){
                DB::commit();
                return Department::where('id',$department->id)->first();
            }
        }catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Update Department database
     */
    public function updateDepartment(Request $request,Department $department)
    {

        $this->validate($request, [
            'name' => 'required'
        ]);

       DB::beginTransaction();
        try {
            if($department->update($request->all())){
                DB::commit();
                return Department::where('id',$department->id)->first();
            }
        }catch (Exception $e) {
            DB::rollBack();
            return $department;
        }
    }

    public function destroyDepartment(Department $department)
    {
        if($department->delete()){
            return $department;
        }
    }

    //--------------Locations

    public function allLocation()
    {
        $locations = Location::orderBy('name','ASC')->get();
        return $locations;
    }
    public function getLocationAddress(){
        $address =  Address::all();

        return json_encode($address, true);
    }
  

    public function storeLocation(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        
        DB::beginTransaction();
        try {
            if($location = Location::create($request->all())){
                DB::commit();
                return Location::where('id',$location->id)->first();
            }
        }catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Update Location database
     */
    public function updateLocation(Request $request,Location $location)
    {

        $this->validate($request, [
            'name' => 'required'
        ]);

       DB::beginTransaction();
        try {
            if($location->update($request->all())){
                DB::commit();
                return Location::where('id',$location->id)->first();
            }
        }catch (Exception $e) {
            DB::rollBack();
            return $location;
        }
    }

    public function destroyLocation(Location $location)
    {
        if($location->delete()){
            return $location;
        }
    }

    //--------------Addresses

    public function allAddress()
    {
        $addresses = Address::orderBy('name','ASC')->get();
        return $addresses;
    }

    public function storeAddress(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        
        DB::beginTransaction();
        try {
            if($address = Address::create($request->all())){
                DB::commit();
                return Address::where('id',$address->id)->first();
            }
        }catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Update Address database
     */
    public function updateAddress(Request $request,Address $address)
    {

        $this->validate($request, [
            'name' => 'required'
        ]);

       DB::beginTransaction();
        try {
            if($address->update(['name'=>$request->get('name')])){
                DB::commit();
                return Address::where('id',$address->id)->first();
            }
        }catch (Exception $e) {
            DB::rollBack();
            return $address;
        }
    }

    public function destroyAddress(Address $address)
    {
        if($address->delete()){
            return $address;
        }
    }

    //--------------Heads

    public function allHead()
    {
        $heads = Head::orderBy('name','ASC')->get();
        return $heads;
    }

    public function storeHead(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        
        DB::beginTransaction();
        try {
            if($head = Head::create($request->all())){
                DB::commit();
                return Head::where('id',$head->id)->first();
            }
        }catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Update Head database
     */
    public function updateHead(Request $request,Head $head)
    {

        $this->validate($request, [
            'name' => 'required'
        ]);

       DB::beginTransaction();
        try {
            if($head->update(['name'=>$request->get('name')])){
                DB::commit();
                return Head::where('id',$head->id)->first();
            }
        }catch (Exception $e) {
            DB::rollBack();
            return $head;
        }
    }

    public function destroyHead(Head $head)
    {
        if($head->delete()){
            return $head;
        }
    }


    //--------------Levels

    public function allLevel()
    {
        $levels = Level::orderBy('name','ASC')->get();
        return $levels;
    }

    public function allLevelOptions()
    {
        $levels = Level::orderBy('name','ASC')->pluck('name', 'name');
        return $levels;
    }

    public function storeLevel(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        
        DB::beginTransaction();
        try {
            if($level = Level::create($request->all())){
                DB::commit();
                return Level::where('id',$level->id)->first();
            }
        }catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Update Level database
     */
    public function updateLevel(Request $request,Level $level)
    {

        $this->validate($request, [
            'name' => 'required'
        ]);

       DB::beginTransaction();
        try {
            if($level->update(['name'=>$request->get('name')])){
                DB::commit();
                return Level::where('id',$level->id)->first();
            }
        }catch (Exception $e) {
            DB::rollBack();
            return $level;
        }
    }

    public function destroyLevel(Level $level)
    {
        if($level->delete()){
            return $level;
        }
    }


    //--------------Marital Status

    public function allMarital()
    {
        $maritals = MaritalStatus::orderBy('id','DESC')->get();
        return $maritals;
    }
    public function allMaritalOptions()
    {
        $maritals = MaritalStatus::orderBy('id','DESC')->pluck('name', 'name');
        return $maritals;
    }

    public function storeMarital(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        
        DB::beginTransaction();
        try {
            if($marital = MaritalStatus::create($request->all())){
                DB::commit();
                return MaritalStatus::where('id',$marital->id)->first();
            }
        }catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Update Marital database
     */
    public function updateMarital(Request $request,MaritalStatus $marital)
    {

        $this->validate($request, [
            'name' => 'required'
        ]);

       DB::beginTransaction();
        try {
            if($marital->update(['name'=>$request->get('name')])){
                DB::commit();
                return MaritalStatus::where('id',$marital->id)->first();
            }
        }catch (Exception $e) {
            DB::rollBack();
            return $marital;
        }
    }

    public function destroyMarital(MaritalStatus $marital)
    {
        if($marital->delete()){
            return $marital;
        }
    }
}
