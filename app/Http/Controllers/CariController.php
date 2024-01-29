<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;

class CariController extends Controller
{

    public function dataset_cari(Request $request){
        // dd($request);
        $orga = DB::table('users')->select('id', 'nama')->where('level', '1')->orderBy('nama', 'asc')->get();
        $topik = DB::table('tb_sektoral')->select('id', 'nama_sektoral')->orderBy('nama_sektoral', 'asc')->get();

        if($request->organisasi != null && $request->topik !=null){
            $datas = DB::table('tb_dataset')
            ->where('tb_dataset.status', '1')
            ->leftjoin('users', 'tb_dataset.id_opd', '=', 'users.id')
            ->leftjoin('tb_sektoral', 'tb_dataset.id_sektoral', '=', 'tb_sektoral.id')
            ->select('tb_dataset.*', 'users.nama as nama_opd', 'tb_sektoral.nama_sektoral as nama_sektor', 'tb_sektoral.logo as logo_sek', 'users.username as opd_user')
            ->orderBy('updated_at', 'desc')
            ->where('tb_dataset.judul', 'like', '%'.$request->search.'%' )
            ->where('tb_dataset.id_opd', $request->organisasi)
            ->where('tb_dataset.id_sektoral', $request->topik)
            ->paginate(10);
        }elseif($request->organisasi != null && $request->topik == null){
            $datas = DB::table('tb_dataset')
            ->where('tb_dataset.status', '1')
            ->leftjoin('users', 'tb_dataset.id_opd', '=', 'users.id')
            ->leftjoin('tb_sektoral', 'tb_dataset.id_sektoral', '=', 'tb_sektoral.id')
            ->select('tb_dataset.*', 'users.nama as nama_opd', 'tb_sektoral.nama_sektoral as nama_sektor', 'tb_sektoral.logo as logo_sek', 'users.username as opd_user')
            ->orderBy('updated_at', 'desc')
            ->where('tb_dataset.judul', 'like', '%'.$request->search.'%' )
            ->where('tb_dataset.id_opd', $request->organisasi)
            ->paginate(10);
        }elseif($request->organisasi == null && $request->topik != null){            
            $datas = DB::table('tb_dataset')
            ->where('tb_dataset.status', '1')
            ->leftjoin('users', 'tb_dataset.id_opd', '=', 'users.id')
            ->leftjoin('tb_sektoral', 'tb_dataset.id_sektoral', '=', 'tb_sektoral.id')
            ->select('tb_dataset.*', 'users.nama as nama_opd', 'tb_sektoral.nama_sektoral as nama_sektor', 'tb_sektoral.logo as logo_sek', 'users.username as opd_user')
            ->orderBy('updated_at', 'desc')
            ->where('tb_dataset.judul', 'like', '%'.$request->search.'%' )
            ->where('tb_dataset.id_sektoral', $request->topik)
            ->paginate(10);
        }else{            
            $datas = DB::table('tb_dataset')
            ->where('tb_dataset.status', '1')
            ->leftjoin('users', 'tb_dataset.id_opd', '=', 'users.id')
            ->leftjoin('tb_sektoral', 'tb_dataset.id_sektoral', '=', 'tb_sektoral.id')
            ->select('tb_dataset.*', 'users.nama as nama_opd', 'tb_sektoral.nama_sektoral as nama_sektor', 'tb_sektoral.logo as logo_sek', 'users.username as opd_user')
            ->orderBy('updated_at', 'desc')
            ->where('tb_dataset.judul', 'like', '%'.$request->search.'%' )
            ->paginate(10);
        }

        // dd('1');
        
        return view('web.dataset', [
            'dataset' => $datas,
            'orga' => $orga,
            'topik' => $topik
        ]);
    }

    
    public function sektoral_cari(Request $request){
        // dd($request);
        $orga = DB::table('users')->select('id', 'nama')->where('level', '1')->orderBy('nama', 'asc')->get();
        $sek_solo = DB::table('tb_sektoral')->where('id', $request->sektor)->first();

        
        if($request->organisasi != null){
            $sek_dataset =  DB::table('tb_dataset')
                ->where('tb_dataset.id_sektoral', $sek_solo->id)
                ->leftjoin('users', 'tb_dataset.id_opd', '=', 'users.id')
                ->leftjoin('tb_sektoral', 'tb_dataset.id_sektoral', '=', 'tb_sektoral.id')
                ->select('tb_dataset.*', 'users.nama as nama_opd', 'tb_sektoral.nama_sektoral as nama_sektor', 'tb_sektoral.logo as logo_sek', 'users.username as opd_user')
                ->orderBy('updated_at', 'desc')
                ->where('tb_dataset.status', '1')
                ->where('tb_dataset.judul', 'like', '%'.$request->search.'%' )
                ->where('tb_dataset.id_opd', $request->organisasi)
                ->get();
        }else{
            $sek_dataset =  DB::table('tb_dataset')
                ->where('tb_dataset.id_sektoral', $sek_solo->id)
                ->leftjoin('users', 'tb_dataset.id_opd', '=', 'users.id')
                ->leftjoin('tb_sektoral', 'tb_dataset.id_sektoral', '=', 'tb_sektoral.id')
                ->select('tb_dataset.*', 'users.nama as nama_opd', 'tb_sektoral.nama_sektoral as nama_sektor', 'tb_sektoral.logo as logo_sek', 'users.username as opd_user')
                ->orderBy('updated_at', 'desc')
                ->where('tb_dataset.status', '1')
                ->where('tb_dataset.judul', 'like', '%'.$request->search.'%' )
                ->get();
        }


        $sek_dataset_c = count($sek_dataset);
        // dd($opd->id);
        return view('web.sektoral_detail', [
            'sek_solo' => $sek_solo,
            'sek_dataset' => $sek_dataset,
            'hit' => $sek_dataset_c,
            'orga' => $orga
        ]);
    }

    
    public function organisasi_cari(Request $request){
        // dd($request, 'asd');
        $topik = DB::table('tb_sektoral')->select('id', 'nama_sektoral')->orderBy('nama_sektoral', 'asc')->get();
        $opd = DB::table('users')->where('id', $request->organisasi)->first();
        
        if($request->topik == null){
            $opd_dataset =  DB::table('tb_dataset')
                        ->where('tb_dataset.id_opd', $opd->id)
                        ->leftjoin('users', 'tb_dataset.id_opd', '=', 'users.id')
                        ->leftjoin('tb_sektoral', 'tb_dataset.id_sektoral', '=', 'tb_sektoral.id')
                        ->select('tb_dataset.*', 'users.nama as nama_opd', 'tb_sektoral.nama_sektoral as nama_sektor', 'tb_sektoral.logo as logo_sek', 'users.username as opd_user')
                        ->orderBy('updated_at', 'desc')
                        ->where('tb_dataset.status', '1')
                        ->where('tb_dataset.judul', 'like', '%'.$request->search.'%' )
                        ->get();                        
        }else{
            $opd_dataset =  DB::table('tb_dataset')
                        ->where('tb_dataset.id_opd', $opd->id)
                        ->leftjoin('users', 'tb_dataset.id_opd', '=', 'users.id')
                        ->leftjoin('tb_sektoral', 'tb_dataset.id_sektoral', '=', 'tb_sektoral.id')
                        ->select('tb_dataset.*', 'users.nama as nama_opd', 'tb_sektoral.nama_sektoral as nama_sektor', 'tb_sektoral.logo as logo_sek', 'users.username as opd_user')
                        ->orderBy('updated_at', 'desc')
                        ->where('tb_dataset.status', '1')
                        ->where('tb_dataset.judul', 'like', '%'.$request->search.'%' )
                        ->where('tb_dataset.id_sektoral', $request->topik )
                        ->get();  
        }
        
        $sek_dataset_c = count($opd_dataset);

        return view('web.organisasi_detail', [
            'opd' => $opd,
            'hit' => $sek_dataset_c,
            'opd_data' => $opd_dataset,
            'topik' => $topik
        ]);
    }
    

    
    public function infografik_cari(Request $request){
        
        $topik = DB::table('tb_sektoral')->select('id', 'nama_sektoral')->orderBy('nama_sektoral', 'asc')->get();

        if($request->topik == null){
            $info = DB::table('tb_infografik')
            ->leftjoin('tb_sektoral', 'tb_infografik.id_sektor', '=', 'tb_sektoral.id')
            ->select('tb_infografik.*', 'tb_sektoral.nama_sektoral as nama_sektor')
            ->where('tb_infografik.judul', 'like', '%'.$request->search.'%' )
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        }else{
            $info = DB::table('tb_infografik')
            ->leftjoin('tb_sektoral', 'tb_infografik.id_sektor', '=', 'tb_sektoral.id')
            ->select('tb_infografik.*', 'tb_sektoral.nama_sektoral as nama_sektor')
            ->where('tb_infografik.judul', 'like', '%'.$request->search.'%' )
            ->where('tb_sektoral.id',  $request->topik )
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        }

        return view('web.infografik', [
            'info' => $info,
            'topik' => $topik
        ]);
    }
}