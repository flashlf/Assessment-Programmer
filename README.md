# Assessment-Programmer
Repository Tugas untuk training Assesment Programmer Neuron 2023

# HTML CSS
### Todo list HTML & CSS
- [x] List Film = ``list.html``, Pemilihan Tempat Duduk = ``seating.html``, Menu Pembayaran = ``pembayaran.html``
- [x] 2 Menu view/GUI menggunakan HTML+CSS Bootstrap ``index.html`` ``seating.html``
- [x] 1 Menu tanpa Framework bootstrap ``pembayaran.html``
- [x] 1 UI Fitur Tambahan ``index.html``

### Notes
Library jquery / js sebenernya gadigunakan sih, cuma yaudah lah ya

# SQL
Belum dicatet udah kelewat

# PHP
Membuat program untuk todolist dengan fitur CRUD, detail item pada point todo list
### Todo List PHP
- [x] Login Page (index utama modal login dan signup)
- [x] Feature Cookie untuk Login
- [x] Object Oriented
    - [x] Namespace
    - [x] Magic method
    - [x] Overidding Method
    - [x] Interface & Abstract
    - [x] Collection
- [x] Security
    - [x] Handling CSRF
    - [x] Handling XSS
    - [x] Handling SQL Injection
- [x] Feature attachment (Validate Size & Extension File)
- [x] RESTAPI CRUD Published
    - [x] Using HTTP Auth (Without Session)
    - [x] I/O Json Formatted
    - [x] Error Handling
    - [x] Validate Variable


### Auth Api
pastikan route untuk file ``.htpasswd`` sudah sesuai pada file ``todo\.htaccess``
username : aduls
password : e10adc3949ba59abbe56e057f20f883e

### Vhost XAMPP
``apache/conf/extra/httpd-vhosts.conf`` tambahkan pada baris terakhir
```xml
<VirtualHost *:80>
    ServerAdmin webmaster@example.com
    DocumentRoot "C:\xampp-7\htdocs\Assessment-Programmer\PHP"
    ServerName adul.todo.app
    ErrorLog "logs/adultodo-errorr.log"
    CustomLog "logs/adultodo-access.log" common
    <Directory "C:\xampp-7\htdocs\Assessment-Programmer\PHP\asset\uploads">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
<VirtualHost *:443>
     DocumentRoot "C:\xampp-7\htdocs\Assessment-Programmer\PHP"
     ServerName adul.todo.app
     ServerAlias *.adul.todo.app
     SSLEngine on
     SSLCertificateFile "crt/adul.todo.app/server.crt"
     SSLCertificateKeyFile "crt/adul.todo.app/server.key"
 	<Directory "C:\xampp-7\htdocs\Assessment-Programmer\PHP">
        Options All
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>

```

### host windows

```c++
127.0.0.1 adul.todo.app
```

## Notes PHP
integrasi backend dengan frontend belum semuanya XD,
namun point point pada todo list tugas studi kasus sudah terpenuhi untuk item pengerjaan PHP.
- file sqldump untuk db terletak pada ``asset/sqldump for db``
- untuk evidence postman belum sempat semuanya tercapture bisa dilihat pada ``asset/Evidence Postman``, namun sudah disediakan collection postman beserta dengan sample responsenya
- Untuk sertifikat dummy ssl agar bisa berjalan protokol https pada lokal xampp,  bisa di extract terlebih dahulu file ``mia.co.d.zip`` ke folder ``apache\crt`` kemudian bisa di install terlebih dahulu sertifikat ssl dummynya ke bagian Trusted Root Certificate Authorities ``pastikan di remove kembali setelah melakukan penilaian``
- 


# JS
Membuat aplikasi shopping menggunakan JS dan HTML,CSS dengan studi kasus seperti berikut  
- User dapat meilaht produk
- Menambahkan produk ke keranjang
- Menghitung diskon took ?
- Menghitung diskon menggunakan kupon
- Proses checkout

### Todo list JS
- [x] Buatlah variable untuk menyimpan data username dan jumlah item yang ada pada keranjang
- [ ] Buatlah sebuah perhitungan untuk menghitung total harga dari setiap item yang ditambahkan ke keranjang
- [ ] Buatlah sebuah fungsi untuk menyimpan item pada keranjang
- [ ] Buatlah sebuah arrow function untuk menghitung total harga setelah diskon 
- [ ] Buatlah contoh penggunaan sebuah variable global untuk menghitung total dengan ongkos kirim
- [ ] Buatlah contoh pengunaan functional scope variable untuk menghitung diskon tambahan menggunakan coupon
- [ ] Buatlah event pada tombol untuk melakukan checkout
- [x] Buatlah sebuah Asynchronous function untuk mendapaktkan data product dari API
- [ ] Buatlah sebuah fungsi untuk mengambil data product detail dari API
- [ ] Buatlah class Product
- [ ] Buatlah Error Handling pada saat menggunakan API