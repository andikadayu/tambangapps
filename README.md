# Requirements
| Name                      | Version |
| ------------------------- | ------- |
| PHP(gd extension enabled) | 8.1.6   |
| Composer                  | 2.4.1   |
| Laravel Framework         | 9.19    |
| MariaDB                   | 10.4.24 |

# Task List
Buat aplikasi pemesanan kendaraan dengan ketentuan sebagai berikut :
| Task                                                                                                     |                         Status                          |
| -------------------------------------------------------------------------------------------------------- | :-----------------------------------------------------: |
| Terdapat 2 user (admin dan pihak yang menyetujui)                                                        |                   :heavy_check_mark:                    |
| Admin dapat menginputkan pemesanan kendaraan dan menentukan driver serta pihak yang menyetujui pemesanan |                   :heavy_check_mark:                    |
| Persetujuan dilakukan berjenjang minimal 2 level                                                         | Hanya Sekedar Persetujuan Banyak Orang tidak harus urut |
| Pihak yang menyetujui dapat melakukan persetujuan melalui aplikasi                                       |                   :heavy_check_mark:                    |
| Terdapat dashboard yang menampilkan grafik pemakaian kendaraan                                           |                   :heavy_check_mark:                    |
| Terdapat laporan periodik pemesanan kendaraan yang dapat di export (Excel)                               |                   :heavy_check_mark:                    |

# How To Install
1. Install All Dependcies
    ```bash
    composer install
    ```
2. Copy Env File
   ```cmd
    copy .env.example .env
   ```
   or 
   ```bash
    cp .env.example .env
   ```
3. Generate New App Key
   ```bash
    php artisan key:generate
   ```
4. Migrate Database & Seeding Database
   ```bash
    php artisan migrate && php artisan db:seed --class=DatabaseSeeder
   ``` 
5. Run Apss and open on website http://localhost:8000
   ```bash
   php artisan serve
   ```
# How to Use
1. Untuk melihat username dan password silahkan buka phpmyadmin/Dbeaver/Navicat untuk melihat list data di bagian user , lihat username masing-masing. username dan password dari user generate otomatis dari database seeding untuk password defaultnya 'password'
2. setelah login di bagian dashboard terdapat 2 tabel yaitu history logs serta penggunaan dalam bulanan pada tahun sekarang
3. di menu Schedule, untuk menambahkan data klik tombol "+" di bagian atas kanan dan isi semua data, untuk approval minimal 2 Orang (tidak harus urut)
4. lalu pada tabel juga ada beberapa action yaitu info warna biru dan hapus data warna merah
5. saat di info akan dialihkan ke menu detail dan bisa merubah beberapa data(admin), untuk approval tidak bisa
6. di menu info ini juga user approver bisa centang maupun tolak berulang kali
7. lalu pada menu schedule ada tombol cetak untuk di mencetak data dalam bentuk excel