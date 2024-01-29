<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Http\Response;
use Illuminate\Support\Facades\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    
    public function index(){
        return view('index');
    }
    // public function index(){
        
    //     $opd_data = DB::table('users')->count();
    //     $sektor = DB::table('tb_sektoral')->count();
    //     $dataset = DB::table('tb_dataset')->where('status', '1')->count();
    //     $infografik = DB::table('tb_infografik')->count();
    //     $high_data = DB::table('tb_highlight')
    //                 ->leftjoin('tb_dataset', 'tb_highlight.id_konten', '=', 'tb_dataset.id')
    //                 ->leftjoin('tb_sektoral', 'tb_dataset.id_sektoral', '=', 'tb_sektoral.id')
    //                 ->select('tb_highlight.*', 'tb_dataset.dataset_konteks as konteks', 'tb_dataset.judul as judul', 'tb_sektoral.logo as gambar')
    //                 ->orderBy('created_at', 'desc')
    //                 ->where('tipe','1')
    //                 ->get();

    //     $high_info = DB::table('tb_highlight')
    //                 ->leftjoin('tb_infografik', 'tb_highlight.id_konten', '=', 'tb_infografik.id')
    //                 ->select('tb_highlight.*', 'tb_infografik.judul_konteks as konteks','tb_infografik.gambar as gambar', 'tb_infografik.judul as judul')
    //                 ->orderBy('created_at', 'desc')
    //                 ->where('tipe','2')
    //                 ->get();

    //     return view('web.index' , [
    //         'opd' => $opd_data,
    //         'datasek' => $sektor,
    //         'dataset' => $dataset,
    //         'infografik' => $infografik,
    //         'high_data' => $high_data,
    //         'high_info' => $high_info
    //     ]);
    // }

    public function dataset(){
        $orga = DB::table('users')->select('id', 'nama')->where('level', '1')->orderBy('nama', 'asc')->get();
        $topik = DB::table('tb_sektoral')->select('id', 'nama_sektoral')->orderBy('nama_sektoral', 'asc')->get();

        $datas = DB::table('tb_dataset')
                ->where('tb_dataset.status', '1')
                ->leftjoin('users', 'tb_dataset.id_opd', '=', 'users.id')
                ->leftjoin('tb_sektoral', 'tb_dataset.id_sektoral', '=', 'tb_sektoral.id')
                ->select('tb_dataset.*', 'users.nama as nama_opd', 'tb_sektoral.nama_sektoral as nama_sektor', 'tb_sektoral.logo as logo_sek', 'users.username as opd_user')
                ->orderBy('updated_at', 'desc')
                ->paginate(10);

        return view('web.dataset', [
            'dataset' => $datas,
            'orga' => $orga,
            'topik' => $topik
        ]);
    }

    
    public function infografik(){
        $topik = DB::table('tb_sektoral')->select('id', 'nama_sektoral')->orderBy('nama_sektoral', 'asc')->get();

        $info = DB::table('tb_infografik')
        ->leftjoin('tb_sektoral', 'tb_infografik.id_sektor', '=', 'tb_sektoral.id')
        ->select('tb_infografik.*', 'tb_sektoral.nama_sektoral as nama_sektor')
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('web.infografik', [
            'info' => $info,
            'topik' => $topik
        ]);
    }

    
    public function infografik_detail(Request $request){
        $plus1 = DB::table('tb_infografik')->where('judul_konteks', $request->id)->first();
        $tambah = $plus1->view + 1;
        $plus = DB::table('tb_infografik')->where('id', $plus1->id)->update([
            'view' => $tambah
        ]);

        $infografik =  DB::table('tb_infografik')
                        ->where('tb_infografik.judul_konteks', $request->id)
                        ->leftjoin('users', 'tb_infografik.id_opd', '=', 'users.id')
                        ->leftjoin('tb_sektoral', 'tb_infografik.id_sektor', '=', 'tb_sektoral.id')
                        ->select('tb_infografik.*', 'users.nama as nama_opd', 'tb_sektoral.nama_sektoral as nama_sektor')
                        ->first();

        return view('web.infografik_detail', [
            'infografik' => $infografik
        ]);
    }
    
    public function organisasi(){
        $orga = DB::table('users')->where('level', '1')->orderBy('nama', 'asc')->get();

        return view('web.organisasi', [
            'opd' => $orga
        ]);
    }

    
    public function organisasi_detail(Request $request){
        // dd($request->id);
        $topik = DB::table('tb_sektoral')->select('id', 'nama_sektoral')->orderBy('nama_sektoral', 'asc')->get();
        $opd = DB::table('users')->where('username', $request->id)->first();
        $opd_dataset =  DB::table('tb_dataset')
                        ->where('tb_dataset.id_opd', $opd->id)
                        ->leftjoin('users', 'tb_dataset.id_opd', '=', 'users.id')
                        ->leftjoin('tb_sektoral', 'tb_dataset.id_sektoral', '=', 'tb_sektoral.id')
                        ->select('tb_dataset.*', 'users.nama as nama_opd', 'tb_sektoral.nama_sektoral as nama_sektor', 'tb_sektoral.logo as logo_sek', 'users.username as opd_user')
                        ->orderBy('updated_at', 'desc')
                        ->where('tb_dataset.status', '1')
                        ->get();

                        $sek_dataset_c = count($opd_dataset);
        // dd($opd->id);
        return view('web.organisasi_detail', [
            'opd' => $opd,
            'hit' => $sek_dataset_c,
            'opd_data' => $opd_dataset,
            'topik' => $topik
        ]);
    }
    
    public function data_sektoral(){
        $sektor = DB::table('tb_sektoral')->orderBy('nama_sektoral', 'asc')->get();

        return view('web.sektoral', [
            'sektoral' => $sektor
        ]);
    }

    
    public function sektoral_detail(Request $request){
        $orga = DB::table('users')->select('id', 'nama')->where('level', '1')->orderBy('nama', 'asc')->get();
        $sek_solo = DB::table('tb_sektoral')->where('nama_sektoral', $request->id)->first();
        $sek_dataset =  DB::table('tb_dataset')
                        ->where('tb_dataset.id_sektoral', $sek_solo->id)
                        ->leftjoin('users', 'tb_dataset.id_opd', '=', 'users.id')
                        ->leftjoin('tb_sektoral', 'tb_dataset.id_sektoral', '=', 'tb_sektoral.id')
                        ->select('tb_dataset.*', 'users.nama as nama_opd', 'tb_sektoral.nama_sektoral as nama_sektor', 'tb_sektoral.logo as logo_sek', 'users.username as opd_user')
                        ->orderBy('updated_at', 'desc')
                        ->where('tb_dataset.status', '1')
                        ->get();

                        $sek_dataset_c = count($sek_dataset);
        // dd($opd->id);
        return view('web.sektoral_detail', [
            'sek_solo' => $sek_solo,
            'sek_dataset' => $sek_dataset,
            'hit' => $sek_dataset_c,
            'orga' => $orga
        ]);
    }

    
    public function dataset_detail(Request $request){
        $plus1 = DB::table('tb_dataset')->where('tb_dataset.dataset_konteks', $request->id)->first();
        $tambah = $plus1->lihat + 1;
        $plus = DB::table('tb_dataset')->where('tb_dataset.dataset_konteks', $request->id)->update([
            'lihat' => $tambah
        ]);

        $dataset =  DB::table('tb_dataset')
                        ->where('tb_dataset.dataset_konteks', $request->id)
                        ->leftjoin('users', 'tb_dataset.id_opd', '=', 'users.id')
                        ->leftjoin('tb_sektoral', 'tb_dataset.id_sektoral', '=', 'tb_sektoral.id')
                        ->select('tb_dataset.*', 'users.nama as nama_opd', 'tb_sektoral.nama_sektoral as nama_sektor', 'tb_sektoral.logo as logo_sek', 'users.username as opd_user', 'users.icon as logo_opd')
                        ->first();

        return view('web.dataset_detail', [
            'dataset' => $dataset
        ]);
    }

    
    public function get_detail(Request $request){
        $info = DB::table('tb_dataset')        
        ->leftjoin('users', 'tb_dataset.id_opd', '=', 'users.id')
        ->leftjoin('tb_sektoral', 'tb_dataset.id_sektoral', '=', 'tb_sektoral.id')
        ->leftjoin('file_dataset', 'tb_dataset.id', '=', 'file_dataset.id_dataset')
        ->select('tb_dataset.*', 'users.nama as nama_opd', 'tb_sektoral.nama_sektoral as nama_sektoral', 'file_dataset.*')
        ->where('tb_dataset.id', '=', $request->id)
        ->get();

        return response()->json($info);
    }
    
    
    public function kill_notif($id, $status){  
        // dd($id, $status);
        DB::table('tb_notifikasi')->where('id', $id)->update([
            'stats' => '1'
        ]);
        
        if($status == '0'){
            return \redirect(route('admin.dataset.baru'));
        }else if($status == '1'){
            return \redirect(route('admin.dataset.terima'));
        }else if($status == '2'){
            return \redirect(route('admin.dataset.tolak'));
        }
    }

    public function kill_notif2($id, $status){  
        // dd($id, $status);
        DB::table('tb_notifikasi')->where('id', $id)->update([
            'stats' => '1'
        ]);
        
        if($status == '0'){
            return \redirect(route('opd.dataset.baru'));
        }else if($status == '1'){
            return \redirect(route('opd.dataset.terima'));
        }else if($status == '2'){
            return \redirect(route('opd.dataset.tolak'));
        }
    }

    
    public function organisasi_cari(Request $request){        
        $data = [];

        if($request->has('q')){
            $search = $request->q;
            $data = DB::table('users')
                    ->select("username","nama")
            		->where('nama','LIKE',"%$search%")
            		->where('level', '1')
            		->get();
        }
        return response()->json($data);
    }

    public function sektoral_cari(Request $request){        
        $data = [];

        if($request->has('q')){
            $search = $request->q;
            $data = DB::table('tb_sektoral')
            		->where('nama_sektoral','LIKE',"%$search%")
            		->get();
        }
        return response()->json($data);
    }

    public function download_file(Request $request){
        $contents = Storage::disk('webdav')->get($request->id);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		return (new Response($contents, 200))
              ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'Content-Disposition: attachment');
    }

    
    public function download_file_cpanel(Request $request){
        $file= public_path(). "/file/".$request->id;

        $headers = array('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        return Response::download($file, $request->id, $headers);
    }
    
}
