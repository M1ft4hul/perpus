<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Buku extends MY_Controller
{


	public function __construct()
	{
		parent::__construct();

		$this->load->model('Buku_model');
		$this->load->helper(array('form', 'url'));
		$this->load->library('session');
	}

	// script code php dibawah ini untuk flash data

	// public function setflash($pesan, $aksi, $tipe) {
	// 	$_SESSION['flash'] = [
	// 		'pesan' => $pesan,
	// 		'aksi' => $aksi,
	// 		'tipe' => $tipe
	// 	];
	// }

	// public function flash(){
	// 	if (isset($_SESSION['flash']) ) {
	// 		echo '<div class="alert alert-' . $_SESSION['flash']['tipe'] . ' alert-dismissible fade show" role="alert">
	// 		Data<strong>' . $_SESSION['flash']['pesan'] . '</strong>
	// 		 ' . $_SESSION['flash']['aksi'] . '
	// 		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
	// 		  <span aria-hidden="true">&times;</span>
	// 		</button>
	// 	  </div>';
	// 	  unset($_SESSION['flash']);
	// 	}
	// }

	//menampilkan daftar buku
	public function buku()
	{
		$data['log'] = $this->db->get_where('tb_petugas', array('id_petugas' => $this->session->userdata('username')))->result();
		$cek = $this->session->userdata('logged_in');
		$stts = $this->session->userdata('stts');
		/*jika status login Yes dan status admin tampilkan*/
		if (!empty($cek) && $stts == 'admin') {
			/*layout*/
			$data['title'] = 'Daftar buku';
			$data['pointer'] = "buku/buku";
			$data['classicon'] = "fa fa-book";
			$data['main_bread'] = "Data Buku";
			$data['sub_bread'] = "Daftar Buku";
			$data['desc'] = "Data Master Buku, Menampilkan data Buku Perpustakaan";

			/*data yang ditampilkan*/
			$data['data_buku'] = $this->Buku_model->getAllData("tb_buku");
			$data['data_kategori'] = $this->Buku_model->getAllData("tb_kategori");
			$data['data_penerbit'] = $this->Buku_model->getAllData("tb_penerbit");
			$data['data_pengarang'] = $this->Buku_model->getAllData("tb_pengarang");
			$data['data_rak'] = $this->Buku_model->getAllData("tb_rak");
			$data['model'] = $this->Buku_model;
			/*masukan data kedalam view */
			//$data['js']=$this->load->view('admin/buku/js');
			$tmp['content'] = $this->load->view('admin/buku/R_buku', $data, TRUE);
			$this->load->view('admin/layout', $tmp);
		} else
		/*jika status login NO atau status bukan admin kembalikan ke login*/ {
			$this->load->view("login/auth");
		}
	}

	//hapus buku
	public function hapus_buku()
	{
		$data['log'] = $this->db->get_where('tb_petugas', array('id_petugas' => $this->session->userdata('username')))->result();
		$cek = $this->session->userdata('logged_in');
		$stts = $this->session->userdata('stts');
		if (!empty($cek) && $stts == 'admin') {
			$id_buku = $this->input->get('id_buku', TRUE);
			$hapus = array('id_buku' => $id_buku);

			$this->Buku_model->deleteData('tb_buku', $hapus);
			header('location:' . base_url() . 'admin/Buku/buku');
		} else {
			header('location:' . base_url() . 'login/auth');
		}
	}

	//tambah buku
	public function tambah_buku()
	{
		$data['log'] = $this->db->get_where('tb_petugas', array('id_petugas' => $this->session->userdata('username')))->result();
		$cek = $this->session->userdata('logged_in');
		$stts = $this->session->userdata('stts');
		if (!empty($cek) && $stts == 'admin') {

			$this->form_validation->set_rules('id_buku', 'id_buku', 'required');
			$this->form_validation->set_rules('judul', 'judul', 'required');
			$this->form_validation->set_rules('kategori', 'kategori', 'required');
			$this->form_validation->set_rules('penerbit', 'penerbit', 'required');
			$this->form_validation->set_rules('pengarang', 'pengarang', 'required');
			$this->form_validation->set_rules('no_rak', 'no_rak', 'required');
			if ($this->form_validation->run() === FALSE) {
				/*layout*/
				$data['title'] = 'Tambah Buku';
				$data['pointer'] = "buku/buku";
				$data['classicon'] = "fa fa-book";
				$data['main_bread'] = "Daftar Buku";
				$data['sub_bread'] = "Tambah Buku";
				$data['desc'] = "Form menambahkan data buku Perpustakaan";

				/*data yang ditampilkan*/
				$data['data_kategori'] = $this->Buku_model->getAllData("tb_kategori");
				$data['data_penerbit'] = $this->Buku_model->getAllData("tb_penerbit");
				$data['data_pengarang'] = $this->Buku_model->getAllData("tb_pengarang");
				$data['data_rak'] = $this->Buku_model->getAllData("tb_rak");
				/*masukan data kedalam view */
				$tmp['content'] = $this->load->view('admin/buku/C_buku', $data, TRUE);
				$this->load->view('admin/layout', $tmp);
			} else {
				$gambar = $_FILES['foto']['name'];
				if (empty($gambar)) {
					$this->db->where('id_buku', $this->input->post('id_buku'));
					$isi = $this->db->count_all_results('tb_buku');
					if ($isi == 0) {
						$data = array(
							'id_buku' => $this->input->post('id_buku'),
							'ISBN' => $this->input->post('ISBN'),
							'judul' => $this->input->post('judul'),
							'id_kategori' => $this->input->post('kategori'),
							'id_penerbit' => $this->input->post('penerbit'),
							'id_pengarang' => $this->input->post('pengarang'),
							'no_rak' => $this->input->post('no_rak'),
							'thn_terbit' => $this->input->post('thn_terbit'),
							'ket' => $this->input->post('keterangan'),
							'img' => '',
						);

						$this->Buku_model->insertData('tb_buku', $data);
						redirect('admin/Buku/buku');
					} else {
						/*layout*/
						$data['title'] = 'Tambah Buku';
						$data['pointer'] = "buku/buku";
						$data['classicon'] = "fa fa-book";
						$data['main_bread'] = "Daftar Buku";
						$data['sub_bread'] = "Tambah Buku";
						$data['desc'] = "Form menambahkan data buku Perpustakaan";

						/*data yang ditampilkan*/
						$data['data_kategori'] = $this->Buku_model->getAllData("tb_kategori");
						$data['data_penerbit'] = $this->Buku_model->getAllData("tb_penerbit");
						$data['data_pengarang'] = $this->Buku_model->getAllData("tb_pengarang");
						$data['data_rak'] = $this->Buku_model->getAllData("tb_rak");
						/*masukan data kedalam view */
						$tmp['content'] = $this->load->view('admin/buku/C_buku', $data, TRUE);
						$this->load->view('admin/layout', $tmp);
					}
				} else {
					$alphanum	= "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
					$nama		= str_shuffle($alphanum); //random nama dengan alphanum
					$config['file_name']	= $nama;
					$config['upload_path']	= "gambar_petugas"; //lokasi penyimpanan file
					$config['allowed_types'] = 'gif|jpg|JPEG|png|JPEG|BMP|bmp'; // format foto yang diizinkan
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if (!$this->upload->do_upload('foto')) {
						$data['pesan']      = $this->upload->display_errors();
						$data['title'] = 'Tambah Buku';
						$data['pointer'] = "buku/buku";
						$data['classicon'] = "fa fa-book";
						$data['main_bread'] = "Daftar Buku";
						$data['sub_bread'] = "Tambah Buku";
						$data['desc'] = "Form menambahkan data buku Perpustakaan";

						/*data yang ditampilkan*/
						$data['data_kategori'] = $this->Buku_model->getAllData("tb_kategori");
						$data['data_penerbit'] = $this->Buku_model->getAllData("tb_penerbit");
						$data['data_pengarang'] = $this->Buku_model->getAllData("tb_pengarang");
						$data['data_rak'] = $this->Buku_model->getAllData("tb_rak");
						/*masukan data kedalam view */
						$tmp['content'] = $this->load->view('admin/buku/C_buku', $data, TRUE);
						$this->load->view('admin/layout', $tmp);
					} else {
						$this->db->where('id_buku', $this->input->post('id_buku'));
						$isi = $this->db->count_all_results('tb_buku');
						if ($isi == 0) {
							$data = array(
								'id_buku' => $this->input->post('id_buku'),
								'ISBN' => $this->input->post('ISBN'),
								'judul' => $this->input->post('judul'),
								'id_kategori' => $this->input->post('kategori'),
								'id_penerbit' => $this->input->post('penerbit'),
								'id_pengarang' => $this->input->post('pengarang'),
								'no_rak' => $this->input->post('no_rak'),
								'thn_terbit' => $this->input->post('thn_terbit'),
								'ket' => $this->input->post('keterangan'),
								'img' => $this->upload->file_name,
							);
							$this->Buku_model->insertData('tb_buku', $data);
							// $this->session->set_flashdata('sukses','pesan sukses tertambah');
							// $setFlash('berhasil','ditambahkan','success');
							redirect('admin/Buku/buku');
							exit;
						} else {
							/*layout*/
							$data['title'] = 'Tambah Buku';
							$data['pointer'] = "buku/buku";
							$data['classicon'] = "fa fa-book";
							$data['main_bread'] = "Daftar Buku";
							$data['sub_bread'] = "Tambah Buku";
							$data['desc'] = "Form menambahkan data buku Perpustakaan";

							/*data yang ditampilkan*/
							$data['data_kategori'] = $this->Buku_model->getAllData("tb_kategori");
							$data['data_penerbit'] = $this->Buku_model->getAllData("tb_penerbit");
							$data['data_pengarang'] = $this->Buku_model->getAllData("tb_pengarang");
							$data['data_rak'] = $this->Buku_model->getAllData("tb_rak");
							/*masukan data kedalam view */
							$tmp['content'] = $this->load->view('admin/buku/C_buku', $data, TRUE);
							// $this->session->set_flashdata('sukses','data buku berhasil ditambahkan');
							$this->load->view('admin/layout', $tmp);
							exit;
						}
					}
				}
				/**/
			}
		} else {
			$this->session->set_flashdata('sukses', 'pesan sukses tertambah');
			header('location:' . base_url() . 'admin/R_buku');
		}
	}

	//edit buku
	public function edit_buku()
	{
		$data['log'] = $this->db->get_where('tb_petugas', array('id_petugas' => $this->session->userdata('username')))->result();
		$cek = $this->session->userdata('logged_in');
		$stts = $this->session->userdata('stts');
		if (!empty($cek) && $stts == 'admin') {
			$id_buku = $this->input->get('id_buku', TRUE);

			/*layout*/
			$data['title'] = 'Edit Buku';
			$data['pointer'] = "buku/buku";
			$data['classicon'] = "fa fa-book";
			$data['main_bread'] = "Daftar Buku";
			$data['sub_bread'] = "Edit Buku";
			$data['desc'] = "Form untuk melakukan edit data buku Perpustakaan";

			/*data yang ditampilkan*/
			$data['data_kategori'] = $this->Buku_model->getAllData("tb_kategori");
			$data['data_penerbit'] = $this->Buku_model->getAllData("tb_penerbit");
			$data['data_pengarang'] = $this->Buku_model->getAllData("tb_pengarang");
			$data['data_rak'] = $this->Buku_model->getAllData("tb_rak");
			$data['buku'] = $this->Buku_model->get_detail('tb_buku', 'id_buku', $id_buku);

			/*masukan data kedalam view */
			$tmp['content'] = $this->load->view('admin/buku/U_buku', $data, TRUE);
			$this->load->view('admin/layout', $tmp);
		} else {
			header('location:' . base_url() . 'login/auth');
		}
	}

	//update buku
	public function update_buku()
	{
		$data['log'] = $this->db->get_where('tb_petugas', array('id_petugas' => $this->session->userdata('username')))->result();
		$cek = $this->session->userdata('logged_in');
		$stts = $this->session->userdata('stts');
		if (!empty($cek) && $stts == 'admin') {
			$this->form_validation->set_rules('judul', 'judul', 'required');
			$this->form_validation->set_rules('kategori', 'kategori', 'required');
			$this->form_validation->set_rules('penerbit', 'penerbit', 'required');
			$this->form_validation->set_rules('pengarang', 'pengarang', 'required');
			$this->form_validation->set_rules('no_rak', 'no_rak', 'required');


			$id_buku = $this->input->post('id');


			if ($this->form_validation->run() === FALSE) {
				$data['title'] = 'Edit Buku';
				$data['pointer'] = "buku/buku";
				$data['classicon'] = "fa fa-book";
				$data['main_bread'] = "Daftar Buku";
				$data['sub_bread'] = "Edit Buku";
				$data['desc'] = "Form untuk melakukan edit data buku Perpustakaan";

				/*data yang ditampilkan*/
				$data['data_kategori'] = $this->Buku_model->getAllData("tb_kategori");
				$data['data_penerbit'] = $this->Buku_model->getAllData("tb_penerbit");
				$data['data_pengarang'] = $this->Buku_model->getAllData("tb_pengarang");
				$data['data_rak'] = $this->Buku_model->getAllData("tb_rak");
				$data['buku'] = $this->Buku_model->get_detail('tb_buku', 'id_buku', $id_buku);

				/*masukan data kedalam view */
				$tmp['content'] = $this->load->view('admin/buku/U_buku', $data, TRUE);
				$this->load->view('admin/layout', $tmp);
			} else {
				$gambar = $_FILES['foto']['name'];
				if (empty($gambar)) {
					$id_buku = $this->input->post('id');
					$data = array(
						'ISBN' => $this->input->post('ISBN'),
						'judul' => $this->input->post('judul'),
						'id_kategori' => $this->input->post('kategori'),
						'id_penerbit' => $this->input->post('penerbit'),
						'id_pengarang' => $this->input->post('pengarang'),
						'no_rak' => $this->input->post('no_rak'),
						'thn_terbit' => $this->input->post('thn_terbit'),
						'ket' => $this->input->post('keterangan'),
					);

					$this->Buku_model->updateData('tb_buku', $data, $id_buku, 'id_buku');
					redirect('admin/Buku/Buku', 'refresh');
				} else {
					$id_buku = $this->input->post('id');
					$alphanum                = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
					$nama                    = str_shuffle($alphanum); //random nama dengan alphanum
					$config['file_name']     = $nama;
					$config['upload_path']   = "gambar_petugas"; // lokasi penyimpanan file
					$config['allowed_types'] = 'gif|jpg|JPEG|png|JPEG|BMP|bmp'; // format foto yang diizinkan
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if (!$this->upload->do_upload('foto')) {
						//echo $$_FILES['file_name'];
						$data['pesan']      = $this->upload->display_errors();
						$data['title'] = 'Edit Buku';
						$data['pointer'] = "buku/buku";
						$data['classicon'] = "fa fa-book";
						$data['main_bread'] = "Daftar Buku";
						$data['sub_bread'] = "Edit Buku";
						$data['desc'] = "Form untuk melakukan edit data buku Perpustakaan";

						/*data yang ditampilkan*/
						$data['data_kategori'] = $this->Buku_model->getAllData("tb_kategori");
						$data['data_penerbit'] = $this->Buku_model->getAllData("tb_penerbit");
						$data['data_pengarang'] = $this->Buku_model->getAllData("tb_pengarang");
						$data['data_rak'] = $this->Buku_model->getAllData("tb_rak");
						$data['buku'] = $this->Buku_model->get_detail('tb_buku', 'id_buku', $id_buku);

						/*masukan data kedalam view */
						$tmp['content'] = $this->load->view('admin/buku/U_buku', $data, TRUE);
						$this->load->view('admin/layout', $tmp);
					} else {
						$data = array(
							'ISBN' => $this->input->post('ISBN'),
							'judul' => $this->input->post('judul'),
							'id_kategori' => $this->input->post('kategori'),
							'id_penerbit' => $this->input->post('penerbit'),
							'id_pengarang' => $this->input->post('pengarang'),
							'no_rak' => $this->input->post('no_rak'),
							'thn_terbit' => $this->input->post('thn_terbit'),
							'ket' => $this->input->post('keterangan'),
							'img'	=> $this->upload->file_name,
						);
						$this->Buku_model->updateData('tb_buku', $data, $id_buku, 'id_buku');
						redirect('admin/Buku/Buku', 'refresh');
					}
				}
			}
		} else {
			header('location:' . base_url() . 'login/auth');
		}
	}

	//menampilkan daftar detail stock buku
	public function detail_stok()
	{
		$data['log'] = $this->db->get_where('tb_petugas', array('id_petugas' => $this->session->userdata('username')))->result();
		$cek = $this->session->userdata('logged_in');
		$stts = $this->session->userdata('stts');
		/*jika status login Yes dan status admin tampilkan*/
		if (!empty($cek) && $stts == 'admin') {
			$id_buku = $this->input->get('id_buku', TRUE);
			/*layout*/
			$data['title'] = 'Daftar Detail Stock Buku';
			$data['pointer'] = "buku/buku/";
			$data['classicon'] = "fa fa-book";
			$data['main_bread'] = "Data Buku";
			$data['sub_bread'] = "Detail Stock Buku";
			$data['desc'] = "Data Detail Stock, Menampilkan Detail Stock Buku Perpustakaan";

			/*data yang ditampilkan*/
			$data['data_stok_buku'] = $this->Buku_model->get_detail("tb_detail_buku", 'id_buku', $id_buku);
			$data['data_kategori'] = $this->Buku_model->getAllData("tb_kategori");
			$data['data_penerbit'] = $this->Buku_model->getAllData("tb_penerbit");
			$data['data_pengarang'] = $this->Buku_model->getAllData("tb_pengarang");
			$data['data_rak'] = $this->Buku_model->getAllData("tb_rak");
			$data['id'] = $id_buku;
			$data['error'] = "";

			/*masukan data kedalam view */
			$tmp['content'] = $this->load->view('admin/buku/R_detail_stok', $data, TRUE);
			$this->load->view('admin/layout', $tmp);
		} else
		/*jika status login NO atau status bukan admin kembalikan ke login*/ {
			header('location:' . base_url() . 'login/auth');
		}
	}

	//hapus detail buku
	public function hapus_det_buku()
	{
		$data['log'] = $this->db->get_where('tb_petugas', array('id_petugas' => $this->session->userdata('username')))->result();
		$cek = $this->session->userdata('logged_in');
		$stts = $this->session->userdata('stts');
		if (!empty($cek) && $stts == 'admin') {

			$id_buku = $this->input->get('id_buku', TRUE);
			$id_det_buku = $this->input->get('id_detail_buku', TRUE);

			$hapus = array('id_detail_buku' => $id_det_buku);
			$status = $this->Buku_model->get_detail1('tb_detail_buku', 'id_detail_buku', $id_det_buku);
			//jika status buku tersedia, maka dapat dihapus. jika status dipinjamkan tidak dapat dihapus
			if ($status['status'] == 1) {
				$this->Buku_model->deletedetData('tb_detail_buku', 'id_detail_buku', $id_det_buku);
				$stok_sebelum = $this->Buku_model->get_stok($id_buku)->stok;
				$stok_sesudah = $stok_sebelum - 1;
				$data2 = array(
					'stok' => $stok_sesudah,
				);
				$this->Buku_model->updateData('tb_buku', $data2, $id_buku, 'id_buku');
				header('location:' . base_url() . 'admin/buku/detail_stok/?id_buku=' . $id_buku . '');
			} else {
				//tampilkan error
				$this->session->set_flashdata("message", "<div class='callout callout-info'>
                <h4>Info!</h4>
                <p>Data stok buku tidak dapat dihapus karena status dipinjamkan</p>
                </div>");
				header('location:' . base_url() . 'admin/buku/detail_stok/?id_buku=' . $id_buku . '');
			}
		} else {
			header('location:' . base_url() . 'login/auth');
		}
	}

	//tambah detail buku
	public function tambah_det_buku()
	{
		$data['log'] = $this->db->get_where('tb_petugas', array('id_petugas' => $this->session->userdata('username')))->result();
		$cek = $this->session->userdata('logged_in');
		$stts = $this->session->userdata('stts');
		if (!empty($cek) && $stts == 'admin') {

			$this->form_validation->set_rules('no_buku1', 'no_buku1', 'required');
			$this->form_validation->set_rules('no_buku2', 'no_buku2', 'required');

			$id_buku = $this->input->get('id_buku', TRUE);


			if ($this->form_validation->run() === FALSE) {
				/*layout*/
				$data['title'] = 'Tambah Detail Stok Buku';
				$data['pointer'] = 'buku/detail_stok/?id_buku=' . $id_buku . '';
				$data['classicon'] = "fa fa-book";
				$data['main_bread'] = "Detail Stok Buku";
				$data['sub_bread'] = "Tambah Detail Stok";
				$data['desc'] = "Form menambahkan data detail stok buku Perpustakaan";
				$data['id_buku'] = $id_buku;
				/*masukan data kedalam view */
				$tmp['content'] = $this->load->view('admin/buku/C_detail_stok', $data, TRUE);
				$this->load->view('admin/layout', $tmp);
			} else {
				//ambil id buku
				$id_buku = $this->input->post('id_buku');

				//ambil no awal dan no akhir buku (range)
				$no_awal = $this->input->post('no_buku1');
				$no_akhir = $this->input->post('no_buku2');

				//validasi no awal tidak boleh lebih besar dari no akhir
				if ($no_awal > $no_akhir) {
					//tampilkan error
					$this->session->set_flashdata("message", "<div class='callout callout-info'>
		                <h4>Info!</h4>
		                <p>No awal tidak boleh lebih besar dari No akhir</p>
		                </div>");
					header('location:' . base_url() . 'admin/buku/tambah_det_buku/?id_buku=' . $id_buku . '');
				} else {

					//deklarasi array
					$no_buku = array();
					$data = array();
					//masukan masing - masing no buku awal sampai akhir
					for ($i = $no_awal; $i <= $no_akhir; $i++) {
						$no_buku[] = $i;
					}
					//hitung jumlah buku
					$jml = count($no_buku);
					//masukan no buku beserta id buku dan status nya
					for ($i = 0; $i < $jml; $i++) {
						$data[] = array(
							'id_buku' => $this->input->post('id_buku'),
							'no_buku' => $no_buku[$i],
							'status' => $this->input->post('status'),
						);
					}

					//insert buku dengan no buku secara berurutan sesuai range
					$this->Buku_model->insertData_batch('tb_detail_buku', $data);

					//update stock
					$stok_sebelum = $this->Buku_model->get_stok($id_buku)->stok;
					$stok_sesudah = $stok_sebelum + $jml;
					$data2 = array(
						'stok' => $stok_sesudah,
					);
					$this->Buku_model->updateData('tb_buku', $data2, $id_buku, 'id_buku');

					header('location:' . base_url() . 'admin/buku/detail_stok/?id_buku=' . $id_buku . '');
				}
			}
		} else {

			header('location:' . base_url() . 'login/auth');
		}
	}

	//edit buku
	public function edit_det_buku()
	{
		$data['log'] = $this->db->get_where('tb_petugas', array('id_petugas' => $this->session->userdata('username')))->result();
		$cek = $this->session->userdata('logged_in');
		$stts = $this->session->userdata('stts');
		if (!empty($cek) && $stts == 'admin') {
			$id_det_buku = $this->input->get('id_detail_buku', TRUE);
			$id_buku = $this->input->get('id_buku', TRUE);

			/*layout*/
			$data['title'] = 'Edit Detail Stok Buku';
			$data['pointer'] = 'buku/detail_stok/?id_buku=' . $id_buku . '';
			$data['classicon'] = "fa fa-book";
			$data['main_bread'] = "Detail Stok Buku";
			$data['sub_bread'] = "Edit Stok Buku";
			$data['desc'] = "Form untuk melakukan edit detail stok buku Perpustakaan";
			$data['id_detail_buku'] = $id_det_buku;
			$data['id_buku'] = $id_buku;
			$data['det_buku'] = $this->Buku_model->get_detail('tb_detail_buku', 'id_detail_buku', $id_det_buku);

			/*masukan data kedalam view */
			$tmp['content'] = $this->load->view('admin/buku/U_detail_stok', $data, TRUE);
			$this->load->view('admin/layout', $tmp);
		} else {
			header('location:' . base_url() . 'login/auth');
		}
	}

	//update buku
	public function update_det_buku()
	{
		$data['log'] = $this->db->get_where('tb_petugas', array('id_petugas' => $this->session->userdata('username')))->result();
		$cek = $this->session->userdata('logged_in');
		$stts = $this->session->userdata('stts');
		if (!empty($cek) && $stts == 'admin') {
			$id_det_buku = $this->input->get('id_det_buku', TRUE);
			$id_buku = $this->input->get('id_buku', TRUE);

			$this->form_validation->set_rules('no_buku', 'no_buku', 'required');

			if ($this->form_validation->run() === FALSE) {
				$data['title'] = 'Edit Detail Stok Buku';
				$data['pointer'] = 'buku/detail_stok/?id_buku=' . $id_buku . '';
				$data['classicon'] = "fa fa-book";
				$data['main_bread'] = "Detail Stok Buku";
				$data['sub_bread'] = "Edit Stok Buku";
				$data['desc'] = "Form untuk melakukan edit detail stok buku Perpustakaan";
				$data['det_buku'] = $this->Buku_model->get_detail('tb_detail_buku', 'id_detail_buku', $id_det_buku);

				/*masukan data kedalam view */
				$tmp['content'] = $this->load->view('admin/buku/U_detail_stok', $data, TRUE);
				$this->load->view('admin/layout', $tmp);
			} else {

				$id_buku = $this->input->post('id_buku');
				$data = array(
					'id_buku' => $this->input->post('id_buku'),
					'no_buku' => $this->input->post('no_buku'),
					'status' => $this->input->post('status'),
				);

				$this->Buku_model->updateData('tb_detail_buku', $data, $id_det_buku, 'id_detail_buku');
				header('location:' . base_url() . 'admin/buku/detail_stok/?id_buku=' . $id_buku . '');
			}
		} else {
			header('location:' . base_url() . 'login/auth');
		}
	}
}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */
