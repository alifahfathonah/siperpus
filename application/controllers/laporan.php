<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Export to Excel multiple sheets with CI and Spout
 *
 * @author budy k
 *
 */

//load Spout Library
require_once APPPATH . '/third_party/spout/src/Spout/Autoloader/autoload.php';
//lets Use the Spout Namespaces
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\StyleBuilder;

class laporan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper("url");
        $this->load->model('m_katalog_buku');
        $this->load->model('m_sirkulasi');
        $this->load->model('m_jenis_denda');
        $this->load->model('m_pelanggaran');
        $this->load->model('m_status_buku');
        $this->load->model('m_stock_opname');
        is_logged_in();
    }
    public function Barcode($id = 12332)
    {
        $this->zend->load('Zend/Barcode');
        Zend_Barcode::render('code128', 'image', array('text' => $id));
    }

    function get_ajax_admin($kategori = null)
    {
        if ($kategori == 'laporan') {
            $list = $this->m_katalog_buku->get_datatables_laporan();
        } else {
            $list = $this->m_katalog_buku->get_datatables();
        }
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $no . ".";
            $row[] = $item->register;
            $row[] = $item->judul_buku;
            $row[] = $item->pengarang;
            $row[] = $item->penerbit;
            $row[] = $item->tahun_terbit;
            if ($item->status_buku == 1) {
                $row[] = '<span class="badge badge-success">tersedia</span>';
            } else {
                $row[] = '<span class="badge badge-secondary">dipinjam</span>';
            }
            // add html for action
            $row[] =  $item->isbn;
            $row[] = $item->no_dewey;
            $row[] = $item->kota_terbit;
            $row[] = $item->nama_bahasa;
            $row[] = $item->nama_circ_type;
            $row[] = $item->nama_funding;
            $row[] = $item->nama_sumber;
            $row[] = $item->author_abrev;
            $row[] = $item->title_abrev;
            $row[] = $item->volume;
            $row[] = $item->kondisi_fisik;
            $row[] = $item->bibliography;
            $row[] = $item->subject;
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->m_katalog_buku->count_all(),
            "recordsFiltered" => $this->m_katalog_buku->count_filtered(),
            "data" => $data,
        );
        // output to json format
        echo json_encode($output);
    }

    function get_ajax_koleksi_digital()
    {

        $list = $this->m_katalog_buku->get_datatables_koleksi();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $no . ".";
            $row[] = $item->register;
            $row[] = $item->judul_buku;
            $row[] = $item->pengarang;
            $row[] = $item->penerbit;
            $row[] = $item->tahun_terbit;
            $row[] = $item->digital_pdf;

            // add html for action
            $row[] =  $item->isbn;
            $row[] = $item->no_dewey;
            $row[] = $item->pengarang;
            $row[] = $item->nama_bahasa;
            $row[] = $item->nama_circ_type;
            $row[] = $item->nama_funding;
            $row[] = $item->nama_sumber;
            $row[] = $item->author_abrev;
            $row[] = $item->title_abrev;
            $row[] = $item->volume;
            $row[] = $item->kondisi_fisik;
            $row[] = $item->bibliography;
            $row[] = $item->subject;
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->m_katalog_buku->count_all_digital(),
            "recordsFiltered" => $this->m_katalog_buku->count_filtered_digital(),
            "data" => $data,
        );
        // output to json format
        echo json_encode($output);
    }


    // yusril
    public function peminjaman()
    {
        $data['title'] = "Laporan Peminjaman";
        $data['buku_dipinjam'] =  $this->m_katalog_buku->getBukuDipinjam(null, $this->input->get('status_sirkulasi'), $this->input->get('start_date'), $this->input->get('end_date'));
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar');
        $this->load->view('templates/sidebar');
        $this->load->view('laporan/peminjaman', $data);
        $this->load->view('templates/footer');
    }

    // yusril
    public function keranjang_buku()
    {
        $data['title'] = "Laporan Keranjang Buku";
        $data['buku'] =  $this->m_sirkulasi->getDataKeranjang($this->input->get('start_date'), $this->input->get('end_date'));
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar');
        $this->load->view('templates/sidebar');
        $this->load->view('laporan/keranjang_buku', $data);
        $this->load->view('templates/footer');
    }
    // yusril
    public function koleksi_buku()
    {
        $data['title'] = "Laporan Koleksi Buku";
        $data['jenis_koleksi'] = $this->db->get('jenis_koleksi')->result_array();
        $data['status_buku'] = $this->db->get('status_buku')->result_array();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar');
        $this->load->view('templates/sidebar');
        $this->load->view('laporan/katalog_buku', $data);
        $this->load->view('templates/footer');
    }
    // kharis
    public function perpanjangan()
    {
    }
    // fadli
    public function sangsi()
    {
        $data['title'] = "Laporan Sangsi";
        $data['sirkulasi_pelanggaran'] =  $this->m_pelanggaran->getListPelanggaran(null, $this->input->get('id_pelanggaran'), null, $this->input->get('start_date'), $this->input->get('end_date'));
        $data['pelanggaran'] =  $this->m_pelanggaran->getPelanggaran();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar');
        $this->load->view('templates/sidebar');
        $this->load->view('laporan/sangsi', $data);
        $this->load->view('templates/footer');
    }
    // khris
    public function koleksi_sering_dipinjam()
    {
    }
    // fadli
    public function keterlambatan()
    {
        $data['title'] = "Laporan Keterlambatan";
        $data['sirkulasi_pelanggaran'] =  $this->m_pelanggaran->getListPelanggaran(null, 1, null, $this->input->get('start_date'), $this->input->get('end_date'));
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar');
        $this->load->view('templates/sidebar');
        $this->load->view('laporan/keterlambatan', $data);
        $this->load->view('templates/footer');
    }
    // kharis
    public function baca_ditempat()
    {
    }
    // fadli
    public function stock_opname()
    {
        $data['title'] = "Laporan Stock Opname";
        $data['data_status_buku'] = $this->m_status_buku->getData();
        $id_opname = [];
        $status_opname = [];
        $opname = $this->m_stock_opname->getDataLaporanOpname(null, $this->input->get('start_date'), $this->input->get('end_date'));
        if (count($opname) >= 1) {
            foreach ($opname as $o) {
                if ($o['status_now'] != null || $o['akses_now'] != null) {
                    $status_opname[$o['id_opname']][] = $o['status_now'];
                } else {
                    $status_opname[$o['id_opname']][] = null;
                }
                $id_opname[] = $o['id_opname'] . "/" . $o['keterangan'] . "/" . substr($o['tanggal'], 0, 10);
            }
        }
        $id_opname = array_unique($id_opname);
        sort($id_opname);
        $data['data_opname'] = $id_opname;
        $data['count_opname'] = $status_opname;
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar');
        $this->load->view('templates/sidebar');
        $this->load->view('laporan/stock_opname', $data);
        $this->load->view('templates/footer');
    }
    // yusril
    public function koleksi_digital()
    {
        $data['title'] = "Laporan Koleksi Buku";
        $this->load->view('templates/header', $data);
        $this->load->view('templates/topbar');
        $this->load->view('templates/sidebar');
        $this->load->view('laporan/koleksi_digital', $data);
        $this->load->view('templates/footer');
    }
}
