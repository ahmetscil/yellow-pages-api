# BAŞLANGIÇ
```bash
# kurulum ve ilk kullanım
$ make init

# standart kullanım
$ make up

# API URL
http://localhost:8080

# DATABASE
  DB URL: http://127.0.0.1:4306
  DB USER: admin
  DB PASS: 123123
  DB NAME: yellowpages
```
# KULLANIM NOTLARI
* JWT sistem geliştirmesi tamamlanamadı.
* JWT olmadığı için client tarafından userID gönderilmesi gerekiyor.
* * client bu işi kendiliğnden tamamlıyor.


# KİŞİ LİSTESİ yönetimi
Tüm CRUD işlemleri gerçekleştirilir.

* POST     http://localhost:8080/people        Yeni Kayıt
* GET      http://localhost:8080/people        Tüm Liste
* GET      http://localhost:8080/people/{id}   USER ID rehberi
* PUT      http://localhost:8080/people/{id}   Update
* DELETE   http://localhost:8080/people/{id}   Delete

# TELEFON numaraları yönetimi
People tablosundaki her bir kullanıcıya sınırsız telefon numarası tanımlanabilir

Tüm CRUD işlemleri gerçekleştirilir.
* POST     http://localhost:8080/phone         Yeni Kayıt
* GET      http://localhost:8080/phone         Tüm Liste
* GET      http://localhost:8080/phone/{id}    Seçili Kayıt
* PUT      http://localhost:8080/phone/{id}    Update
* DELETE   http://localhost:8080/phone/{id}    Delete

# TELEFON numaraları yönetimi
People tablosundaki her bir kullanıcıya sınırsız telefon numarası tanımlanabilir

Tüm CRUD işlemleri gerçekleştirilir.

* POST     http://localhost:8080/phone         Yeni Kayıt
* GET      http://localhost:8080/phone         Tüm Liste
* GET      http://localhost:8080/phone/{id}    Seçili Kayıt
* PUT      http://localhost:8080/phone/{id}    Update
* DELETE   http://localhost:8080/phone/{id}    Delete
