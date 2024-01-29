<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use DataTables;
use Session;
use Carbon\Carbon;

class AdminController extends Controller
{    
    public function index(){
       
        $opddata = DB::table('users')->select('nama', 'icon', 'id')->get();
        $opd_data = DB::table('users')->count();
        $sektor = DB::table('tb_sektoral')->count();
        $dataset = DB::table('tb_dataset')->count();
        $infografik = DB::table('tb_infografik')->count();
        $d1 = DB::table('tb_dataset')->where('status', '0')->count();
        $d2 = DB::table('tb_dataset')->where('status', '2')->count();
        $d3 = DB::table('tb_dataset')->where('status', '1')->count();

        return view('admin.index' , [
            'infografik' => $infografik,
            'opddata' => $opddata,
            'opd' => $opd_data,
            'datasek' => $sektor,
            'dataset' => $dataset,
            'd1' => $d1,
            'd2' => $d2,
            'd3' => $d3
        ]);
    }
    
    public function opd(){
       
        return view('admin.opd');
    }

    public function log(){       
        return view('admin.log');
    }

    public function highlight(){       
        return view('admin.highlight');
    }

    public function profil(){ 
        $user = DB::table('users')->where('id', auth()->user()->id)->first();   
        return view('admin.profil', [
            'user' => $user
        ]);
    }

    
    public function infografik(){       
        $sektor = DB::table('tb_sektoral')->get();
        return view('admin.infografik',[
            'topik' => $sektor
        ]);
    }
    
    public function dataset_baru(){
       
        $opd_data = DB::table('users')->select('id','nama')->get();
        $sektor = DB::table('tb_sektoral')->get();
        // $desa = DB::table('tb_desa')->where('camat', '=', '1')->get();

        return view('admin.dataset_baru' , [
            'opd' => $opd_data,
            'sektor' => $sektor
        ]);
    }

    public function dataset_tolak(){
       
        $opd_data = DB::table('users')->select('id','nama')->get();
        $sektor = DB::table('tb_sektoral')->get();
        // $desa = DB::table('tb_desa')->where('camat', '=', '1')->get();

        return view('admin.dataset_tolak' , [
            'opd' => $opd_data,
            'sektor' => $sektor
        ]);
    }

    public function dataset_terima(){
       
        $opd_data = DB::table('users')->select('id','nama')->get();
        $sektor = DB::table('tb_sektoral')->get();
        // $desa = DB::table('tb_desa')->where('camat', '=', '1')->get();

        return view('admin.dataset_terima' , [
            'opd' => $opd_data,
            'sektor' => $sektor
        ]);
    }
    
    // public function dataset_pembaruan(){
       
    //     $opd_data = DB::table('users')->select('id','nama')->get();
    //     $sektor = DB::table('tb_sektoral')->get();
    //     // $desa = DB::table('tb_desa')->where('camat', '=', '1')->get();

    //     return view('admin.dataset_pembaruan' , [
    //         'opd' => $opd_data,
    //         'sektor' => $sektor
    //     ]);
    // }

    public function sektoral(){
       
        return view('admin.sektoral');
    }

    public function akun(){
        return view('admin.akun');
    }
    

    public function get_dataset_baru(){     
        $info = DB::table('tb_dataset')        
        ->leftjoin('users', 'tb_dataset.id_opd', '=', 'users.id')
        ->leftjoin('tb_sektoral', 'tb_dataset.id_sektoral', '=', 'tb_sektoral.id')
        ->select('tb_dataset.*', 'users.nama as nama_opd', 'tb_sektoral.nama_sektoral as nama_sektoral')
        ->where('status', '=', '0')->orwhere('status', '=', '3')
        ->orderBy('created_at', 'DESC')
        ->get();

        return Datatables::of($info)->make();
    }

    public function get_dataset_terima(){     
        $info = DB::table('tb_dataset')        
        ->leftjoin('users', 'tb_dataset.id_opd', '=', 'users.id')
        ->leftjoin('tb_sektoral', 'tb_dataset.id_sektoral', '=', 'tb_sektoral.id')
        ->select('tb_dataset.*', 'users.nama as nama_opd', 'tb_sektoral.nama_sektoral as nama_sektoral')
        ->where('status', '=', '1')
        ->orderBy('updated_at', 'DESC')
        ->get();

        return Datatables::of($info)->make();
    }

    public function get_dataset_tolak(){     
        $info = DB::table('tb_dataset')        
        ->leftjoin('users', 'tb_dataset.id_opd', '=', 'users.id')
        ->leftjoin('tb_sektoral', 'tb_dataset.id_sektoral', '=', 'tb_sektoral.id')
        ->select('tb_dataset.*', 'users.nama as nama_opd', 'tb_sektoral.nama_sektoral as nama_sektoral')
        ->where('status', '=', '2')
        ->orderBy('updated_at', 'DESC')
        ->get();

        return Datatables::of($info)->make();
    }

    public function get_dataset(){     
        $info = DB::table('tb_dataset')        
        ->leftjoin('users', 'tb_dataset.id_opd', '=', 'users.id')
        ->leftjoin('tb_sektoral', 'tb_dataset.id_sektoral', '=', 'tb_sektoral.id')
        ->select('tb_dataset.*', 'users.nama as nama_opd', 'tb_sektoral.nama_sektoral as nama_sektoral')
        // ->where('st', '=', '1')
        ->get();

        return Datatables::of($info)->make();
    }

    public function get_opd(){     
        $info = DB::table('users')->where('level', '1')->get();

        return Datatables::of($info)->make();
    }

    public function get_sektoral(){     
        $info = DB::table('tb_sektoral')->get();

        return Datatables::of($info)->make();
    }

    
    public function get_infografik(){     
        $info = DB::table('tb_infografik')
        ->leftjoin('users', 'tb_infografik.id_opd', '=', 'users.id')
        ->leftjoin('tb_sektoral', 'tb_infografik.id_sektor', '=', 'tb_sektoral.id')
        ->select('tb_infografik.*', 'users.nama as nama_opd', 'tb_sektoral.nama_sektoral as nama_sektoral')
        ->orderBy('created_at', 'desc')
        ->get();

        return Datatables::of($info)->make();
    }
    
    public function get_log(){            
        $info = DB::table('activity_log')      
        ->leftJoin('users', 'activity_log.causer_id', '=', 'users.id')
        ->select('activity_log.description as deskripsi', 'activity_log.created_at as waktu', 'activity_log.properties as keterangan', 'users.nama as nama')
        ->orderBy('waktu', 'desc')
        ->get();
        // dd($info);

        return Datatables::of($info)->make();   
    }   

    
    public function get_highlight1(){            
        $info = DB::table('tb_highlight')
        ->leftjoin('tb_dataset', 'tb_highlight.id_konten', '=', 'tb_dataset.id')
        ->select('tb_highlight.*', 'tb_dataset.dataset_konteks as konteks', 'tb_dataset.judul as judul')
        ->orderBy('created_at', 'desc')
        ->where('tipe','1')
        ->get();
        
        return Datatables::of($info)->make();
    }   
    
    public function get_highlight2(){            
        $info = DB::table('tb_highlight')
        ->leftjoin('tb_infografik', 'tb_highlight.id_konten', '=', 'tb_infografik.id')
        ->select('tb_highlight.*', 'tb_infografik.judul_konteks as konteks', 'tb_infografik.judul as judul')
        ->orderBy('created_at', 'desc')
        ->where('tipe','2')
        ->get();
        
        return Datatables::of($info)->make();
    }   
}
