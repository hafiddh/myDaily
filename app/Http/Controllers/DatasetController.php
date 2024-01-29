<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use DataTables;
use Session;
use Image;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use File;

class DatasetController extends Controller
{

    
    function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
    
    public function new_dataset(Request $request){
        
        dd($request);

        foreach ($request->sektoral as $selectedOption)
            echo ($selectedOption."\n");


        return \redirect(route('admin.opd'))->with('success','Data Ditambahkan.');
    }

    
    public function post_edit_dataset(Request $request){
        
        // dd($request);
        DB::table('tb_dataset')
        ->where('id', $request->id)
        ->update([
            'judul' => $request->judul , 
            'deskripsi' => $request->deskripsi,  
            'id_sektoral' => $request->sektoral,  
            'pengukuran' => $request->pengukuran, 
            'cakupan' => $request->cakupan, 
            'frekuensi' => $request->frekuensi, 
            'tahun' => $request->tahun, 
            'satuan' => $request->satuan
        ]);
        
        activity()->withProperties(['ip' => $this->get_client_ip(), 'id_dataset' => $request->id])->log('Ubah dataset');
        return \redirect(route('admin.dataset.baru'))->with('success','Data Berhasil diubah!.');
    }

    public function post_tolak_dataset(Request $request){
        
        // dd($request);
        DB::table('tb_dataset')
        ->where('id', $request->id)
        ->update([
            'tolak' => $request->tolak,
            'status' => '2'
        ]);
        
        $dataset = DB::table('tb_dataset')->select('id','judul','id_opd')->where('id', $request->id)->first();
        $isi = ' Dataset berjudul '.$dataset->judul.' ditolak oleh Admin';
        DB::table('tb_notifikasi')->insert([
            'opd_from' => auth()->user()->id,  
            'opd_to' => $dataset->id_opd,
            'tentang' => "Dataset Ditolak",  
            'isi' => $isi,
            'id_dataset' => $dataset->id,  
            'stats' => '0',
            'status' => '2'
        ]);

        activity()->withProperties(['ip' => $this->get_client_ip(), 'id_dataset' => $request->id])->log('Tolak Dataset');
        return \redirect(route('admin.dataset.tolak'))->with('success','Dataset Ditolak!');
    }

    public function post_proses_dataset(Request $request){
        
        // dd($request);
        // dd($request);

        $ds = DB::table('file_dataset')->where('id_dataset', $request->id)->first();
        
        $file = $request->file('file_publish');

        $extension = $file->getClientOriginalExtension(); 
        $nama_file = 'portal-data-morowali_'.$ds->nama_file_opd;
        // dd($nama_file);
        $ukuran_file = $file->getSize();
        $file->move('file/', $nama_file);

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(TRUE);
        $spreadsheet = $reader->load("file/".$nama_file);
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();
        $colsName = array();
        for($col='A'; $col<=$highestColumn; $col++){
            $colsName[] =  $worksheet->getCell($col . 1)->getValue();
        }
        $dataAll = array();
        for($row=2; $row<=$highestRow; $row++){
            $dataRow = array();        
            $i = 0;
            for($col='A'; $col<=$highestColumn; $col++){
                $dataRow += array($colsName[$i] => $worksheet->getCell($col . $row)->getValue());
                $i++;
            }
            $dataAll[] = $dataRow;
            }
            $datta = json_encode($dataAll);
 
        DB::table('tb_dataset')
        ->where('id', $request->id)
        ->update([
            'status' => '1'
        ]);

        DB::table('file_dataset')
        ->where('id_dataset', $request->id)
        ->update([
            'nama_file_admin' => $nama_file,
            'json_admin' => $datta
        ]);
        
        $dataset = DB::table('tb_dataset')->select('id','judul','id_opd')->where('id', $request->id)->first();
        $isi = ' Dataset berjudul '.$dataset->judul.' diterima oleh Admin';
        DB::table('tb_notifikasi')->insert([
            'opd_from' => auth()->user()->id,  
            'opd_to' => $dataset->id_opd,
            'tentang' => "Dataset Diterima",  
            'isi' => $isi,
            'id_dataset' => $dataset->id,  
            'stats' => '0',
            'status' => '1'
        ]);
        
        activity()->withProperties(['ip' => $this->get_client_ip(), 'id_dataset' => $request->id])->log('Publish Dataset');
        return \redirect(route('admin.dataset.terima'))->with('success','Data Berhasil dipublish!');
    }


    public function del_dataset(Request $request){        
        $lol = DB::table('tb_dataset')->where('id', $request->id)->first();
        $lol2 = DB::table('file_dataset')->where('id_dataset', $request->id)->first();

        $image_path = public_path('file/'.$lol2->nama_file_opd);
        if(File::exists($image_path)) {
            File::delete($image_path);
        }
        
        $image_path2 = public_path('file/'.$lol2->nama_file_admin);
        if(File::exists($image_path2)) {
            File::delete($image_path2);
        }

        DB::table('tb_dataset')->where('id', $request->id)->delete();
        DB::table('file_dataset')->where('id_dataset', $request->id)->delete();
        

        activity()->withProperties(['ip' => $this->get_client_ip(), 'id_dataset' => $request->id, 'judul_dataset' => $lol->judul])->log('Hapus Dataset');
        return response()->json();
    }

    public function edit_dataset(Request $request){           
        $data = DB::table('tb_dataset')->where('id', $request->id)->get();
        return response()->json($data);
    }
    
    public function detail_dataset(Request $request){             
        $info = DB::table('tb_dataset')        
        ->leftjoin('users', 'tb_dataset.id_opd', '=', 'users.id')
        ->leftjoin('tb_sektoral', 'tb_dataset.id_sektoral', '=', 'tb_sektoral.id')
        ->leftjoin('file_dataset', 'tb_dataset.id', '=', 'file_dataset.id_dataset')
        ->select('tb_dataset.*', 'users.nama as nama_opd', 'tb_sektoral.nama_sektoral as nama_sektoral', 'file_dataset.*')
        ->where('tb_dataset.id', '=', $request->id)
        ->get();

        return response()->json($info);
    }

    
    public function dataset_teruskan(Request $request){
        $id = $request->id;

        $ds = DB::table('file_dataset')->where('id_dataset', $id)->first();

        DB::table('file_dataset')
        ->where('id_dataset', $id)
        ->update([
            'nama_file_admin' => $ds->nama_file_opd,
            'json_admin' => $ds->json_opd
        ]);

        DB::table('tb_dataset')
        ->where('id', $id)
        ->update([
            'status' => '1'
        ]);
        
        $dataset = DB::table('tb_dataset')->select('id','judul','id_opd')->where('id', $id)->first();
        $isi = ' Dataset berjudul '.$dataset->judul.' diterima oleh Admin';
        DB::table('tb_notifikasi')->insert([
            'opd_from' => auth()->user()->id,  
            'opd_to' => $dataset->id_opd,
            'tentang' => "Dataset Diterima",  
            'isi' => $isi,
            'id_dataset' => $dataset->id,  
            'stats' => '0',
            'status' => '1'
        ]);

        activity()->withProperties(['ip' => $this->get_client_ip(), 'id_dataset' => $id])->log('Publish Dataset');
        return response()->json();
    }
}