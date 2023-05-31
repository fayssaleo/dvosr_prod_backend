<?php

namespace App\Modules\Voyage\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\ActionDetail\Models\ActionDetail;
use App\Modules\Code\Models\Code;
use App\Modules\Crane\Models\Crane;
use App\Modules\Utilisateur\Models\Action;
use App\Modules\Vessel\Models\Vessel;
use App\Modules\Voyage\Models\CraneVoyage;
use App\Modules\Voyage\Models\OtherDelay;
use App\Modules\Voyage\Models\Voyage;
use Carbon\Carbon;
use Exception as GlobalException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use mysql_xdevapi\Exception;

class VoyageController extends Controller
{
    public  $voyageColums=[
        "vawgd",
        "vawsnrog",
        "dm_y",
        "dm_g",
        "hatch_covers_num",
        "hatch_covers_moves",
        "gear_boxes_num",
        "gear_boxes_moves",
        "first_line_datetime",
        "vessel_all_fast",
        "gangway_secured",
        "lashers_onboard",
        "num_mooring_r_fore",
        "num_mooring_r_aft",
        "dwuscfb",
        "imo_class",
        "imo_class_ps_onb",
        "last_lift_from",
        "last_lift_to",
        "last_lift_comment",
        "lf_from",
        "lf_to",
        "lf_comment",
        "agent_onboard_from",
        "agent_onboard_to",
        "agent_onboard_comment",
        "safety_net_gangway_from",
        "safety_net_gangway_to",
        "safety_net_gangway_comment",
        "pilot_onboard_from",
        "pilot_onboard_to",
        "pilot_onboard_comment",
        "tugs_arrived_from",
        "tugs_arrived_to",
        "tugs_arrived_comment",
        "unmooring_forward_from",
        "unmooring_forward_to",
        "unmooring_forward_comment",
        "unmooring_aft_from",
        "unmooring_aft_to",
        "unmooring_aft_comment",
        "last_line_from",
        "last_line_to",
        "last_line_comment",
        "manoeuvre_sequence",
        "pgb_r_co",
        "pgb_r_co_reason",
        "gear_boxes_num_40",
        "hatch_covers_num_40",
        "any_hydraulic_couvers",
    ];
    public  $voyageColumsText=[
        "last_lift_comment",
        "lf_comment",
        "agent_onboard_comment",
        "safety_net_gangway_comment",
        "pilot_onboard_comment",
        "tugs_arrived_comment",
        "unmooring_forward_comment",
        "unmooring_aft_comment",
        "last_line_comment",
        "pgb_r_co_reason",
    ];
    public  $voyageColumsDate=[
        "first_line_datetime",
        "vessel_all_fast",
        "gangway_secured",
        "lashers_onboard",
        "last_lift_from",
        "last_lift_to",
        "lf_from",
        "lf_to",
        "agent_onboard_from",
        "agent_onboard_to",
        "safety_net_gangway_from",
        "safety_net_gangway_to",
        "pilot_onboard_from",
        "pilot_onboard_to",
        "tugs_arrived_from",
        "tugs_arrived_to",
        "unmooring_forward_from",
        "unmooring_forward_to",
        "unmooring_aft_from",
        "unmooring_aft_to",
        "last_line_from",
        "last_line_to",
    ];
    public  $voyageColumsInt=[
        "hatch_covers_num",
        "hatch_covers_moves",
        "gear_boxes_num",
        "gear_boxes_moves",
        "num_mooring_r_fore",
        "num_mooring_r_aft",
        "gear_boxes_num_40",
        "hatch_covers_num_40",
        "manoeuvre_sequence",
    ];
    public  $voyageColumsString=[     
        "imo_class_ps_onb",
        "pgb_r_co_reason",
        "tele",
    ];
    public  $voyageColumsBool=[
        "vawgd",
        "vawsnrog",
        "dm_y",
        "dm_g",
        "dwuscfb",
        "imo_class",
        "pgb_r_co",
        "any_hydraulic_couvers",
    ];
    public  $craneVoyageColums=[
        "cbd",
        "dgbohc_bfl_from",
        "dgbohc_bfl_to",
        "dgbohc_bfl_num_gb",
        "dgbohc_bfl_num_hc",
        "dss_bfl_from",
        "dss_bfl_to",
        "dss_bfl_num_sp",
        "dss_bfl_fb_dnw",
        "dss_bfl_fb",
        "ffl",
        "fll",
        "sfl",
        "sll",
        "tfl",
        "tll",
        "fofl",
        "foll",
        "fifl",
        "fill",
        "sifl",
        "sill",
        "sevfl",
        "sevll",
        "eifl",
        "eill",
        "nfl",
        "nll",
        "tenfl",
        "tenll",
        "lgbohc_all_from",
        "lgbohc_all_to",
        "lgbohc_all_num_gb",
        "lgbohc_all_hc",
        "lgbohc_all_inbay",
        "lgbohc_all_inbay_hatch_covers",
        "lss_all_from",
        "lss_all_to",
        "lss_all_num_ss",
        "lss_all_ib_lnw",
        "lss_all_ib",
        "cbu",
       
    ];
    public  $otherDelaysColums=[
        "from",
        "to",
        "reason",
        "comment",
        "dep_arr",
        "code",
        "category",
        "crane_id",
    ];
    public function index(){
        $ExistData = array();
        $rs=Voyage::with("crane_voyages")->with("other_delays")->with("vessel")->get();
        for ($i = 0; $i < count($rs); $i++) {
            if(!$rs[$i]->vessel->archived)
            if(date('y-m-d h:i',strtotime($rs[$i]->vessel->eta))>=date('y-m-d h:i',strtotime("-2 day")) && date('y-m-d h:i',strtotime($rs[$i]->vessel->etd))<=date('y-m-d h:i',strtotime("+2 day"))){
                array_push($ExistData, $rs[$i]);
            }
        }
        return [
            "payload" => $ExistData,
            "status" => "200"
        ];
    }
    public function getAllVoyages(){
        $ExistData = array();
        $rs=Voyage::with("crane_voyages")->with("other_delays")->with("vessel","vessel.actions2","vessel.actions2.actionDetails")->get();
        for ($i = 0; $i < count($rs); $i++) {
            if(!$rs[$i]->vessel->archived)
            if(date('y-m-d h:i',strtotime($rs[$i]->vessel->eta))>=date('y-m-d h:i',strtotime("-7 day")) && date('y-m-d h:i',strtotime($rs[$i]->vessel->etd))<=date('y-m-d h:i',strtotime("+3 day"))){
                array_push($ExistData, $rs[$i]);

            }
        }
        return $ExistData;
        return [
            "payload" => $ExistData,
            "status" => "200"
        ];
    }
    public function archivedIndex(){
        $ExistData = array();
        $rs=Voyage::with("crane_voyages")->with("other_delays")->with("vessel")->get();
        for ($i = 0; $i < count($rs); $i++) {
            if($rs[$i]->vessel->archived)
            if(date('y-m-d h:i',strtotime($rs[$i]->vessel->eta))>=date('y-m-d h:i',strtotime("-2 day")) && date('y-m-d h:i',strtotime($rs[$i]->vessel->etd))<=date('y-m-d h:i',strtotime("+2 day"))){
                array_push($ExistData, $rs[$i]);

            }
        }
        return [
            "payload" => $ExistData,
            "status" => "200"
        ];
    }
    public function indexAll(){

        $ExistData = array();
        $rs=Voyage::with("crane_voyages")->with("other_delays")->with("vessel")->get();
        for ($i = 0; $i < count($rs); $i++) {
            if(!$rs[$i]->vessel->archived)
                array_push($ExistData, $rs[$i]);

            
        }
        return [
            "payload" => $ExistData,
            "status" => "200"
        ];
    }
    public function archivedIndexAll(){

        $ExistData = array();
        $rs=Voyage::with("crane_voyages")->with("other_delays")->with("vessel")->get();
        for ($i = 0; $i < count($rs); $i++) {
            if($rs[$i]->vessel->archived)
                array_push($ExistData, $rs[$i]);

            
        }
        return [
            "payload" => $ExistData,
            "status" => "200"
        ];
    }
    public function get($id){

        $vessel=Voyage::select()->where("id","=",$id)->with("crane_voyages")->with("other_delays")->with("vessel")->first();
        if(!$vessel){
            return [
                "payload" => "The searched row does not exist !",
                "status" => "404_1"
            ];
        }
        else {
            return [
                "payload" => $vessel,
                "status" => "200"
            ];

        }
    }
    public function delete(Request $request){

        $validator = Validator::make($request->all(), [
            "id" => "required",
        ]);
        if ($validator->fails()) {
            return [
                "payload" => $validator->errors(),
                "status" => "406_2_delete"
            ];
        }
        $vessel=Vessel::find($request->id);
        if(!$vessel){
            return [
                "payload" => "The searched row does not exist !",
                "status" => "404_4"
            ];
        }
        else {
            $vessel->delete();

            return [
                "payload" => "Deleted successfully",
                "status" => "200"
            ];
        }
    }
    public function archive(Request $request){

        $validator = Validator::make($request->all(), [
            "id" => "required",
        ]);
        if ($validator->fails()) {
            return [
                "payload" => $validator->errors(),
                "status" => "406_2_delete"
            ];
        }
        $vessel=Vessel::find($request->id);
        if(!$vessel){
            return [
                "payload" => "The searched row does not exist !",
                "status" => "404_4"
            ];
        }
        else {
            $vessel->archived=true;
            $vessel->archived_by=auth()->user()->username;
            $vessel->archived_at=Carbon::now();
            $vessel->save();
            return [
                "payload" => "Archived successfully",
                "status" => "200"
            ];
        }
    }
    public function unarchive(Request $request){

        $validator = Validator::make($request->all(), [
            "id" => "required",
        ]);
        if ($validator->fails()) {
            return [
                "payload" => $validator->errors(),
                "status" => "406_2_delete"
            ];
        }
        $vessel=Vessel::find($request->id);
        if(!$vessel){
            return [
                "payload" => "The searched row does not exist !",
                "status" => "404_4"
            ];
        }
        else {
            $vessel->archived=false;
            $vessel->unarchived_by=auth()->user()->username;
            $vessel->unarchived_at=Carbon::now();
            $vessel->save();
            return [
                "payload" => "Archived successfully",
                "status" => "200"
            ];
        }
    }
    public function crane_vouyages_validatorAndSaver($crane_voyage,$voyage_id,$action_id){
        $validator = Validator::make($crane_voyage, [
            "crane_id" => "required|integer",
        ]);
        if ($validator->fails()) {

            return [
                "payload" => $validator->errors(),
                "status" => "406_2_crane",
                "IsReturnErrorRespone" => true
            ];
        }
        $voyage=Voyage::find($voyage_id);
        if(!$voyage){
            return [
                "payload"=>"voyage is not exist !",
                "status"=>"404_2",
                "IsReturnErrorRespone" => true
            ];
        }
        $crane=Crane::find($crane_voyage['crane_id']);
        if(!$crane){
            return [
                "payload"=>"Crane is not exist !",
                "status"=>"404_2",
                "IsReturnErrorRespone" => true
            ];
        }
        $v_crane2=CraneVoyage::select()->where([["voyage_id",$crane_voyage["voyage_id"]],["crane_id",$crane_voyage["crane_id"]]])->first();
        if ($v_crane2) {
            $crane_voyage["id"]=$v_crane2->id;
           return $this->crane_vouyages_validatorAndUpdater($crane_voyage,$action_id);
        }
        $v_crane=CraneVoyage::make($crane_voyage);
        $v_crane->voyage_id=$voyage_id;
        $v_crane->save();
        foreach ($this->craneVoyageColums as $craneVoyageColum){
                if($v_crane[$craneVoyageColum]!="" && $v_crane[$craneVoyageColum]!=null){
                    $this->saveActionDetail($action_id,"crane_voyages","Empty",$v_crane[$craneVoyageColum],$v_crane->crane_id,$craneVoyageColum);
                }
                        
            
            
        }
        return [
            "payload" => $v_crane,
            "status" => "200_2",
            "IsReturnErrorRespone" => false
        ];
    }
    public function other_delays_validatorAndSaver($other_delay,$voyage_id,$action_id){
        $validator = Validator::make($other_delay, [
            "crane_id" => "required|integer",

        ]);
        if ($validator->fails()) {

            return [
                "payload" => $validator->errors(),
                "status" => "406_2_other_delays",
                "IsReturnErrorRespone" => true
            ];
        }
        $voyage=Voyage::find($voyage_id);
        if(!$voyage){
            return [
                "payload"=>"voyage is not exist !",
                "status"=>"404_2",
                "IsReturnErrorRespone" => true
            ];
        }
        $code=Code::find($other_delay['code_id']);
        if(!$code){
            return [
                "payload"=>"code is not exist !",
                "status"=>"404_5",
                "IsReturnErrorRespone" => true
            ];
        }
        $crane=Crane::find($other_delay['crane_id']);
        if(!$crane){
            return [
                "payload"=>"Crane is not exist !",
                "status"=>"404_2",
                "IsReturnErrorRespone" => true
            ];
        }
        $o_delay=OtherDelay::make($other_delay);
        $o_delay->voyage_id=$voyage_id;
        $o_delay->code_id=$other_delay['code_id'];
        $o_delay->save();
        $action=Action::find($action_id);
        if($other_delay['dep_arr']=="dep"){
            $action->actionType="Add Dep Delay";
        } else{
            $action->actionType="Add Arr Delay";
        }
        $action->save();
        foreach ($this->otherDelaysColums as $otherDelaysColum){
            
                if($o_delay[$otherDelaysColum]!="" && $o_delay[$otherDelaysColum]!=null){
                    $this->saveActionDetail($action_id,"other_delays","Empty",$o_delay[$otherDelaysColum],$o_delay->id,$otherDelaysColum);
                }
                        
            
            
        }
        $o_delay->code=$code;
        return [
            "payload" => $o_delay,
            "status" => "200_2",
            "IsReturnErrorRespone" => false
        ];
    }
    public function crane_vouyages_validatorAndUpdater($crane_voyage,$action_id){
        $validator = Validator::make($crane_voyage, [
            "id" => "required",
            "voyage_id" => "required",
            "crane_id" => "required|integer",


        ]);
        if ($validator->fails()) {

            return [
                "payload" => $validator->errors(),
                "status" => "crane_vouyages_406_2_crane_voyage",
                "IsReturnErrorRespone" => true
            ];
        }
        $voyage=Voyage::find($crane_voyage['voyage_id']);
        if(!$voyage){
            return [
                "payload"=>"voyage is not exist !",
                "status"=>"crane_vouyages_404_2",
                "IsReturnErrorRespone" => true
            ];
        }
        $crane=Crane::find($crane_voyage['crane_id']);
        if(!$crane){
            return [
                "payload"=>"Crane is not exist !",
                "status"=>"crane_vouyages_404_2",
                "IsReturnErrorRespone" => true
            ];
        }
        $v_crane=CraneVoyage::find($crane_voyage['id']);
        if (!$v_crane) {
            return [
                "payload"=>"CraneVoyage is not exist !",
                "status"=>"crane_vouyages_404_2",
                "IsReturnErrorRespone" => true
            ];
        }
        $oldV_crane=clone $v_crane;
        $v_crane->crane_id=$crane_voyage['crane_id'];
        $v_crane->voyage_id=$crane_voyage['voyage_id'];
        $v_crane->cbd=$crane_voyage['cbd'];
        $v_crane->dgbohc_bfl_from=$crane_voyage['dgbohc_bfl_from'];
        $v_crane->dgbohc_bfl_to=$crane_voyage['dgbohc_bfl_to'];
        $v_crane->dgbohc_bfl_num_gb=$crane_voyage['dgbohc_bfl_num_gb'];
        $v_crane->dgbohc_bfl_num_hc=$crane_voyage['dgbohc_bfl_num_hc'];
        $v_crane->dss_bfl_from=$crane_voyage['dss_bfl_from'];
        $v_crane->dss_bfl_to=$crane_voyage['dss_bfl_to'];
        $v_crane->dss_bfl_num_sp=$crane_voyage['dss_bfl_num_sp'];
        $v_crane->dss_bfl_fb_dnw=$crane_voyage['dss_bfl_fb_dnw'];
        $v_crane->dss_bfl_fb=$crane_voyage['dss_bfl_fb'];
        $v_crane->ffl=$crane_voyage['ffl'];
        $v_crane->fll=$crane_voyage['fll'];
        $v_crane->sfl=$crane_voyage['sfl'];
        $v_crane->sll=$crane_voyage['sll'];
        $v_crane->tfl=$crane_voyage['tfl'];
        $v_crane->tll=$crane_voyage['tll'];
        $v_crane->fofl=$crane_voyage['fofl'];
        $v_crane->foll=$crane_voyage['foll'];
        $v_crane->fifl=$crane_voyage['fifl'];
        $v_crane->fill=$crane_voyage['fill'];
        $v_crane->sifl=$crane_voyage['sifl'];
        $v_crane->sill=$crane_voyage['sill'];
        $v_crane->sevfl=$crane_voyage['sevfl'];
        $v_crane->sevll=$crane_voyage['sevll'];
        $v_crane->eifl=$crane_voyage['eifl'];
        $v_crane->eill=$crane_voyage['eill'];
        $v_crane->nfl=$crane_voyage['nfl'];
        $v_crane->nll=$crane_voyage['nll'];
        $v_crane->tenfl=$crane_voyage['tenfl'];
        $v_crane->tenll=$crane_voyage['tenll'];
        $v_crane->lgbohc_all_from=$crane_voyage['lgbohc_all_from'];
        $v_crane->lgbohc_all_to=$crane_voyage['lgbohc_all_to'];
        $v_crane->lgbohc_all_num_gb=$crane_voyage['lgbohc_all_num_gb'];
        $v_crane->lgbohc_all_hc=$crane_voyage['lgbohc_all_hc'];
        $v_crane->lgbohc_all_inbay=$crane_voyage['lgbohc_all_inbay'];
        $v_crane->lgbohc_all_inbay_hatch_covers=$crane_voyage['lgbohc_all_inbay_hatch_covers'];

        $v_crane->lss_all_from=$crane_voyage['lss_all_from'];
        $v_crane->lss_all_to=$crane_voyage['lss_all_to'];
        $v_crane->lss_all_num_ss=$crane_voyage['lss_all_num_ss'];
        $v_crane->lss_all_ib_lnw=$crane_voyage['lss_all_ib_lnw'];
        $v_crane->lss_all_ib=$crane_voyage['lss_all_ib'];
        $v_crane->cbu=$crane_voyage['cbu'];
        $v_crane->save();
        foreach ($this->craneVoyageColums as $craneVoyageColum){
            
                if($oldV_crane[$craneVoyageColum]!=$v_crane[$craneVoyageColum]){
                    $this->saveActionDetail($action_id,"crane_voyages",
                    ($oldV_crane[$craneVoyageColum]!="" && $oldV_crane[$craneVoyageColum]!=null) ?$oldV_crane[$craneVoyageColum]:"Empty",
                    ($v_crane[$craneVoyageColum]!="" && $v_crane[$craneVoyageColum]!=null) ?$v_crane[$craneVoyageColum]:"Empty",
                    $v_crane->crane_id,
                    $craneVoyageColum
                    );
                    
                }
                        
            
            
        }
        return [
            "payload" => $v_crane,
            "status" => "crane_vouyages_200_2",
            "IsReturnErrorRespone" => false
        ];
    }
    public function other_delays_validatorAndUpdater($other_delay,$action_id){
        $validator = Validator::make($other_delay, [
            "id" => "required",
            "crane_id" => "required|integer",
            "voyage_id" => "required|integer",



        ]);
        if ($validator->fails()) {

            return [
                "payload" => $validator->errors(),
                "status" => "406_2_other_delays",
                "IsReturnErrorRespone" => true
            ];
        }
        $voyage=Voyage::find($other_delay['voyage_id']);
        if(!$voyage){
            return [
                "payload"=>"voyage is not exist !",
                "status"=>"other_delays_404_2",
                "IsReturnErrorRespone" => true
            ];
        }
        $code=Code::find($other_delay['code_id']);
        if(!$code){
            return [
                "payload"=>"code is not exist !",
                "status"=>"other_delays_404_2",
                "IsReturnErrorRespone" => true
            ];
        }
        $crane=Crane::find($other_delay['crane_id']);
        if(!$crane){
            return [
                "payload"=>"Crane is not exist !",
                "status"=>"other_delays_404_2",
                "IsReturnErrorRespone" => true
            ];
        }
        $o_delay=OtherDelay::find($other_delay['id']);
        if (!$o_delay) {
            return [
                "payload"=>"OtherDelay is not exist !",
                "status"=>"other_delays_404_2",
                "IsReturnErrorRespone" => true
            ];
        }
        $oO_delay=clone $o_delay;
        $o_delay->crane_id=$other_delay['crane_id'];
        $o_delay->voyage_id=$other_delay['voyage_id'];
        $o_delay->code_id=$other_delay['code_id'];
        $o_delay->from=$other_delay['from'];
        $o_delay->to=$other_delay['to'];
        $o_delay->reason=$other_delay['reason'];
        $o_delay->comment=$other_delay['comment'];
        $o_delay->code=$other_delay['code'];
        $o_delay->category=$other_delay['category'];
        $o_delay->dep_arr=$other_delay['dep_arr'];

        $o_delay->save();
        foreach ($this->otherDelaysColums as $otherDelaysColum){
            
                if($oO_delay[$otherDelaysColum]!=$o_delay[$otherDelaysColum]){
                    $this->saveActionDetail($action_id,"other_delays",
                    ($oO_delay[$otherDelaysColum]!="" && $oO_delay[$otherDelaysColum]!=null) ?$oO_delay[$otherDelaysColum]:"Empty",
                    ($o_delay[$otherDelaysColum]!="" && $o_delay[$otherDelaysColum]!=null) ?$o_delay[$otherDelaysColum]:"Empty",
                    $o_delay->id,
                    $otherDelaysColum
                    );
                    
                }
                        
           
            
        }
        $o_delay->code=$code;
        return [
            "payload" => $o_delay,
            "status" => "other_delays_200_2",
            "IsReturnErrorRespone" => false
        ];
    }
    public function CraneVoyageDelete(Request $request){

        $craneVoyage=CraneVoyage::find($request->id);
        if(!$craneVoyage){
            return [
                "payload" => "The searched row does not exist !",
                "status" => "404_4"
            ];
        }
        else {
            $craneVoyage->delete();
            return [
                "payload" => "Deleted successfully",
                "status" => "200_4"
            ];
        }
    }
    public function allVoyages($date){
        $notExistData = array();
        try {
            $store = DB::connection('oracle');
            $data = $store->select("select t.voy_system_key as voy_no,t.ves_name as vessel_name,t.service_name as service,t.voy_eta_ts as eta,t.voy_etd_ts as etd from VOYAGE_M t where trunc(t.voy_eta_ts)=trunc(TO_DATE('".$date."', 'DD-MM-YYYY'))");
            $vessels=Vessel::whereDate('eta', '>=', $date)->select('voy_no')->get();
            $vessels=@json_decode(json_encode($vessels), true);
            for ($i = 0; $i < count($vessels); $i++) {
                $vessels[$i]=$vessels[$i]["voy_no"];
            }
            for ($i = 0; $i < count($data); $i++) {
                if(!in_array($data[$i]->voy_no, $vessels))
                    array_push($notExistData, $data[$i]);
            }
            return $notExistData;

        }catch (GlobalException $e){
            $this->allVoyages($date);
        }
    }
    public function createٍVessel($vessel){

        $validator = Validator::make($vessel, [
            "voy_no" => "required|string",
            "vessel_name" => "required|string",
            "service" => "required|string",

        ]);
        if ($validator->fails()) {
            return [
                "payload" => $validator->errors(),
                "status" => "406_2",
                "IsReturnErrorRespone" => true
            ];
        }
        $_vessel=Vessel::make($vessel);
        $_vessel->save();
        return [
            "payload" => $_vessel,
            "status" => "200_2",
            "IsReturnErrorRespone" => false
        ];;
    }
    public function updateٍVessel($vessel){

        $validator = Validator::make($vessel, [
            "id" => "required",
            "voy_no" => "string|required",
            "vessel_name" => "required|string",
        ]);
        if ($validator->fails()) {
            return [
                "payload" => $validator->errors(),
                "status" => "406_2",
                "IsReturnErrorRespone" => true
            ];


        }
        $_vessel=Vessel::find($vessel["id"]);
        if (!$vessel) {
            return [
                "payload" => "The searched row does not exist !",
                "status" => "404_2",
                "IsReturnErrorRespone" => true
            ];
        }
        $_vessel->voy_no=$vessel["voy_no"];
        $_vessel->vessel_name=$vessel["vessel_name"];
        $_vessel->service=$vessel["service"];
        $_vessel->eta=$vessel["eta"];
        $_vessel->etd=$vessel["etd"];
        $_vessel->save();
        return [
            "payload" => $_vessel,
            "status" => "200",
            "IsReturnErrorRespone" => false
        ];
    }
    public function saveOrUpdateVoyage(Request $request){
        if($request->id==""){
            $validator = Validator::make($request->all(), [
                "vessel"=>"required"
            ]);
            if ($validator->fails()) {
                return [
                    "payload" => $validator->errors(),
                    "status" => "vessel_empty_406_2_voyage"
                ];
            }
            $returnedValue=$this->createٍVessel($request->vessel);
            if($returnedValue['IsReturnErrorRespone']){
                return [
                    "payload" => $returnedValue['payload'],
                    "status" => $returnedValue['status']
                ];
            }
            $vessel=$returnedValue["payload"];
            $voyage=Voyage::make($request->except("vessel_id"));
            $voyage->vessel_id=$vessel["id"];
            $voyage->save();
            $action=new Action;
            $action->vessel_id=$vessel->id;
            $action->utilisateur_id=auth()->user()->id;
            $action->shift=$request->shift;
            $action->actionType="Create";
            $action->save();
            
            foreach ($this->voyageColumsText as $voyageColum){
                
                    if($voyage[$voyageColum]!="" && $voyage[$voyageColum]!=null){
                        $this->saveActionDetail($action->id,"voyages","Empty",$voyage[$voyageColum],$voyage->id,$voyageColum);
                    }
                        
               
                
            }
            foreach ($this->voyageColumsDate as $voyageColum){
              
                
                    if($voyage[$voyageColum]!="" && $voyage[$voyageColum]!=null){
                        $this->saveActionDetail($action->id,"voyages","Empty",$voyage[$voyageColum],$voyage->id,$voyageColum);
                    }
                        
                
                
            }
            foreach ($this->voyageColumsInt as $voyageColum){
              
               
                    if($voyage[$voyageColum]!="" && $voyage[$voyageColum]!=null){
                        $this->saveActionDetail($action->id,"voyages","Empty",$voyage[$voyageColum],$voyage->id,$voyageColum);
                    }
                        
               
                
            }
            foreach ($this->voyageColumsString as $voyageColum){
              
                
                    if($voyage[$voyageColum]!="" && $voyage[$voyageColum]!=null){
                        $this->saveActionDetail($action->id,"voyages","Empty",$voyage[$voyageColum],$voyage->id,$voyageColum);
                    }
                        
                
                
            }
            foreach ($this->voyageColumsBool as $voyageColum){
              
                
                    if($voyage[$voyageColum]!="" && $voyage[$voyageColum]!=null){
                        $this->saveActionDetail($action->id,"voyages","Empty",$voyage[$voyageColum],$voyage->id,$voyageColum);
                    }
                        
               
                
            }
            
            foreach ($request->crane_voyages as $crane_voyage){
                if($crane_voyage["id"]==""){
                
                    $returnedValue=$this->crane_vouyages_validatorAndSaver($crane_voyage,$voyage->id,$action->id);
                    if($returnedValue['IsReturnErrorRespone']){
                        return [
                            "payload" => $returnedValue['payload'],
                            "status" => $returnedValue['status']
                        ];
                    }
               
                }
                else{
                    $returnedValue=$this->crane_vouyages_validatorAndUpdater($crane_voyage,$action->id);
                    if($returnedValue['IsReturnErrorRespone']){
                        return [
                            "payload" => $returnedValue['payload'],
                            "status" => $returnedValue['status']
                        ];
                    }
                }
            }
            foreach ($request->other_delays as $other_delay){
               if($other_delay["id"]==""){
                   $returnedValue=$this->other_delays_validatorAndSaver($other_delay,$voyage->id,$action->id);
                   if($returnedValue['IsReturnErrorRespone']){
                       return [
                           "payload" => $returnedValue['payload'],
                           "status" => $returnedValue['status']
                       ];
                   }
               }
               else{
                   $returnedValue=$this->other_delays_validatorAndUpdater($other_delay,$action->id);
                   if($returnedValue['IsReturnErrorRespone']){
                       return [
                           "payload" => $returnedValue['payload'],
                           "status" => $returnedValue['status']
                       ];
                   }
               }
            }
            $voyage->crane_voyages=$voyage->craneVoyages;
            $voyage->other_delays=$voyage->otherDelays;
            $voyage->vessel=$vessel;
           
            return [
                "payload" => $voyage,
                "status" => "200"
            ];

        }
        else {
            $validator = Validator::make($request->all(), [
                "id" => "required",
            ]);
            if ($validator->fails()) {
                return [
                    "payload" => $validator->errors(),
                    "status" => "406_2_voyage"
                ];
            }
            $voyage=Voyage::find($request->id);
            if (!$voyage) {
                return [
                    "payload" => "The searched voyage does not exist !",
                    "status" => "404_3"
                ];
            }
            $vessel=Vessel::find($request->vessel["id"]);
            if (!$vessel) {
                return [
                    "payload" => "The searched vessel does not exist !",
                    "status" => "404_3"
                ];
            }
            $_vesselReturned=$this->updateٍVessel($request->vessel);
            if($_vesselReturned['IsReturnErrorRespone'])
                return [
                    "payload" => $_vesselReturned['payload'],
                    "status" => $_vesselReturned['status']
                ];
            $oldVoyage=clone $voyage;
            $voyage->vawgd=$request->vawgd;
            $voyage->vawsnrog=$request->vawsnrog;
            $voyage->voyage_number=$request->voyage_number;
            $voyage->dm_y=$request->dm_y;
            $voyage->dm_g=$request->dm_g;
            $voyage->hatch_covers_num=$request->hatch_covers_num;
            $voyage->hatch_covers_moves=$request->hatch_covers_moves;
            $voyage->gear_boxes_num=$request->gear_boxes_num;
            $voyage->gear_boxes_num_40=$request->gear_boxes_num_40;
            $voyage->hatch_covers_num_40=$request->hatch_covers_num_40;
            $voyage->any_hydraulic_couvers=$request->any_hydraulic_couvers;
            $voyage->gear_boxes_moves=$request->gear_boxes_moves;
            $voyage->first_line_datetime=$request->first_line_datetime;
            $voyage->vessel_all_fast=$request->vessel_all_fast;
            $voyage->gangway_secured=$request->gangway_secured;
            $voyage->lashers_onboard=$request->lashers_onboard;
            $voyage->num_mooring_r_fore=$request->num_mooring_r_fore;
            $voyage->num_mooring_r_aft=$request->num_mooring_r_aft;
            $voyage->dwuscfb=$request->dwuscfb;
            $voyage->imo_class=$request->imo_class;
            $voyage->imo_class_ps_onb=$request->imo_class_ps_onb;
            $voyage->last_lift_from=$request->last_lift_from;
            $voyage->last_lift_to=$request->last_lift_to;
            $voyage->last_lift_comment=$request->last_lift_comment;
            $voyage->lf_from=$request->lf_from;
            $voyage->lf_to=$request->lf_to;
            $voyage->lf_comment=$request->lf_comment;
            $voyage->agent_onboard_from=$request->agent_onboard_from;
            $voyage->agent_onboard_to=$request->agent_onboard_to;
            $voyage->agent_onboard_comment=$request->agent_onboard_comment;
            $voyage->safety_net_gangway_from=$request->safety_net_gangway_from;
            $voyage->safety_net_gangway_to=$request->safety_net_gangway_to;
            $voyage->safety_net_gangway_comment=$request->safety_net_gangway_comment;
            $voyage->pilot_onboard_from=$request->pilot_onboard_from;
            $voyage->pilot_onboard_to=$request->pilot_onboard_to;
            $voyage->pilot_onboard_comment=$request->pilot_onboard_comment;
            $voyage->tugs_arrived_from=$request->tugs_arrived_from;
            $voyage->tugs_arrived_to=$request->tugs_arrived_to;
            $voyage->tugs_arrived_comment=$request->tugs_arrived_comment;
            $voyage->unmooring_forward_from=$request->unmooring_forward_from;
            $voyage->unmooring_forward_to=$request->unmooring_forward_to;
            $voyage->unmooring_forward_comment=$request->unmooring_forward_comment;
            $voyage->unmooring_aft_from=$request->unmooring_aft_from;
            $voyage->unmooring_aft_to=$request->unmooring_aft_to;
            $voyage->unmooring_aft_comment=$request->unmooring_aft_comment;
            $voyage->last_line_from=$request->last_line_from;
            $voyage->last_line_to=$request->last_line_to;
            $voyage->last_line_comment=$request->last_line_comment;
            $voyage->pgb_r_co=$request->pgb_r_co;
            $voyage->pgb_r_co_reason=$request->pgb_r_co_reason;
            $voyage->manoeuvre_sequence=$request->manoeuvre_sequence;
            $voyage->tele=$request->tele;
            $voyage->save();
            $action=new Action;
            $action->vessel_id=$vessel->id;
            $action->utilisateur_id=auth()->user()->id;
            $action->shift=$request->shift;
            $action->actionType="Update";
            $action->save();



            
           
            foreach ($this->voyageColumsText as $voyageColum){
                
                if($voyage[$voyageColum]!=$oldVoyage[$voyageColum]){
                    $this->saveActionDetail($action->id,"voyages",
                    ($oldVoyage[$voyageColum]!="" && $oldVoyage[$voyageColum]!=null) ?$oldVoyage[$voyageColum]:"Empty",
                    ($voyage[$voyageColum]!="" && $voyage[$voyageColum]!=null) ?$voyage[$voyageColum]:"Empty",
                    $voyage->id,
                    $voyageColum
                    );
                    
                }
            }
            foreach ($this->voyageColumsDate as $voyageColum){
                
                if($voyage[$voyageColum]!=$oldVoyage[$voyageColum]){
                    $this->saveActionDetail($action->id,"voyages",
                    ($oldVoyage[$voyageColum]!="" && $oldVoyage[$voyageColum]!=null) ?$oldVoyage[$voyageColum]:"Empty",
                    ($voyage[$voyageColum]!="" && $voyage[$voyageColum]!=null) ?$voyage[$voyageColum]:"Empty",
                    $voyage->id,
                    $voyageColum
                    );
                    
                }
            }
            foreach ($this->voyageColumsInt as $voyageColum){
                
                if($voyage[$voyageColum]!=$oldVoyage[$voyageColum]){
                    $this->saveActionDetail($action->id,"voyages",
                    ($oldVoyage[$voyageColum]!="" && $oldVoyage[$voyageColum]!=null) ?$oldVoyage[$voyageColum]:"Empty",
                    ($voyage[$voyageColum]!="" && $voyage[$voyageColum]!=null) ?$voyage[$voyageColum]:"Empty",
                    $voyage->id,
                    $voyageColum
                    );
                    
                }
            }
            foreach ($this->voyageColumsString as $voyageColum){
                
                if($voyage[$voyageColum]!=$oldVoyage[$voyageColum]){
                    $this->saveActionDetail($action->id,"voyages",
                    ($oldVoyage[$voyageColum]!="" && $oldVoyage[$voyageColum]!=null) ?$oldVoyage[$voyageColum]:"Empty",
                    ($voyage[$voyageColum]!="" && $voyage[$voyageColum]!=null) ?$voyage[$voyageColum]:"Empty",
                    $voyage->id,
                    $voyageColum
                    );
                    
                }
            }
            foreach ($this->voyageColumsBool as $voyageColum){
                if($voyage[$voyageColum]!=$oldVoyage[$voyageColum]){
                    $this->saveActionDetail($action->id,"voyages",
                    ($oldVoyage[$voyageColum]!="" && $oldVoyage[$voyageColum]!=null) ?$oldVoyage[$voyageColum]:"Empty",
                    ($voyage[$voyageColum]!="" && $voyage[$voyageColum]!=null) ?$voyage[$voyageColum]:"Empty",
                    $voyage->id,
                    $voyageColum
                    );
                    
                }
            }
            










            foreach ($request->crane_voyages as $crane_voyage){
                if($crane_voyage["id"]==""){
                    $returnedValue=$this->crane_vouyages_validatorAndSaver($crane_voyage,$voyage->id,$action->id);
                    if($returnedValue['IsReturnErrorRespone']){
                        return [
                            "payload" => $returnedValue['payload'],
                            "status" => $returnedValue['status']
                        ];
                    }
                }
                else{
                    $returnedValue=$this->crane_vouyages_validatorAndUpdater($crane_voyage,$action->id);
                    if($returnedValue['IsReturnErrorRespone']){
                        return [
                            "payload" => $returnedValue['payload'],
                            "status" => $returnedValue['status']
                        ];
                    }
                }
            }

            foreach ($request->other_delays as $other_delay){
                if($other_delay["id"]==""){
                    $returnedValue=$this->other_delays_validatorAndSaver($other_delay,$voyage->id,$action->id);
                    if($returnedValue['IsReturnErrorRespone']){
                        return [
                            "payload" => $returnedValue['payload'],
                            "status" => $returnedValue['status']
                        ];
                    }
                }
                else{
                    $returnedValue=$this->other_delays_validatorAndUpdater($other_delay,$action->id);
                    if($returnedValue['IsReturnErrorRespone']){
                        return [
                            "payload" => $returnedValue['payload'],
                            "status" => $returnedValue['status']
                        ];
                    }
                }
            }
            $voyage->vessel=$_vesselReturned['payload'];
            $voyage->crane_voyages=$voyage->craneVoyages;
            $voyage->other_delays=$voyage->otherDelays;
            
            return [
                "payload" => $voyage,
                "status" => "200"
            ];


        }
    }
    public function deleteOtherDelay(Request $request){

        $validator = Validator::make($request->all(), [
            "id" => "required",
        ]);
        if ($validator->fails()) {
            return [
                "payload" => $validator->errors(),
                "status" => "406_2_other_delays"
            ];
        }
        $otherDelay=OtherDelay::find($request->id);
        if(!$otherDelay){
            return [
                "payload" => "The searched row does not exist !",
                "status" => "404_4"
            ];
        }
        else {
            
            $action=new Action;
            $action->vessel_id=$otherDelay->voyage->vessel->id;
            $action->utilisateur_id=auth()->user()->id;
            $action->shift=$request->shift;
            if($otherDelay->dep_arr=="dep"){
                $action->actionType="Delete Dep Delay";
                } else{
                $action->actionType="Delete Arr Delay";
                }
            
            $action->save();
            $this->saveActionDetail($action->id,"other_delays","Empty","Empty",$otherDelay->id,$action->actionType);
            $otherDelay->delete();

            return [
                "payload" => "Deleted successfully",
                "status" => "200"
            ];
        }
    }
    public function getActionHistory($vessel_id){

        $vessel=Vessel::find($vessel_id);
        if(!$vessel){
            return [
                "payload" => "The searched row does not exist !",
                "status" => "404_1"
            ];
        }
        else {
            return [
                "payload" => $vessel->actions(),
                "status" => "200"
            ];

        }
    }
    public function saveActionDetail($action_id,$table_name,$oldvalue,$newvalue,$line_id,$column_name){
        $actionDetails=new ActionDetail;
        try {
            
        $actionDetails->oldvalue=$oldvalue;
        $actionDetails->newvalue=$newvalue;
        $actionDetails->line_id=$line_id;
        $actionDetails->table_name=$table_name;
        $actionDetails->column_name=$column_name;
        $actionDetails->action_id=$action_id;
        $actionDetails->save();
        } catch (GlobalException $e) {
            dd($e);
        }

    }
    public function undoAction(Request $request){

        $validator = Validator::make($request->all(), [
            "id" => "required",
        ]);
        if ($validator->fails()) {
            return [
                "payload" => $validator->errors(),
                "status" => "406_2_undoAction"
            ];
        }
        $actionDetail=ActionDetail::find($request->id);
        if(!$actionDetail){
            return [
                "payload" => "The searched action detail does not exist !",
                "status" => "404_4"
            ];
        }
        else {
            if($actionDetail->table_name=="voyages"){
                $voyage=Voyage::find($actionDetail->line_id);
                if (!$voyage) {
                    return [
                        "payload" => "The searched voyage does not exist !",
                        "status" => "404_3_voyage"
                    ];
                }
                $voyage[$actionDetail->column_name]=$actionDetail->oldvalue;
                $voyage->save();
                $action=new Action;
                $action->vessel_id=$request->vessel_id;
                $action->utilisateur_id=auth()->user()->id;
                $action->shift=$request->shift;
                $action->actionType="UNDO-".$actionDetail->table_name."-".$actionDetail->column_name;
                $action->save();
                $this->saveActionDetail(
                $action->id,
                $actionDetail->table_name,
                $actionDetail->newvalue,
                $actionDetail->oldvalue,
                $actionDetail->line_id,
                $actionDetail->column_name
                );
            }
            else if($actionDetail->table_name=="crane_voyages"){
                $crane_voyage=CraneVoyage::find($actionDetail->line_id);
                if (!$crane_voyage) {
                    return [
                        "payload" => "The searched Crane voyage does not exist !",
                        "status" => "404_3_crane_voyages"
                    ];
                }
                $crane_voyage[$actionDetail->column_name]=$actionDetail->oldvalue;
                $crane_voyage->save();
                $action=new Action;
                $action->vessel_id=$request->vessel_id;
                $action->utilisateur_id=auth()->user()->id;
                $action->shift=$request->shift;
                $action->actionType="UNDO-"+$actionDetail->table_name+"-"+$actionDetail->column_name;
                $action->save();
                $this->saveActionDetail($action->id,
                $actionDetail->table_name,
                $actionDetail->oldvalue,
                $actionDetail->newvalue,
                $actionDetail->line_id,
                $actionDetail->column_name
                );
            }
            else if($actionDetail->table_name=="other_delays"){
                $other_delays=OtherDelay::find($actionDetail->line_id);
                if (!$other_delays) {
                    return [
                        "payload" => "The searched Delay does not exist !",
                        "status" => "404_3_other_delays"
                    ];
                }
                $other_delays[$actionDetail->column_name]=$actionDetail->oldvalue;
                $other_delays->save();
                $action=new Action;
                $action->vessel_id=$request->vessel_id;
                $action->utilisateur_id=auth()->user()->id;
                $action->shift=$request->shift;
                $action->actionType="UNDO-"+$actionDetail->table_name+"-"+$actionDetail->column_name;
                $action->save();
                $this->saveActionDetail($action->id,
                $actionDetail->table_name,
                $actionDetail->oldvalue,
                $actionDetail->newvalue,
                $actionDetail->line_id,
                $actionDetail->column_name
                );
            }
            return [
                "payload" => "undo action successfully",
                "status" => "200"
            ];
        }
    }
    public function undoAnctionFunction(Request $request){
        $validator = Validator::make($request->all(), [
            "id" => "required",
            "shift" => "required",
        ]);
        if ($validator->fails()) {
            return [
                "payload" => $validator->errors(),
                "status" => "406_2_undoAction"
            ];
        }
        $actionBase=Action::find($request->id);
        if(!$actionBase){
            return [
                "payload" => "The searched action does not exist !",
                "status" => "404_4"
            ];
        }
        $actionDetails=$actionBase->actionDetails;
        $action=new Action;
        $action->vessel_id=$actionBase->vessel_id;
        $action->utilisateur_id=auth()->user()->id;
        $action->shift=$request->shift;
        $action->actionType="UNDO ACTION";
        $action->save();
        foreach ($actionDetails as $actionDetail) {
            if($actionDetail->table_name=="voyages"){
                $voyage=Voyage::find($actionDetail->line_id);
                if (!$voyage) {
                    return [
                        "payload" => "The searched voyage does not exist !",
                        "status" => "404_3_voyage"
                    ];
                }
                if($actionDetail->oldvalue!="Empty"){
                    $voyage[$actionDetail->column_name]=$actionDetail->oldvalue;
                }else {
                    $voyage[$actionDetail->column_name]=null;
                }
                $voyage->save();
                $this->saveActionDetail(
                $action->id,
                $actionDetail->table_name,
                $actionDetail->newvalue,
                $actionDetail->oldvalue,
                $actionDetail->line_id,
                $actionDetail->column_name
                );
            }
            else if($actionDetail->table_name=="crane_voyages"){
                 $voyage=Voyage::find($actionBase->vessel_id);
                 
                if (!$voyage) {
                    return [
                        "payload" => "The searched voyage does not exist !",
                        "status" => "404_3_voyage"
                    ];
                }

                $crane_voyages=$voyage->crane_voyages;
                foreach ($crane_voyages as $crane_voyage) {
                    if($crane_voyage->crane_id==$actionDetail->line_id){
                        if($actionDetail->oldvalue!="Empty"){
                            $crane_voyage[$actionDetail->column_name]=$actionDetail->oldvalue;
                        }else {
                            $crane_voyage[$actionDetail->column_name]=$actionDetail->oldvalue;
                        }
                        
                        $crane_voyage->save();
                        $this->saveActionDetail($action->id,
                        $actionDetail->table_name,
                        $actionDetail->oldvalue,
                        $actionDetail->newvalue,
                        $actionDetail->line_id,
                        $actionDetail->column_name
                        );
                    }
                }
                
            }
            else if($actionDetail->table_name=="other_delays"){
                $other_delays=OtherDelay::find($actionDetail->line_id);
                if (!$other_delays) {
                    return [
                        "payload" => "The searched Delay does not exist !",
                        "status" => "404_3_other_delays"
                    ];
                }
                if($actionDetail->oldvalue!="Empty"){
                    $other_delays[$actionDetail->column_name]=$actionDetail->oldvalue;
                }else {
                    $other_delays[$actionDetail->column_name]=$actionDetail->oldvalue;
                }
                
                $other_delays->save();
                $this->saveActionDetail($action->id,
                $actionDetail->table_name,
                $actionDetail->oldvalue,
                $actionDetail->newvalue,
                $actionDetail->line_id,
                $actionDetail->column_name
                );
            }
           
        }
        $payload=[
            "actionType"=>$action->actionType,
            "created_at"=>$action->created_at,
            "shift"=>$action->shift,
            "id"=>$action->id,
            "utilisateur"=>$action->utilisateur,
        ];
        return [
            "payload" => $payload,
            "status" => "200"
        ];
    }
    public function vesselAllActionsDetails(Request $request){
        $vessel=Vessel::find($request->id);
        $actions=[];
        $actionsDetails=[];
        if(!$vessel){
            return [
                "payload" => "The searched row does not exist !",
                "status" => "404_1"
            ];
        }
        else {          
            $actions = DB::table('actions')
            ->where('vessel_id', '=', $vessel->id)
            ->orderBy('created_at', 'asc')
            ->get();
            foreach ($actions as $action) {
                $actionDHolder=$this->actionDetailndex($action->id);
                //dd($actionDHolder);
                if(count($actionDHolder)>0)
                array_push($actionsDetails,...$actionDHolder );
            }
        }
        return [
            "payload" => $actionsDetails,
            "status" => "200"
        ];
    }
    public function actionDetailndex($id){
        
        
        if (!$id) {
            return [
                "payload" => "The searched action does not exist !",
                "status" => "406_2_actionDetailndex"
            ];
        }
        $action=Action::find($id);
        if(!$action){
            return [
                "payload" => "The searched action does not exist !",
                "status" => "404_4"
            ];
        }
       
        return $action->actionDetails;
        
    }
    public function sendShiftReport(){
        $command = 'python shiftReportScript.py';
        exec($command, $out, $status);

        return $out;
    }
}

