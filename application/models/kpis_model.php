<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kpis_model extends CI_Model {

    // Nama tabel yang akan diinteraksi oleh model ini
    private $table = 'kpis';

    public function __construct()
    {
        parent::__construct();
        // Memuat library database secara otomatis saat model diinisialisasi
        $this->load->database();
    }

   
    public function get_all_kpis()
    {
        $query = $this->db->get($this->table);
        return $query->result(); // Mengembalikan hasil dalam bentuk array objek
    }

    /**
     * Mengambil satu definisi KPI berdasarkan ID-nya.
     * @param int $kpi_id ID KPI yang akan diambil.
     * @return object|null Objek KPI jika ditemukan, null jika tidak.
     */
    public function get_kpi_by_id($kpi_id)
    {
        $query = $this->db->get_where($this->table, array('kpi_id' => $kpi_id));
        return $query->row(); // Mengembalikan satu baris dalam bentuk objek
    }

    /**
     * Menambahkan definisi KPI baru ke database.
     * @param array $data Data KPI yang akan ditambahkan (kpi_name, kpi_category, description, status).
     * @return bool True jika berhasil, false jika gagal.
     */
    public function add_kpi($data)
    {
        $table = 'kpis';
        return $this->db->insert($this->table, $data);
    }

    /**
     * Memperbarui definisi KPI yang sudah ada.
     * @param int $kpi_id ID KPI yang akan diperbarui.
     * @param array $data Data KPI yang akan diperbarui.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function update_kpi($kpi_id, $data)
    {
        $this->db->where('kpi_id', $kpi_id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Menghapus definisi KPI dari database.
     * @param int $kpi_id ID KPI yang akan dihapus.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function delete_kpi($kpi_id)
    {
        $this->db->where('kpi_id', $kpi_id);
        return $this->db->delete($this->table);
    }

    /**
     * Mengambil daftar kategori KPI yang tersedia dari ENUM di tabel.
     * Ini berguna untuk dropdown di form.
     * Catatan: Ini adalah cara yang lebih sederhana untuk mengambil nilai ENUM.
     * Untuk solusi yang lebih robust, Anda bisa mendefinisikan kategori ini di array konstan.
     */
    public function get_kpi_categories()
    {
        // Karena ENUM sudah didefinisikan, kita bisa hardcode untuk kesederhanaan.
        // Atau ambil dari metadata database, tapi itu lebih kompleks.
        return ['Individu', 'Organisasi', 'Perusahaan'];
    }

    /**
     * Mengambil daftar status KPI yang tersedia dari ENUM di tabel.
     */
    public function get_kpi_statuses()
    {
        return ['active', 'inactive'];
    }
}