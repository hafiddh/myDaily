<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use DataTables;
use Session;
use Carbon\Carbon;
use File;

class indexController extends Controller
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
    
    public function index(){
        return view('web.index');
    }
    
    public function dataset_baru(){
        $opd = DB::table('users')->select('id','nama')->where('id', auth()->user()->id)->get();
        $sektor = DB::table('tb_sektoral')->get();
        // $desa = DB::table('tb_desa')->where('camat', '=', '1')->get();

        return view('opd.dataset_baru' , [
            'opd' => $opd,
            'sektor' => $sektor
        ]);
    }
    
    public function dataset_tolak(){
        $opd = DB::table('users')->select('id','nama')->where('id', auth()->user()->id)->get();
        $sektor = DB::table('tb_sektoral')->get();
        // $desa = DB::table('tb_desa')->where('camat', '=', '1')->get();

        return view('opd.dataset_tolak' , [
            'opd' => $opd,
            'sektor' => $sektor
        ]);
    }    
    
    public function dataset_terima(){
        $opd = DB::table('users')->select('id','nama')->where('id', auth()->user()->id)->get();
        $sektor = DB::table('tb_sektoral')->get();
        // $desa = DB::table('tb_desa')->where('camat', '=', '1')->get();

        return view('opd.dataset_terima' , [
            'opd' => $opd,
            'sektor' => $sektor
        ]);
    }    
    
    public function dataset_pembaruan(){
       
        $opd_data = DB::table('users')->select('id','nama')->where('id', auth()->user()->id)->get();
        $sektor = DB::table('tb_sektoral')->get();
        // $desa = DB::table('tb_desa')->where('camat', '=', '1')->get();

        return view('opd.dataset_pembaruan' , [
            'opd' => $opd_data,
            'sektor' => $sektor
        ]);
    }
        
    public function get_dataset(){     
        $info = DB::table('tb_dataset')        
        ->leftjoin('users', 'tb_dataset.id_opd', '=', 'users.id')
        ->leftjoin('tb_sektoral', 'tb_dataset.id_sektoral', '=', 'tb_sektoral.id')
        ->select('tb_dataset.*', 'users.nama as nama_opd', 'tb_sektoral.nama_sektoral as nama_sektoral')
        ->where('tb_dataset.id_opd', '=', auth()->user()->id)
        ->get();

        return Datatables::of($info)->make();
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

    public function new_dataset(Request $request){
        // $x = json_encode($request->sektoral);
        // dd($request);

        $delimiters = ['.', '!', '?', ' ', "'", '/', '|', '\\'];
        $str = strtolower($request->judul);
        $newStr = str_replace($delimiters, $delimiters[0], $str);
        $arr = explode($delimiters[0], $newStr);
        $ex = array_filter($arr);
        $judul_konteks = implode("-",$ex);
        
        DB::table('tb_dataset')->insert([
            'judul' => $request->judul , 
            'dataset_konteks' => $judul_konteks, 
            'deskripsi' => $request->deskripsi,  
            'id_opd' => $request->opd,
            'id_sektoral' => $request->sektoral,  
            'pengukuran' => $request->pengukuran, 
            'cakupan' => $request->cakupan, 
            'frekuensi' => $request->frekuensi, 
            'satuan' => $request->satuan,
            'tahun' => $request->tahun,
            'status' => "0"
        ]);

        $a = DB::select("SELECT LAST_INSERT_ID() as id_last;");

            // Upload_Drive_morowalikab                
            $file = $request->file('file_data');                  
            $extension = $file->getClientOriginalExtension(); 
            $nama_file = $judul_konteks.'_'.auth()->user()->username.'-dataset_'.date('d-m-y').'.'.$extension;
            $ukuran_file = $file->getSize(); 

            $fop = fopen($file, "rb");
            $frd = fread($fop, filesize($file));
            fclose($fop);
                
            $login = 'admin';
            $password = 'Kominfo2020';
            $url = 'https://drive.morowalikab.go.id/remote.php/dav/files/admin/Website_API_Data/OpenData/'.$nama_file;

            $options = array(
                CURLOPT_SAFE_UPLOAD => true,
                CURLOPT_HEADER => true,
                CURLOPT_CUSTOMREQUEST => "PUT",
                CURLOPT_URL => $url,
                CURLOPT_POSTFIELDS => $frd,
                CURLOPT_SSL_VERIFYPEER=> false,
                CURLOPT_RETURNTRANSFER=> 1,
                CURLOPT_HTTPAUTH=>CURLAUTH_BASIC,
                CURLOPT_USERPWD=> $login.':'.$password,
                CURLOPT_HTTPHEADER=>array('OCS-APIRequest: true')
            );

            $curl = curl_init();
            curl_setopt_array($curl, $options);
            $response = curl_exec($curl);
            curl_close($curl);

        $file = $request->file('file_data');
        $extension = $file->getClientOriginalExtension(); 
        $nama_file = $nama_file;
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
 

        DB::table('file_dataset')->insert([
            'id_dataset' => $a[0]->id_last,  
            'nama_file_opd' => $nama_file,
            'json_opd' => $datta
        ]);

        
        $opd = DB::table('users')->select('id','nama')->where('id', auth()->user()->id)->first();
        $isi = $opd->nama.' menambahkan Dataset baru yang berjudul '.$request->judul;
        DB::table('tb_notifikasi')->insert([
            'opd_from' => auth()->user()->id,  
            'opd_to' => '999',
            'tentang' => "Pengajuan Dataset Baru",  
            'isi' => $isi,
            'id_dataset' => $a[0]->id_last,  
            'stats' => '0',
            'status' => '0'
        ]);

        // $image_path = public_path('file/'.$nama_file);
        // if(File::exists($image_path)) {
        //     File::delete($image_path);
        // }

        activity()->withProperties(['ip' => $this->get_client_ip(), 'id_dataset' => $a[0]->id_last])->log('Pengajuan Dataset');
        return \redirect(route('opd.dataset.baru'))->with('success','Dataset Diajukan.');
        
    }

    public function post_edit_dataset_tolak(Request $request){
        
        // dd($request);
        if($request->file_data == null){

            $delimiters = ['.', '!', '?', ' ', "'", '/', '|', '\\'];
            $str = strtolower($request->judul);
            $newStr = str_replace($delimiters, $delimiters[0], $str);
            $arr = explode($delimiters[0], $newStr);
            $ex = array_filter($arr);
            $judul_konteks = implode("-",$ex);
            
            DB::table('tb_dataset')
            ->where('id', $request->id)
            ->update([
                'judul' => $request->judul , 
                'dataset_konteks' => $judul_konteks, 
                'deskripsi' => $request->deskripsi,  
                'id_sektoral' => $request->sektoral,  
                'pengukuran' => $request->pengukuran, 
                'cakupan' => $request->cakupan, 
                'frekuensi' => $request->frekuensi, 
                'satuan' => $request->satuan,
                'tahun' => $request->tahun,
                'status' => "0"
            ]);

            $opd = DB::table('users')->select('id','nama')->where('id', auth()->user()->id)->first();
            $isi = $opd->nama.' menambahkan Dataset baru yang berjudul '.$request->judul;
            DB::table('tb_notifikasi')->insert([
                'opd_from' => auth()->user()->id,  
                'opd_to' => '999',
                'tentang' => "Pengajuan Dataset Baru",  
                'isi' => $isi,
                'id_dataset' =>  $request->id,  
                'stats' => '0',
                'status' => '0'
            ]);

            activity()->withProperties(['ip' => $this->get_client_ip(), 'id_dataset' => $request->id])->log('pengajuan Kembali Dataset');
            return \redirect(route('opd.dataset.baru'))->with('success','Data Berhasil diajukan kembali!');
        
        }else{

            $delimiters = ['.', '!', '?', ' ', "'", '/', '|', '\\'];
            $str = strtolower($request->judul);
            $newStr = str_replace($delimiters, $delimiters[0], $str);
            $arr = explode($delimiters[0], $newStr);
            $ex = array_filter($arr);
            $judul_konteks = implode("-",$ex);
            
            DB::table('tb_dataset')
            ->where('id', $request->id)
            ->update([
                'judul' => $request->judul , 
                'dataset_konteks' => $judul_konteks, 
                'deskripsi' => $request->deskripsi,  
                'id_sektoral' => $request->sektoral,  
                'pengukuran' => $request->pengukuran, 
                'cakupan' => $request->cakupan, 
                'frekuensi' => $request->frekuensi, 
                'satuan' => $request->satuan,
                'tahun' => $request->tahun,
                'status' => "0"
            ]);

            // Upload_Drive_morowalikab                
            $file = $request->file('file_data');                  
            $extension = $file->getClientOriginalExtension(); 
            $nama_file = $judul_konteks.'_'.auth()->user()->username.'-dataset_'.date('d-m-y').'.'.$extension;
            $ukuran_file = $file->getSize(); 

            $fop = fopen($file, "rb");
            $frd = fread($fop, filesize($file));
            fclose($fop);
                
            $login = 'admin';
            $password = 'Kominfo2020';
            $url = 'https://drive.morowalikab.go.id/remote.php/dav/files/admin/Website_API_Data/OpenData/'.$nama_file;

            $options = array(
                CURLOPT_SAFE_UPLOAD => true,
                CURLOPT_HEADER => true,
                CURLOPT_CUSTOMREQUEST => "PUT",
                CURLOPT_URL => $url,
                CURLOPT_POSTFIELDS => $frd,
                CURLOPT_SSL_VERIFYPEER=> false,
                CURLOPT_RETURNTRANSFER=> 1,
                CURLOPT_HTTPAUTH=>CURLAUTH_BASIC,
                CURLOPT_USERPWD=> $login.':'.$password,
                CURLOPT_HTTPHEADER=>array('OCS-APIRequest: true')
            );

            $curl = curl_init();
            curl_setopt_array($curl, $options);
            $response = curl_exec($curl);
            curl_close($curl);

            $file = $request->file('file_data');

            $extension = $file->getClientOriginalExtension(); 
            $nama_file = $nama_file; 
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
    

            // dd($a[0]->id_last);
            DB::table('file_dataset')
            ->where('id_dataset', $request->id)
            ->update([
                'nama_file_opd' => $nama_file,
                'json_opd' => $datta
            ]);

            
            $opd = DB::table('users')->select('id','nama')->where('id', auth()->user()->id)->first();
            $isi = $opd->nama.' menambahkan Dataset baru yang berjudul '.$request->judul;
            DB::table('tb_notifikasi')->insert([
                'opd_from' => auth()->user()->id,  
                'opd_to' => '999',
                'tentang' => "Pengajuan Dataset Baru",  
                'isi' => $isi,
                'id_dataset' => $request->id,  
                'stats' => '0',
                'status' => '0'
            ]);

            // $image_path = public_path('file/'.$nama_file);
            // if(File::exists($image_path)) {
            //     File::delete($image_path);
            // }
            
            activity()->withProperties(['ip' => $this->get_client_ip(), 'id_dataset' => $request->id])->log('Pengajuan Kembali Dataset');
            return \redirect(route('opd.dataset.baru'))->with('success','Dataset Diajukan Kembali.');
        }

    }

    public function post_edit_dataset(Request $request){
        
            $delimiters = ['.', '!', '?', ' ', "'", '/', '|', '\\'];
            $str = strtolower($request->judul);
            $newStr = str_replace($delimiters, $delimiters[0], $str);
            $arr = explode($delimiters[0], $newStr);
            $ex = array_filter($arr);
            $judul_konteks = implode("-",$ex);
            
            DB::table('tb_dataset')
            ->where('id', $request->id)
            ->update([
                'judul' => $request->judul , 
                'dataset_konteks' => $judul_konteks, 
                'deskripsi' => $request->deskripsi,  
                'id_sektoral' => $request->sektoral,  
                'pengukuran' => $request->pengukuran, 
                'cakupan' => $request->cakupan, 
                'frekuensi' => $request->frekuensi, 
                'satuan' => $request->satuan,
                'tahun' => $request->tahun
            ]);

            $opd = DB::table('users')->select('id','nama')->where('id', auth()->user()->id)->first();
            $isi = $opd->nama.' menambahkan Dataset baru yang berjudul '.$request->judul;
            DB::table('tb_notifikasi')->insert([
                'opd_from' => auth()->user()->id,  
                'opd_to' => '999',
                'tentang' => "Pengajuan Dataset Baru",  
                'isi' => $isi,
                'id_dataset' =>  $request->id,  
                'stats' => '0',
                'status' => '0'
            ]);

            activity()->withProperties(['ip' => $this->get_client_ip(), 'id_dataset' => $request->id])->log('Ubah detail dataset');
            return \redirect(route('opd.dataset.baru'))->with('success','Data Berhasil diubah!');
        
    }

    public function del_dataset(Request $request){        
        $lol = DB::table('tb_dataset')->where('id', $request->id)->first();
               DB::table('tb_dataset')->where('id', $request->id)->delete();
               DB::table('file_dataset')->where('id_dataset', $request->id)->delete();
        
        activity()->withProperties(['ip' => $this->get_client_ip(), 'id_dataset' => $request->id, 'judul_dataset' => $lol->judul])->log('Hapus Dataset');
        return response()->json();
    }

    public function edit_dataset(Request $request){           
        $data = DB::table('tb_dataset')->where('tb_dataset.id', $request->id)
        ->leftjoin('file_dataset', 'tb_dataset.id', '=', 'file_dataset.id_dataset')
        ->select('tb_dataset.*', 'file_dataset.*', 'tb_dataset.id as id_dataset')
        ->get();
        return response()->json($data);
    }

    
    public function get_dataset_baru(){     
        $info = DB::table('tb_dataset')        
        ->leftjoin('users', 'tb_dataset.id_opd', '=', 'users.id')
        ->leftjoin('tb_sektoral', 'tb_dataset.id_sektoral', '=', 'tb_sektoral.id')
        ->select('tb_dataset.*', 'users.nama as nama_opd', 'tb_sektoral.nama_sektoral as nama_sektoral')
        ->where('tb_dataset.id_opd', '=', auth()->user()->id)
        ->where('status', '=', '0')->orwhere('status', '=', '3')
        ->orderBy('updated_at', 'desc')
        ->get();

        return Datatables::of($info)->make();
    }
    
    public function get_dataset_tolak(){     
        $info = DB::table('tb_dataset')        
        ->leftjoin('users', 'tb_dataset.id_opd', '=', 'users.id')
        ->leftjoin('tb_sektoral', 'tb_dataset.id_sektoral', '=', 'tb_sektoral.id')
        ->select('tb_dataset.*', 'users.nama as nama_opd', 'tb_sektoral.nama_sektoral as nama_sektoral')
        ->where('tb_dataset.id_opd', '=', auth()->user()->id)
        ->where('tb_dataset.status', '=', '2')
        ->orderBy('updated_at', 'desc')
        ->get();

        return Datatables::of($info)->make();
    }
    
    public function get_dataset_terima(){     
        $info = DB::table('tb_dataset')        
        ->leftjoin('users', 'tb_dataset.id_opd', '=', 'users.id')
        ->leftjoin('tb_sektoral', 'tb_dataset.id_sektoral', '=', 'tb_sektoral.id')
        ->select('tb_dataset.*', 'users.nama as nama_opd', 'tb_sektoral.nama_sektoral as nama_sektoral')
        ->where('tb_dataset.id_opd', '=', auth()->user()->id)
        ->where('tb_dataset.status', '=', '1')
        ->orderBy('updated_at', 'desc')
        ->get();

        return Datatables::of($info)->make();
    }

    
    public function pembaruan_dataset_cari(Request $request){        
        $data = [];

        if($request->has('q')){
            $search = $request->q;
            $data = DB::table('tb_dataset')
                    ->select("id","judul")
            		->where('judul','LIKE',"%$search%")
            		->where('status', '1')
            		->get();
        }
        return response()->json($data);
    }

    
    public function post_pembaruan_dataset(Request $request){
        
        // dd($request);

            $delimiters = ['.', '!', '?', ' ', "'", '/', '|', '\\'];
            $str = strtolower($request->judul);
            $newStr = str_replace($delimiters, $delimiters[0], $str);
            $arr = explode($delimiters[0], $newStr);
            $ex = array_filter($arr);
            $judul_konteks = implode("-",$ex);
            
            DB::table('tb_dataset')
            ->where('id', $request->id)
            ->update([
                'judul' => $request->judul , 
                'dataset_konteks' => $judul_konteks, 
                'deskripsi' => $request->deskripsi,  
                'id_sektoral' => $request->sektoral,  
                'pengukuran' => $request->pengukuran, 
                'cakupan' => $request->cakupan, 
                'frekuensi' => $request->frekuensi, 
                'satuan' => $request->satuan,
                'tahun' => $request->tahun,
                'status' => "3"
            ]);

            // Upload_Drive_morowalikab                
            $file = $request->file('file_data');                  
            $extension = $file->getClientOriginalExtension(); 
            $nama_file = $judul_konteks.'_'.auth()->user()->username.'-dataset_'.date('d-m-y').'.'.$extension;
            $ukuran_file = $file->getSize(); 

            $fop = fopen($file, "rb");
            $frd = fread($fop, filesize($file));
            fclose($fop);
                
            $login = 'admin';
            $password = 'Kominfo2020';
            $url = 'https://drive.morowalikab.go.id/remote.php/dav/files/admin/Website_API_Data/OpenData/'.$nama_file;

            $options = array(
                CURLOPT_SAFE_UPLOAD => true,
                CURLOPT_HEADER => true,
                CURLOPT_CUSTOMREQUEST => "PUT",
                CURLOPT_URL => $url,
                CURLOPT_POSTFIELDS => $frd,
                CURLOPT_SSL_VERIFYPEER=> false,
                CURLOPT_RETURNTRANSFER=> 1,
                CURLOPT_HTTPAUTH=>CURLAUTH_BASIC,
                CURLOPT_USERPWD=> $login.':'.$password,
                CURLOPT_HTTPHEADER=>array('OCS-APIRequest: true')
            );

            $curl = curl_init();
            curl_setopt_array($curl, $options);
            $response = curl_exec($curl);
            curl_close($curl);

            $file = $request->file('file_data');

            $extension = $file->getClientOriginalExtension(); 
            $nama_file = $nama_file; 
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
    

            // dd($a[0]->id_last);
            DB::table('file_dataset')
            ->where('id_dataset', $request->id)
            ->update([
                'nama_file_opd' => $nama_file,
                'json_opd' => $datta
            ]);

            
            $opd = DB::table('users')->select('id','nama')->where('id', auth()->user()->id)->first();
            $isi = $opd->nama.' menambahkan pembaruan dataset yang berjudul '.$request->judul;
            DB::table('tb_notifikasi')->insert([
                'opd_from' => auth()->user()->id,  
                'opd_to' => '999',
                'tentang' => "Pengajuan Pembaruan Dataset",  
                'isi' => $isi,
                'id_dataset' => $request->id,  
                'stats' => '0',
                'status' => '0'
            ]);

            // $image_path = public_path('file/'.$nama_file);
            // if(File::exists($image_path)) {
            //     File::delete($image_path);
            // }
            
            activity()->withProperties(['ip' => $this->get_client_ip(), 'id_dataset' => $request->id])->log('Pengajuan Kembali Dataset');
            return \redirect(route('opd.dataset.pembaruan'))->with('success','Pembaruan Dataset Diajukan.');

    }
}
