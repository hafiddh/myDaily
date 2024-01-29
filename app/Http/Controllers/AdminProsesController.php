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

class AdminProsesController extends Controller
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
    
    public function tabel(Request $request){
        
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(TRUE);
        
        // setting nama file yg akan dibaca
        $spreadsheet = $reader->load("test.xlsx");
        
        // data worksheet yg akan dibaca ada di active sheet
        $worksheet = $spreadsheet->getActiveSheet();
        
        // mendapatkan maks nomor baris data
        $highestRow = $worksheet->getHighestRow();
        // mendapatkan maks kolom data
        $highestColumn = $worksheet->getHighestColumn();
        
        // mendapatkan nama-nama kolom data 
        // membaca value yang ada di cell: A1, B1, ..., E1
        // dan simpan ke dalam array $colsName
        $colsName = array();
        for($col='A'; $col<=$highestColumn; $col++){
            $colsName[] =  $worksheet->getCell($col . 1)->getValue();
        }
        
        // inisialisasi array untuk menampung semua data
        $dataAll = array();
        
        // proses membaca data baris-perbaris 
        // dimulai dari baris ke-2, karena baris ke-1 berisi nama kolom tabel
        
        for($row=2; $row<=$highestRow; $row++){
            // inisialisasi array untuk data perbaris
            $dataRow = array();
        
            $i = 0;
            // untuk setiap baris data, baca value tiap kolom cell
                // misal untuk baris ke-2, cell yang dibaca: A2, B2, ..., E2
                // misal untuk baris ke-3, cell yang dibaca: A3, B3, ..., E3
                // dst ...
            for($col='A'; $col<=$highestColumn; $col++){
                // setiap value digabung menjadi satu
                // dan tambahkan ke array $dataRow
                $dataRow += array($colsName[$i] => $worksheet->getCell($col . $row)->getValue());
                $i++;
            }
            // setelah didapat data array perbaris
            // tambahkan ke $dataAll 
            $dataAll[] = $dataRow;
            }
        
        // convert ke json lalu tampilkan
            $datta = json_encode($dataAll);
 

        return view('admin.tabel', [ 'data'=>$datta ]);
        }

        
    public function new_opd(Request $request){
        
        // dd($request);

		    $file = $request->file('logo');

            if($file == null){
                $nama_file = "logo_default.png";
            }else{
                $extension = $file->getClientOriginalExtension(); 
                $nama_file = 'opd_'.date('YmdHis').'.'.$extension;   
                $ukuran_file = $file->getSize();
                // $img->save('uploads',$nama_file);
        
                $thumbImage = Image::make($file->getRealPath());
                $thumbImage->resize(1000, 1000, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $thumbPath = 'admin_assets/images/logo/' . $nama_file;
                $thumbImage = Image::make($thumbImage)->save($thumbPath);
            }                  
            
            $pass = bcrypt($request->password);

        DB::table('users')->insert([
            'nama' => $request->nama , 
            'level' => '1',  
            'deskripsi' => $request->deskripsi,
            'icon' => $nama_file,  
            'username' => $request->username, 
            'password' => $pass           
        ]);

        activity()->withProperties(['ip' => $this->get_client_ip(), 'nama_organisasi' => $request->nama])->log('Tambah Organisasi');
        return \redirect(route('admin.opd'))->with('success','Data Ditambahkan.');
    }
    
    public function del_opd(Request $request){        
        DB::table('users')->where('id', $request->id)->delete();
        activity()->withProperties(['ip' => $this->get_client_ip(), 'id_organisasi' => $request->id])->log('Hapus Organisasi');
        return response()->json();
    }

    
    public function edit_opd(Request $request){           
        $data = DB::table('users')->where('id', $request->id)->get();
        return response()->json($data);
    }

    public function post_edit_opd(Request $request){
        
        // dd($request);

        $file = $request->file('logo');

            if($file == null){
                $nama_file = "logo_default.png";
            }else{
                $extension = $file->getClientOriginalExtension(); 
                $nama_file = 'opd_'.date('YmdHis').'.'.$extension;   
                $ukuran_file = $file->getSize();
                // $img->save('uploads',$nama_file);
        
                $thumbImage = Image::make($file->getRealPath());
                $thumbImage->resize(1000, 1000, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $thumbPath = 'admin_assets/images/logo/' . $nama_file;
                $thumbImage = Image::make($thumbImage)->save($thumbPath);
            }

        if($request->password == null){            
            $up = DB::table('users')
            ->where('id', $request->id)
            ->update([
                'nama' => $request->nama,
                'username' => $request->username,
                'deskripsi' => $request->deskripsi,
                'icon' => $nama_file,
                'username' => $request->username,
            ]);
            
            activity()->withProperties(['ip' => $this->get_client_ip(), 'id_organisasi' => $request->id])->log('Edit Organisasi');
            return \redirect(route('admin.opd'))->with('success','Data Berhasil diubah!.');
        }else{
            $x = bcrypt($request->password);
            $up = DB::table('users')
            ->where('id', $request->id)
            ->update([
                'nama' => $request->nama,
                'username' => $request->username,
                'deskripsi' => $request->deskripsi,
                'icon' => $nama_file,
                'username' => $request->username,
                'password' => $x
            ]);
            
            activity()->withProperties(['ip' => $this->get_client_ip(), 'id_organisasi' => $request->id])->log('Edit Organisasi');
            return \redirect(route('admin.opd'))->with('success','Data Berhasil diubah!.');
        }

        return \redirect(route('admin.opd'))->with('error','Data gagal diubah!.');
    }

    public function post_edit_profil(Request $request){
        
        // dd($request);

        $file = $request->file('logo');

            if($file == null){
                $nama_file = $request->logo_old;
            }else{
                $extension = $file->getClientOriginalExtension(); 
                $nama_file = 'opd_'.date('YmdHis').'.'.$extension;   
                $ukuran_file = $file->getSize();
                // $img->save('uploads',$nama_file);
        
                $thumbImage = Image::make($file->getRealPath());
                $thumbImage->resize(1000, 1000, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $thumbPath = 'admin_assets/images/logo/' . $nama_file;
                $thumbImage = Image::make($thumbImage)->save($thumbPath);
            }

        if($request->password == null){            
            $up = DB::table('users')
            ->where('id', $request->id)
            ->update([
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
                'icon' => $nama_file,
            ]);
            
            activity()->withProperties(['ip' => $this->get_client_ip()])->log('Ubah Profil');
            return \redirect(route('admin.profil'))->with('success','Data Berhasil diubah!.');
        }else{
            $x = bcrypt($request->password);
            $up = DB::table('users')
            ->where('id', $request->id)
            ->update([
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
                'icon' => $nama_file,
                'password' => $x
            ]);
            
            activity()->withProperties(['ip' => $this->get_client_ip()])->log('Ubah Profil');
            return \redirect(route('admin.profil'))->with('success','Data Berhasil diubah!.');
        }

        return \redirect(route('admin.profil'))->with('error','Data gagal diubah!.');
    }

    public function new_sektoral(Request $request){
        
        // dd($request);

		    $file = $request->file('logo');

            if($file == null){
                $nama_file = $request->logo_old;
            }else{
                $extension = $file->getClientOriginalExtension(); 
                $nama_file = 'sektoral_'.date('YmdHis').'.'.$extension;   
                $ukuran_file = $file->getSize();
                // $img->save('uploads',$nama_file);
        
                $thumbImage = Image::make($file->getRealPath());
                $thumbImage->resize(1000, 1000, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $thumbPath = 'admin_assets/images/logo/' . $nama_file;
                $thumbImage = Image::make($thumbImage)->save($thumbPath);
            }                  
            
            $pass = bcrypt($request->password);

        DB::table('tb_sektoral')->insert([
            'nama_sektoral' => $request->nama ,
            'logo' => $nama_file      
        ]);

        activity()->withProperties(['ip' => $this->get_client_ip(), 'nama_sektoral' => $request->nama])->log('Tambah Data Sektoral');
        return \redirect(route('admin.sektoral'))->with('success','Data Ditambahkan.');
    }
    
    public function del_sektoral(Request $request){        
        DB::table('tb_sektoral')->where('id', $request->id)->delete();
        activity()->withProperties(['ip' => $this->get_client_ip(), 'id_sektoral' => $request->id])->log('Hapus Data Sektoral');
        return response()->json();
    }

    
    public function edit_sektoral(Request $request){           
        $data = DB::table('tb_sektoral')->where('id', $request->id)->get();
        return response()->json($data);
    }

    public function post_edit_sektoral(Request $request){
        
        // dd($request);

        $file = $request->file('logo');

            if($file == null){
                $nama_file = $request->logo_old;
            }else{
                $extension = $file->getClientOriginalExtension(); 
                $nama_file = 'sektoral_'.date('YmdHis').'.'.$extension;   
                $ukuran_file = $file->getSize();
                // $img->save('uploads',$nama_file);
        
                $thumbImage = Image::make($file->getRealPath());
                $thumbImage->resize(1000, 1000, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $thumbPath = 'admin_assets/images/logo/' . $nama_file;
                $thumbImage = Image::make($thumbImage)->save($thumbPath);
            }

            $up = DB::table('tb_sektoral')
            ->where('id', $request->id)
            ->update([
                'nama_sektoral' => $request->nama,
                'logo' => $nama_file
            ]);
            
            activity()->withProperties(['ip' => $this->get_client_ip(), 'id_sektoral' => $request->id])->log('Ubah Data Sektoral');
            return \redirect(route('admin.sektoral'))->with('success','Data Berhasil diubah!.');
    }


    public function new_infografik(Request $request){
        // dd($request);
        $delimiters = ['.', '!', '?', ' ', "'", '/', '|', '\\'];
        $str = strtolower($request->judul);
        $newStr = str_replace($delimiters, $delimiters[0], $str);
        $arr = explode($delimiters[0], $newStr);
        $ex = array_filter($arr);
        $judul_konteks = implode("-",$ex);
        
        $file = $request->file('gambar');
        $extension = $file->getClientOriginalExtension(); 
        $nama_file = 'infografik_'.date('YmdHis').'.'.$extension;   
        $ukuran_file = $file->getSize();
        // $img->save('uploads',$nama_file);

        $thumbImage = Image::make($file->getRealPath());
        $thumbImage->resize(2000, 2000, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $thumbPath = 'admin_assets/images/infografik/' . $nama_file;
        $thumbImage = Image::make($thumbImage)->save($thumbPath);
        // dd($nama_file);
            

        DB::table('tb_infografik')->insert([
            'judul_konteks' => $judul_konteks , 
            'judul' => $request->judul,  
            'gambar' => $nama_file,   
            'sumber' => $request->sumber,  
            'isi' => $request->isi,  
            'id_opd' => auth()->user()->id,
            'id_sektor' => $request->sektor,
            'view' => '0',          
        ]);

        activity()->withProperties(['ip' => $this->get_client_ip(), 'judul_infografik' => $request->judul])->log('Tambah Infografik');
        return \redirect(route('admin.infografik'))->with('success','Data Ditambahkan.');
    }

    public function lihat_infografik(Request $request){           
        $data = DB::table('tb_infografik')
            ->where('tb_infografik.id', $request->id)
            ->leftjoin('tb_sektoral', 'tb_infografik.id_sektor', '=', 'tb_sektoral.id')
            ->select('tb_infografik.*', 'tb_sektoral.nama_sektoral as nama_sektoral')
            ->first();
        return response()->json($data);
    }

    public function edit_infografik(Request $request){           
        $data = DB::table('tb_infografik')->where('id', $request->id)->get();
        return response()->json($data);
    }

    public function post_edit_infografik(Request $request){
        // dd($request);
        $delimiters = ['.', '!', '?', ' ', "'", '/', '|', '\\'];
        $str = strtolower($request->judul);
        $newStr = str_replace($delimiters, $delimiters[0], $str);
        $arr = explode($delimiters[0], $newStr);
        $ex = array_filter($arr);
        $judul_konteks = implode("-",$ex);

        $file = $request->file('gambar');

            if($file == null){
                $nama_file = $request->gambar_old;
            }else{
                $extension = $file->getClientOriginalExtension(); 
                $nama_file = 'infografik_'.date('YmdHis').'.'.$extension;   
                $ukuran_file = $file->getSize();
                // $img->save('uploads',$nama_file);

                $thumbImage = Image::make($file->getRealPath());
                $thumbImage->resize(2000, 2000, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $thumbPath = 'admin_assets/images/infografik/' . $nama_file;
                $thumbImage = Image::make($thumbImage)->save($thumbPath);
            }

            DB::table('tb_infografik')
                ->where('id', $request->id)
                ->update([
                'judul_konteks' => $judul_konteks, 
                'judul' => $request->judul,  
                'gambar' => $nama_file,   
                'sumber' => $request->sumber,  
                'isi' => $request->isi,  
                'id_opd' => auth()->user()->id,
                'id_sektor' => $request->sektor,
                'view' => '0',          
            ]);
            
            activity()->withProperties(['ip' => $this->get_client_ip(), 'id_infografik' => $request->id])->log('Ubah Data Sektoral');
            return \redirect(route('admin.infografik'))->with('success','Data Berhasil diubah!.');
    }
    
    public function del_infografik(Request $request){        
        DB::table('tb_infografik')->where('id', $request->id)->delete();
        activity()->withProperties(['ip' => $this->get_client_ip(), 'id_infografik' => $request->id])->log('Hapus Organisasi');
        return response()->json();
    }
    
    public function del_highlight(Request $request){        
        DB::table('tb_highlight')->where('id', $request->id)->delete();
        activity()->withProperties(['ip' => $this->get_client_ip(), 'id_highlight' => $request->id])->log('Hapus Highlight');
        return response()->json();
    }

      
    public function tambah_highlight1(Request $request){

        DB::table('tb_highlight')->insert([
            'id_konten' => $request->id_data ,  
            'tipe' => '1'       
        ]);

        activity()->withProperties(['ip' => $this->get_client_ip(), 'id_konten' => $request->id_data])->log('Tambah Highlight');
        return \redirect(route('admin.highlight'))->with('success','Highlight Diperbaharui.');
    }

    
    public function tambah_highlight2(Request $request){

        DB::table('tb_highlight')->insert([
            'id_konten' => $request->id_data,  
            'tipe' => '2'       
        ]);

        activity()->withProperties(['ip' => $this->get_client_ip(), 'id_konten' => $request->id_data])->log('Tambah Highlight');
        return \redirect(route('admin.highlight'))->with('success','Highlight Diperbaharui.');
    }
    
    public function high_dataset_cari(Request $request){        
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

    public function high_info_cari(Request $request){        
        $data = [];

        if($request->has('q')){
            $search = $request->q;
            $data = DB::table('tb_infografik')
                    ->select("id","judul")
            		->where('judul','LIKE',"%$search%")
            		->get();
        }
        return response()->json($data);
    }
    
}
