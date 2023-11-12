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
namun point point pada todo list tugas studi kasus sudah terpenuhi untuk item pengerjaan PHP