# core

## Instalasi

Untuk instalasi dengan projek kosong, jalankan perintah di bawah:

```bash
mim app init
```

Untuk instalasi di projek yang sudah berjalan, jalankan perintah di bawah:

```bash
mim app install core
```

## Test

Beberapa bagian dari test ini harus memodifikasi system php pada saat runtime,
sehingga diperlukan instalasi extensi `uopz` dan `xdebug`.
Jalankan perintah `pecl install uopz` untuk meng-install `uopz`, dan 
`pecl install xdebug` untuk meng-install `xdebug` sebelum menjalankan test.

```bash
phpunit --process-isolation test
```